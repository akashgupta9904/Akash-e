const express = require('express');
const router = express.Router();
const { query, getOne, execute, db } = require('../config/db');
const { optionalAuth, requireAuth } = require('../middleware/auth');
const { sanitizeString, isValidEmail } = require('../middleware/validate');

function generateOrderNumber() {
  const dateStr = new Date().toISOString().slice(0, 10).replace(/-/g, '');
  const randomSuffix = Math.floor(1000 + Math.random() * 9000);
  return `NX-${dateStr}-${randomSuffix}`;
}

// Create an order
router.post('/create', optionalAuth, (req, res) => {
  try {
    const {
      customer_name,
      customer_email,
      customer_phone,
      shipping_address,
      payment_method = 'test_gateway',
      coupon_code
    } = req.body;

    if (!customer_name || !customer_email || !customer_phone || !shipping_address) {
      return res.status(400).json({ success: false, message: 'Customer name, email, phone, and shipping address are required.' });
    }

    if (!isValidEmail(customer_email)) {
      return res.status(400).json({ success: false, message: 'Please provide a valid email address.' });
    }

    // Handle Direct Panel Buy or Cart Items
    let itemsToOrder = [];
    let subtotal = 0;

    if (req.body.direct_item) {
      const { name, price, quantity = 1 } = req.body.direct_item;
      const numPrice = Number(price) || 80;
      subtotal = numPrice * quantity;
      itemsToOrder.push({
        product_id: null,
        name: name || 'Gaming Panel Access',
        final_price: numPrice,
        quantity: quantity
      });
    } else {
      // Get cart items
      let cartFilter = '';
      let cartParam = null;
      if (req.user && req.user.id) {
        cartFilter = 'c.user_id = ?';
        cartParam = req.user.id;
      } else {
        const sessionId = req.headers['x-session-id'] || req.body.session_id || 'guest_default_session';
        cartFilter = 'c.session_id = ?';
        cartParam = sessionId;
      }

      const cartItems = query(`
        SELECT 
          c.id as cart_item_id,
          c.quantity,
          p.id as product_id,
          p.name,
          p.price,
          p.discount_percent,
          p.stock,
          ROUND(p.price * (1 - p.discount_percent / 100.0), 2) as final_price
        FROM cart_items c
        JOIN products p ON p.id = c.product_id
        WHERE ${cartFilter}
      `, [cartParam]);

      if (!cartItems || cartItems.length === 0) {
        return res.status(400).json({ success: false, message: 'Your shopping cart is empty.' });
      }

      // Check stock for every item
      for (const item of cartItems) {
        if (item.quantity > item.stock) {
          return res.status(400).json({
            success: false,
            message: `Cannot place order. Product "${item.name}" only has ${item.stock} in stock, but you requested ${item.quantity}.`
          });
        }
      }

      itemsToOrder = cartItems;
      for (const item of cartItems) {
        subtotal += item.final_price * item.quantity;
      }
    }

    // Validate and calculate coupon if provided
    let discountAmount = 0;
    let validCouponCode = null;
    if (coupon_code && coupon_code.trim()) {
      const coupon = getOne('SELECT * FROM coupons WHERE UPPER(code) = UPPER(?) AND is_active = 1', [coupon_code.trim()]);
      if (coupon) {
        let isExpired = coupon.valid_until && new Date(coupon.valid_until) < new Date();
        let limitReached = coupon.usage_limit && coupon.times_used >= coupon.usage_limit;
        let meetsMinOrder = !coupon.min_order_amount || subtotal >= coupon.min_order_amount;

        if (!isExpired && !limitReached && meetsMinOrder) {
          validCouponCode = coupon.code;
          if (coupon.discount_type === 'percentage') {
            discountAmount = (subtotal * coupon.discount_value) / 100;
            if (coupon.max_discount && coupon.max_discount > 0) {
              discountAmount = Math.min(discountAmount, coupon.max_discount);
            }
          } else {
            discountAmount = Math.min(coupon.discount_value, subtotal);
          }
          // Increment coupon usage
          execute('UPDATE coupons SET times_used = times_used + 1 WHERE id = ?', [coupon.id]);
        }
      }
    }

    discountAmount = Math.round(discountAmount * 100) / 100;
    const isDirect = !!req.body.direct_item;
    const shippingFee = isDirect ? 0 : ((subtotal - discountAmount) >= 1000 ? 0 : 99);
    const totalAmount = Math.max(0, Math.round((subtotal - discountAmount + shippingFee) * 100) / 100);

    const orderNumber = req.body.order_number || generateOrderNumber();
    const userId = req.user ? req.user.id : null;
    const addressJson = typeof shipping_address === 'string' ? shipping_address : JSON.stringify(shipping_address);

    // Initial payment status
    let paymentStatus = 'pending';
    let orderStatus = 'pending';

    if (payment_method === 'test_gateway' || payment_method === 'upi_qr') {
      paymentStatus = 'paid';
      orderStatus = 'processing';
    } else if (payment_method === 'cod') {
      paymentStatus = 'pending';
      orderStatus = 'pending';
    }

    // Create order
    const orderRes = execute(`
      INSERT INTO orders (
        order_number, user_id, customer_name, customer_email, customer_phone,
        shipping_address_json, subtotal, discount_amount, coupon_code,
        shipping_fee, total_amount, payment_method, payment_status, order_status
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [
      orderNumber,
      userId,
      sanitizeString(customer_name),
      customer_email.trim().toLowerCase(),
      sanitizeString(customer_phone),
      addressJson,
      subtotal,
      discountAmount,
      validCouponCode,
      shippingFee,
      totalAmount,
      payment_method,
      paymentStatus,
      orderStatus
    ]);

    const orderId = orderRes.lastInsertRowid;

    // Insert order items and deduct stock
    for (const item of itemsToOrder) {
      const itemSubtotal = Math.round(item.final_price * item.quantity * 100) / 100;
      execute(`
        INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
      `, [orderId, item.product_id || null, item.name, item.final_price, item.quantity, itemSubtotal]);

      // Deduct stock if linked product exists
      if (item.product_id) {
        execute(`UPDATE products SET stock = MAX(0, stock - ?) WHERE id = ?`, [item.quantity, item.product_id]);
      }
    }

    // Clear cart if cart order
    if (!isDirect) {
      if (userId) {
        execute('DELETE FROM cart_items WHERE user_id = ?', [userId]);
      } else {
        execute('DELETE FROM cart_items WHERE session_id = ?', [cartParam]);
      }
    }

    // If test gateway was chosen and marked paid, log payment record
    if (paymentStatus === 'paid') {
      const txId = `SIM_TX_${Date.now()}`;
      execute(`
        INSERT INTO payments (order_id, gateway, transaction_id, amount, currency, status, signature, raw_payload)
        VALUES (?, ?, ?, ?, 'INR', 'success', 'test_signature_ok', ?)
      `, [orderId, payment_method, txId, totalAmount, JSON.stringify({ mode: 'simulator', time: new Date() })]);
    }

    res.status(201).json({
      success: true,
      message: 'Order placed successfully!',
      orderNumber,
      orderId,
      totalAmount,
      paymentStatus,
      paymentMethod: payment_method
    });
  } catch (err) {
    console.error('Create order error:', err);
    res.status(500).json({ success: false, message: 'Failed to create order.' });
  }
});

// My orders (Customer portal)
router.get('/my', requireAuth, (req, res) => {
  try {
    const orders = query(`
      SELECT * FROM orders 
      WHERE user_id = ? 
      ORDER BY id DESC
    `, [req.user.id]);

    const orderList = [];
    for (const order of orders) {
      const items = query('SELECT * FROM order_items WHERE order_id = ?', [order.id]);
      orderList.push({
        ...order,
        items
      });
    }

    res.json({ success: true, orders: orderList });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch customer orders.' });
  }
});

// Order lookup by Order Number
router.get('/details/:orderNumber', (req, res) => {
  try {
    const { orderNumber } = req.params;
    const order = getOne('SELECT * FROM orders WHERE order_number = ?', [orderNumber]);
    if (!order) {
      return res.status(404).json({ success: false, message: 'Order not found.' });
    }

    const items = query('SELECT * FROM order_items WHERE order_id = ?', [order.id]);
    const payments = query('SELECT * FROM payments WHERE order_id = ?', [order.id]);

    let address = {};
    try {
      address = JSON.parse(order.shipping_address_json);
    } catch (e) {
      address = { street: order.shipping_address_json };
    }

    res.json({
      success: true,
      order: {
        ...order,
        address,
        items,
        payments
      }
    });
  } catch (err) {
    console.error('Order details error:', err);
    res.status(500).json({ success: false, message: 'Failed to fetch order details.' });
  }
});

module.exports = router;
