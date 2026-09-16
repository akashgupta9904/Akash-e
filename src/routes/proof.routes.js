const express = require('express');
const router = express.Router();
const { query } = require('../config/db');

// GET /api/proofs — Public endpoint to list all customer proofs
router.get('/', (req, res) => {
  try {
    const proofs = query('SELECT * FROM proofs ORDER BY id DESC');
    res.json({ success: true, proofs });
  } catch (err) {
    console.error('Error fetching proofs:', err);
    res.status(500).json({ success: false, message: 'Failed to fetch proofs.' });
  }
});

module.exports = router;
