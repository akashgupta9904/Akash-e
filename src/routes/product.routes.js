const express = require('express');
const router = express.Router();
const { query, getOne } = require('../config/db');

// List products with advanced search, filtering, and sorting
router.get('/', (req, res) => {
  try {
    const {
      search,
      category,
      min_price,
      max_price,
      sort,
      featured,
      in_stock,
      page = 1,
      limit = 12
    } = req.query;

    const conditions = [];
    const params = [];

    if (search && search.trim()) {
      conditions.push('(p.name LIKE ? OR p.description LIKE ?)');
      params.push(`%${search.trim()}%`, `%${search.trim()}%`);
    }

    if (category && category !== 'all') {
      // Can be category slug or ID
      if (isNaN(Number(category))) {
        conditions.push('c.slug = ?');
        params.push(category);
      } else {
        conditions.push('p.category_id = ?');
        params.push(Number(category));
      }
    }

    if (min_price && !isNaN(Number(min_price))) {
      conditions.push('p.price >= ?');
      params.push(Number(min_price));
    }

    if (max_price && !isNaN(Number(max_price))) {
      conditions.push('p.price <= ?');
      params.push(Number(max_price));
    }

    if (featured === 'true' || featured === '1') {
      conditions.push('p.is_featured = 1');
    }

    if (in_stock === 'true' || in_stock === '1') {
      conditions.push('p.stock > 0');
    }

    const whereClause = conditions.length > 0 ? `WHERE ${conditions.join(' AND ')}` : '';

    // Sorting
    let orderBy = 'p.id DESC';
    if (sort === 'price_asc') orderBy = 'p.price ASC';
    else if (sort === 'price_desc') orderBy = 'p.price DESC';
    else if (sort === 'discount') orderBy = 'p.discount_percent DESC';
    else if (sort === 'rating') orderBy = 'p.rating DESC';
    else if (sort === 'newest') orderBy = 'p.id DESC';

    // Count total matching
    const countSql = `
      SELECT COUNT(*) as total
      FROM products p
      LEFT JOIN categories c ON c.id = p.category_id
      ${whereClause}
    `;
    const totalCount = getOne(countSql, params).total;

    const pageNum = Math.max(1, Number(page));
    const limitNum = Math.max(1, Math.min(50, Number(limit)));
    const offset = (pageNum - 1) * limitNum;

    const listSql = `
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p
      LEFT JOIN categories c ON c.id = p.category_id
      ${whereClause}
      ORDER BY ${orderBy}
      LIMIT ? OFFSET ?
    `;
    const products = query(listSql, [...params, limitNum, offset]);

    res.json({
      success: true,
      products,
      pagination: {
        total: totalCount,
        page: pageNum,
        limit: limitNum,
        totalPages: Math.ceil(totalCount / limitNum)
      }
    });
  } catch (err) {
    console.error('Products fetch error:', err);
    res.status(500).json({ success: false, message: 'Failed to fetch products.' });
  }
});

// Featured products
router.get('/featured', (req, res) => {
  try {
    const products = query(`
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p
      LEFT JOIN categories c ON c.id = p.category_id
      WHERE p.is_featured = 1
      ORDER BY p.id DESC
      LIMIT 8
    `);
    res.json({ success: true, products });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch featured products.' });
  }
});

// Product detail by slug or ID
router.get('/:identifier', (req, res) => {
  try {
    const { identifier } = req.params;
    let product;

    if (!isNaN(Number(identifier))) {
      product = getOne(`
        SELECT p.*, c.name as category_name, c.slug as category_slug
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.id = ?
      `, [Number(identifier)]);
    } else {
      product = getOne(`
        SELECT p.*, c.name as category_name, c.slug as category_slug
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.slug = ?
      `, [identifier]);
    }

    if (!product) {
      return res.status(404).json({ success: false, message: 'Product not found.' });
    }

    // Parse gallery
    let gallery = [];
    try {
      gallery = JSON.parse(product.gallery_json || '[]');
    } catch (e) {
      gallery = [];
    }

    // Related products in same category
    const related = query(`
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p
      LEFT JOIN categories c ON c.id = p.category_id
      WHERE p.category_id = ? AND p.id != ?
      ORDER BY p.id DESC
      LIMIT 4
    `, [product.category_id, product.id]);

    res.json({
      success: true,
      product: {
        ...product,
        gallery
      },
      related
    });
  } catch (err) {
    console.error('Product detail error:', err);
    res.status(500).json({ success: false, message: 'Failed to fetch product details.' });
  }
});

module.exports = router;
