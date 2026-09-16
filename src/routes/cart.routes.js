const express = require('express');
const router = express.Router();
const { query, getOne, execute } = require('../config/db');
const { optionalAuth } = require('../middleware/auth');

function getCartFilter(req) {
  if (req.user && req.user.id) {
    return { clause: 'user_id = ?', param: req.user.id, type: 'user' };
  }
  const sessionId = req.headers['x-session-id'] || req.query.session_id || 'guest_default_session';
  return { clause: 'session_id = ?', param: sessionId, type: 'session' };
}

// Get Cart items & calculated totals
router.get('/', optionalAuth, (req, res) => {
  try {
    const filter = getCartFilter(req);
    const items = query(`
      SELECT 
        c.id as cart_item_id,
        c.quantity,
        p.id as product_id,
        p.name,
        p.slug,
        p.price,
        p.discount_percent,
        p.stock,
        p.image_url,
        ROUND(p.price * (1 - p.discount_percent / 100.0), 2) as final_price
      FROM cart_items c
      JOIN products p ON p.id = c.product_id
      WHERE c.${filter.clause}
      ORDER BY c.id DESC
    `, [filter.param]);

    let subtotal = 0;
    let savings = 0;
    let totalItems = 0;

    for (const item of items) {
      const originalItemTotal = item.price * item.quantity;
      const discountedItemTotal = item.final_price * item.quantity;
      subtotal += discountedItemTotal;
      savings += (originalItemTotal - discountedItemTotal);
      totalItems += item.quantity;
    }

    const freeShippingThreshold = 1000;
    const shippingFee = subtotal >= freeShippingThreshold || subtotal === 0 ? 0 : 99;
    const finalTotal = subtotal + shippingFee;

    res.json({
      success: true,
      items,
      summary: {
        totalItems,
        subtotal: Math.round(subtotal * 100) / 100,
        savings: Math.round(savings * 100) / 100,
        shippingFee,
        freeShippingThreshold,
        freeShippingRemaining: Math.max(0, freeShippingThreshold - subtotal),
        finalTotal: Math.round(finalTotal * 100) / 100
      }
    });
  } catch (err) {
    console.error('Cart fetch error:', err);
    res.status(500).json({ success: false, message: 'Failed to fetch cart.' });
  }
});

// Add to cart
router.post('/add', optionalAuth, (req, res) => {
  try {
    const { productId, quantity = 1 } = req.body;
    const numQty = Math.max(1, Number(quantity));

    const product = getOne('SELECT id, name, price, stock FROM products WHERE id = ?', [productId]);
    if (!product) {
      return res.status(404).json({ success: false, message: 'Product not found.' });
    }

    if (product.stock <= 0) {
      return res.status(400).json({ success: false, message: 'Product is currently out of stock.' });
    }

    const filter = getCartFilter(req);
    const existing = getOne(`SELECT id, quantity FROM cart_items WHERE ${filter.clause} AND product_id = ?`, [filter.param, productId]);

    if (existing) {
      const newQty = existing.quantity + numQty;
      if (newQty > product.stock) {
        return res.status(400).json({
          success: false,
          message: `Only ${product.stock} units available in stock. You already have ${existing.quantity} in your cart.`
        });
      }
      execute('UPDATE cart_items SET quantity = ? WHERE id = ?', [newQty, existing.id]);
    } else {
      if (numQty > product.stock) {
        return res.status(400).json({
          success: false,
          message: `Only ${product.stock} units available in stock.`
        });
      }

      if (filter.type === 'user') {
        execute(
          'INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)',
          [filter.param, productId, numQty]
        );
      } else {
        execute(
          'INSERT INTO cart_items (session_id, product_id, quantity) VALUES (?, ?, ?)',
          [filter.param, productId, numQty]
        );
      }
    }

    res.json({ success: true, message: `${product.name} added to cart!` });
  } catch (err) {
    console.error('Cart add error:', err);
    res.status(500).json({ success: false, message: 'Failed to add item to cart.' });
  }
});

// Update item quantity
router.put('/update', optionalAuth, (req, res) => {
  try {
    const { cartItemId, quantity } = req.body;
    const filter = getCartFilter(req);

    const item = getOne(`
      SELECT c.id, c.product_id, p.stock, p.name 
      FROM cart_items c 
      JOIN products p ON p.id = c.product_id 
      WHERE c.id = ? AND c.${filter.clause}
    `, [cartItemId, filter.param]);

    if (!item) {
      return res.status(404).json({ success: false, message: 'Cart item not found.' });
    }

    const numQty = Number(quantity);
    if (numQty <= 0) {
      execute('DELETE FROM cart_items WHERE id = ?', [cartItemId]);
      return res.json({ success: true, message: 'Item removed from cart.' });
    }

    if (numQty > item.stock) {
      return res.status(400).json({
        success: false,
        message: `Cannot increase quantity beyond available stock (${item.stock} available).`
      });
    }

    execute('UPDATE cart_items SET quantity = ? WHERE id = ?', [numQty, cartItemId]);
    res.json({ success: true, message: 'Cart updated successfully.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to update cart.' });
  }
});

// Remove item
router.delete('/remove/:id', optionalAuth, (req, res) => {
  try {
    const filter = getCartFilter(req);
    execute(`DELETE FROM cart_items WHERE id = ? AND ${filter.clause}`, [req.params.id, filter.param]);
    res.json({ success: true, message: 'Item removed from cart.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to remove item.' });
  }
});

// Clear cart
router.delete('/clear', optionalAuth, (req, res) => {
  try {
    const filter = getCartFilter(req);
    execute(`DELETE FROM cart_items WHERE ${filter.clause}`, [filter.param]);
    res.json({ success: true, message: 'Cart cleared.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to clear cart.' });
  }
});

// Merge guest cart on login
router.post('/merge', optionalAuth, (req, res) => {
  try {
    if (!req.user || !req.user.id) {
      return res.status(401).json({ success: false, message: 'User must be authenticated to merge.' });
    }
    const { sessionId } = req.body;
    if (!sessionId) {
      return res.json({ success: true, message: 'No guest session provided.' });
    }

    const guestItems = query('SELECT product_id, quantity FROM cart_items WHERE session_id = ?', [sessionId]);
    for (const g of guestItems) {
      const existing = getOne('SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?', [req.user.id, g.product_id]);
      if (existing) {
        execute('UPDATE cart_items SET quantity = quantity + ? WHERE id = ?', [g.quantity, existing.id]);
      } else {
        execute('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)', [req.user.id, g.product_id, g.quantity]);
      }
    }
    // Delete guest items
    execute('DELETE FROM cart_items WHERE session_id = ?', [sessionId]);

    res.json({ success: true, message: 'Guest cart merged successfully.' });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to merge cart.' });
  }
});

module.exports = router;
