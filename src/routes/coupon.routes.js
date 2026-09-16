const express = require('express');
const router = express.Router();
const { getOne } = require('../config/db');

// Validate and calculate coupon discount
router.post('/validate', (req, res) => {
  try {
    const { code, cartTotal } = req.body;
    if (!code || !code.trim()) {
      return res.status(400).json({ success: false, message: 'Please enter a coupon code.' });
    }

    const subtotal = Number(cartTotal);
    if (isNaN(subtotal) || subtotal <= 0) {
      return res.status(400).json({ success: false, message: 'Invalid cart amount.' });
    }

    const coupon = getOne('SELECT * FROM coupons WHERE UPPER(code) = UPPER(?)', [code.trim()]);
    if (!coupon) {
      return res.status(404).json({ success: false, message: 'Invalid coupon code.' });
    }

    if (!coupon.is_active) {
      return res.status(400).json({ success: false, message: 'This coupon code is currently inactive.' });
    }

    if (coupon.valid_until && new Date(coupon.valid_until) < new Date()) {
      return res.status(400).json({ success: false, message: 'This coupon has expired.' });
    }

    if (coupon.usage_limit && coupon.times_used >= coupon.usage_limit) {
      return res.status(400).json({ success: false, message: 'Coupon usage limit has been reached.' });
    }

    if (coupon.min_order_amount && subtotal < coupon.min_order_amount) {
      return res.status(400).json({
        success: false,
        message: `Minimum order amount of ₹${coupon.min_order_amount} required to use coupon "${coupon.code}".`
      });
    }

    let discountAmount = 0;
    if (coupon.discount_type === 'percentage') {
      discountAmount = (subtotal * coupon.discount_value) / 100;
      if (coupon.max_discount && coupon.max_discount > 0) {
        discountAmount = Math.min(discountAmount, coupon.max_discount);
      }
    } else {
      discountAmount = Math.min(coupon.discount_value, subtotal);
    }

    discountAmount = Math.round(discountAmount * 100) / 100;
    const newTotal = Math.max(0, Math.round((subtotal - discountAmount) * 100) / 100);

    res.json({
      success: true,
      message: `Coupon "${coupon.code}" applied successfully! You saved ₹${discountAmount}.`,
      coupon: {
        code: coupon.code,
        discountType: coupon.discount_type,
        discountValue: coupon.discount_value,
        discountAmount,
        newTotal
      }
    });
  } catch (err) {
    console.error('Coupon validation error:', err);
    res.status(500).json({ success: false, message: 'Failed to validate coupon.' });
  }
});

module.exports = router;
