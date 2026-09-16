const express = require('express');
const router = express.Router();
const path = require('path');
const fs = require('fs');
const multer = require('multer');
const { query, getOne, execute } = require('../config/db');
const { requireAdmin } = require('../middleware/auth');
const { validateProduct, sanitizeString } = require('../middleware/validate');

// Configure Multer for product image uploads
const uploadDir = path.join(__dirname, '../../public/uploads');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, uploadDir),
  filename: (req, file, cb) => {
    const ext = path.extname(file.originalname).toLowerCase();
    const uniqueName = `prod_${Date.now()}_${Math.round(Math.random() * 1e9)}${ext}`;
    cb(null, uniqueName);
  }
});

const upload = multer({
  storage,
  limits: { fileSize: 5 * 1024 * 1024 }, // 5MB max
  fileFilter: (req, file, cb) => {
    const allowed = /jpeg|jpg|png|webp|gif|svg/;
    const ext = path.extname(file.originalname).toLowerCase();
    if (allowed.test(ext)) {
      cb(null, true);
    } else {
      cb(new Error('Only image files (jpg, png, webp, svg) are allowed.'));
    }
  }
});

// All routes in this router require Admin authentication!
router.use(requireAdmin);

// 1. Dashboard Statistics
router.get('/stats', (req, res) => {
  try {
    const totalSalesRow = getOne("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid'");
    const totalOrdersRow = getOne("SELECT COUNT(*) as count FROM orders");
    const pendingOrdersRow = getOne("SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'");
    const totalCustomersRow = getOne("SELECT COUNT(*) as count FROM users WHERE role = 'customer'");
    const totalProductsRow = getOne("SELECT COUNT(*) as count FROM products");
    const lowStockRow = getOne("SELECT COUNT(*) as count FROM products WHERE stock <= 5");

    const recentOrders = query(`
      SELECT o.*, u.email as user_email
      FROM orders o
      LEFT JOIN users u ON u.id = o.user_id
      ORDER BY o.id DESC
      LIMIT 6
    `);

    // Top selling products
    const topProducts = query(`
      SELECT p.id, p.name, p.image_url, SUM(oi.quantity) as total_sold, SUM(oi.subtotal) as total_revenue
      FROM order_items oi
      JOIN products p ON p.id = oi.product_id
      JOIN orders o ON o.id = oi.order_id
      WHERE o.payment_status = 'paid'
      GROUP BY p.id
      ORDER BY total_sold DESC
      LIMIT 5
    `);

    res.json({
      success: true,
      stats: {
        totalRevenue: Math.round(totalSalesRow.total * 100) / 100,
        totalOrders: totalOrdersRow.count,
        pendingOrders: pendingOrdersRow.count,
        totalCustomers: totalCustomersRow.count,
        totalProducts: totalProductsRow.count,
        lowStockProducts: lowStockRow.count,
        recentOrders,
        topProducts
      }
    });
  } catch (err) {
    console.error('Admin stats error:', err);
    res.status(500).json({ success: false, message: 'Failed to fetch admin stats.' });
  }
});

// 2. Product Image Upload
router.post('/upload', upload.single('image'), (req, res) => {
  if (!req.file) {
    return res.status(400).json({ success: false, message: 'No file uploaded.' });
  }
  const fileUrl = `/uploads/${req.file.filename}`;
  res.json({ success: true, url: fileUrl, filename: req.file.filename });
});

// 3. Products Management (List, Add, Update, Delete)
router.get('/products', (req, res) => {
  try {
    const products = query(`
      SELECT p.*, c.name as category_name
      FROM products p
      LEFT JOIN categories c ON c.id = p.category_id
      ORDER BY p.id DESC
    `);
    res.json({ success: true, products });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch products.' });
  }
});

function slugify(text) {
  return text
    .toString()
    .toLowerCase()
    .trim()
    .replace(/[\s\W-]+/g, '-');
}

router.post('/products', validateProduct, (req, res) => {
  try {
    const { name, category_id, description, price, discount_percent = 0, stock = 10, is_featured = 0, image_url, gallery_json } = req.body;
    let baseSlug = slugify(name);
    let slug = baseSlug;
    let counter = 1;
    while (getOne('SELECT id FROM products WHERE slug = ?', [slug])) {
      slug = `${baseSlug}-${counter++}`;
    }

    const result = execute(`
      INSERT INTO products (name, slug, category_id, description, price, discount_percent, stock, is_featured, image_url, gallery_json)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [
      name,
      slug,
      category_id ? Number(category_id) : null,
      description || '',
      price,
      discount_percent,
      stock,
      is_featured ? 1 : 0,
      image_url || 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800',
      gallery_json || '[]'
    ]);

    res.status(201).json({ success: true, message: 'Product created successfully!', productId: result.lastInsertRowid });
  } catch (err) {
    console.error('Create product error:', err);
    res.status(500).json({ success: false, message: 'Failed to create product.' });
  }
});

router.put('/products/:id', validateProduct, (req, res) => {
  try {
    const { id } = req.params;
    const { name, category_id, description, price, discount_percent, stock, is_featured, image_url, gallery_json } = req.body;

    const existing = getOne('SELECT id FROM products WHERE id = ?', [id]);
    if (!existing) {
      return res.status(404).json({ success: false, message: 'Product not found.' });
    }

    execute(`
      UPDATE products SET
        name = ?,
        category_id = ?,
        description = ?,
        price = ?,
        discount_percent = ?,
        stock = ?,
        is_featured = ?,
        image_url = COALESCE(?, image_url),
        gallery_json = COALESCE(?, gallery_json)
      WHERE id = ?
    `, [
      name,
      category_id ? Number(category_id) : null,
      description || '',
      price,
      discount_percent,
      stock,
      is_featured ? 1 : 0,
      image_url,
      gallery_json,
      id
    ]);

    res.json({ success: true, message: 'Product updated successfully!' });
  } catch (err) {
    console.error('Update product error:', err);
    res.status(500).json({ success: false, message: 'Failed to update product.' });
  }
});

router.delete('/products/:id', (req, res) => {
  try {
    const { id } = req.params;
    execute('DELETE FROM products WHERE id = ?', [id]);
    res.json({ success: true, message: 'Product deleted successfully.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to delete product.' });
  }
});

// 4. Categories Management
router.get('/categories', (req, res) => {
  try {
    const categories = query(`
      SELECT c.*, COUNT(p.id) as product_count
      FROM categories c
      LEFT JOIN products p ON p.category_id = c.id
      GROUP BY c.id
      ORDER BY c.name ASC
    `);
    res.json({ success: true, categories });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch categories.' });
  }
});

router.post('/categories', (req, res) => {
  try {
    const { name, description, image_url } = req.body;
    if (!name || !name.trim()) {
      return res.status(400).json({ success: false, message: 'Category name is required.' });
    }
    const slug = slugify(name);
    const existing = getOne('SELECT id FROM categories WHERE slug = ?', [slug]);
    if (existing) {
      return res.status(400).json({ success: false, message: 'A category with this name already exists.' });
    }

    const result = execute(
      'INSERT INTO categories (name, slug, description, image_url) VALUES (?, ?, ?, ?)',
      [sanitizeString(name), slug, sanitizeString(description || ''), image_url || '']
    );

    res.status(201).json({ success: true, message: 'Category created successfully!', categoryId: result.lastInsertRowid });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to create category.' });
  }
});

router.delete('/categories/:id', (req, res) => {
  try {
    const { id } = req.params;
    execute('DELETE FROM categories WHERE id = ?', [id]);
    res.json({ success: true, message: 'Category deleted successfully.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to delete category.' });
  }
});

// 5. Orders Management
router.get('/orders', (req, res) => {
  try {
    const { status, search } = req.query;
    const conditions = [];
    const params = [];

    if (status && status !== 'all') {
      conditions.push('o.order_status = ?');
      params.push(status);
    }

    if (search && search.trim()) {
      conditions.push('(o.order_number LIKE ? OR o.customer_name LIKE ? OR o.customer_email LIKE ?)');
      params.push(`%${search.trim()}%`, `%${search.trim()}%`, `%${search.trim()}%`);
    }

    const whereClause = conditions.length > 0 ? `WHERE ${conditions.join(' AND ')}` : '';
    const orders = query(`
      SELECT o.*, u.email as account_email
      FROM orders o
      LEFT JOIN users u ON u.id = o.user_id
      ${whereClause}
      ORDER BY o.id DESC
    `, params);

    res.json({ success: true, orders });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch orders.' });
  }
});

// Update order status & payment status
router.put('/orders/:id/status', (req, res) => {
  try {
    const { id } = req.params;
    const { order_status, payment_status } = req.body;

    const allowedOrderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    const allowedPaymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

    const updates = [];
    const params = [];

    if (order_status) {
      if (!allowedOrderStatuses.includes(order_status)) {
        return res.status(400).json({ success: false, message: 'Invalid order status value.' });
      }
      updates.push('order_status = ?');
      params.push(order_status);
    }

    if (payment_status) {
      if (!allowedPaymentStatuses.includes(payment_status)) {
        return res.status(400).json({ success: false, message: 'Invalid payment status value.' });
      }
      updates.push('payment_status = ?');
      params.push(payment_status);
    }

    if (updates.length === 0) {
      return res.status(400).json({ success: false, message: 'No status updates provided.' });
    }

    params.push(id);
    execute(`UPDATE orders SET ${updates.join(', ')} WHERE id = ?`, params);

    res.json({ success: true, message: 'Order status updated successfully!' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to update order status.' });
  }
});

// 6. Coupons Management
router.get('/coupons', (req, res) => {
  try {
    const coupons = query('SELECT * FROM coupons ORDER BY id DESC');
    res.json({ success: true, coupons });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch coupons.' });
  }
});

router.post('/coupons', (req, res) => {
  try {
    const { code, discount_type, discount_value, min_order_amount, max_discount, valid_until, usage_limit } = req.body;
    if (!code || !code.trim()) {
      return res.status(400).json({ success: false, message: 'Coupon code is required.' });
    }
    const cleanCode = code.trim().toUpperCase();
    const existing = getOne('SELECT id FROM coupons WHERE code = ?', [cleanCode]);
    if (existing) {
      return res.status(400).json({ success: false, message: 'A coupon with this code already exists.' });
    }

    const result = execute(`
      INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_discount, valid_until, usage_limit, is_active)
      VALUES (?, ?, ?, ?, ?, ?, ?, 1)
    `, [
      cleanCode,
      discount_type === 'fixed' ? 'fixed' : 'percentage',
      Number(discount_value) || 10,
      Number(min_order_amount) || 0,
      Number(max_discount) || 0,
      valid_until || null,
      Number(usage_limit) || 1000
    ]);

    res.status(201).json({ success: true, message: 'Coupon created successfully!', couponId: result.lastInsertRowid });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to create coupon.' });
  }
});

router.delete('/coupons/:id', (req, res) => {
  try {
    const { id } = req.params;
    execute('DELETE FROM coupons WHERE id = ?', [id]);
    res.json({ success: true, message: 'Coupon deleted successfully.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to delete coupon.' });
  }
});

// 7. Customers Directory
router.get('/customers', (req, res) => {
  try {
    const customers = query(`
      SELECT 
        u.id, 
        u.name, 
        u.email, 
        u.phone, 
        u.created_at,
        COUNT(o.id) as total_orders,
        COALESCE(SUM(CASE WHEN o.payment_status = 'paid' THEN o.total_amount ELSE 0 END), 0) as total_spent
      FROM users u
      LEFT JOIN orders o ON o.user_id = u.id
      WHERE u.role = 'customer'
      GROUP BY u.id
      ORDER BY total_spent DESC, u.id DESC
    `);
    res.json({ success: true, customers });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch customers.' });
  }
});

module.exports = router;
