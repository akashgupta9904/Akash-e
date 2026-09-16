const express = require('express');
const router = express.Router();
const { query } = require('../config/db');

const defaultStoreSettings = {
  site_title: 'Akash X Store',
  site_subtitle: 'OFFICIAL AKASH X STORE SHOP',
  logo_url: '/assets/img/logo.png',
  upi_id: 'igakash@fam',
  upi_name: 'Akash X Store',
  upi_qr_url: '',
  whatsapp: '+91 9135164069',
  telegram: 'https://t.me/Real_Panel_100',
  telegram_id: '@Real_Panel_100',
  price_1day: '35',
  price_3days: '45',
  price_7days: '55',
  price_monthly: '150',
  universal_key: '7744',
  announcement: '🔥 Season 43 Anti-Ban v2.8 Updated! Direct UPI Payment & Instant Key Release.'
};

// GET /api/settings — Public endpoint for frontend store settings (UPI, WhatsApp, Telegram, Prices, etc.)
router.get('/', (req, res) => {
  try {
    const settings = { ...defaultStoreSettings };
    try {
      const rows = query('SELECT key, value FROM site_settings');
      rows.forEach(r => {
        settings[r.key] = r.value;
      });
    } catch (dbErr) {
      console.warn('DB settings read notice:', dbErr.message);
    }

    // Ensure telegram_id is computed if only telegram link is present
    if (!settings.telegram_id && settings.telegram) {
      const parts = settings.telegram.split('/');
      const last = parts[parts.length - 1];
      if (last) settings.telegram_id = '@' + last.replace('@', '');
    }

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
    res.json({
      success: true,
      settings: {
        ...defaultStoreSettings,
        prices: { '1': 35, '3': 45, '7': 55, '30': 150 }
      }
    });
  }
});

module.exports = router;
