const express = require('express');
const router = express.Router();
const { query } = require('../config/db');

// GET /api/settings — Public endpoint for frontend store settings (UPI, WhatsApp, Telegram, Prices, etc.)
router.get('/', (req, res) => {
  try {
    const rows = query('SELECT key, value FROM site_settings');
    const settings = {};
    rows.forEach(r => {
      settings[r.key] = r.value;
    });

    // Provide structured prices for convenient frontend consumption
    settings.prices = {
      '1': Number(settings.price_1day || 35),
      '3': Number(settings.price_3days || 45),
      '7': Number(settings.price_7days || 55),
      '30': Number(settings.price_monthly || 150)
    };

    res.json({ success: true, settings });
  } catch (err) {
    console.error('Error fetching settings:', err);
    res.status(500).json({ success: false, message: 'Failed to fetch store settings.' });
  }
});

module.exports = router;
