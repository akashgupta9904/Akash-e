const express = require('express');
const router = express.Router();
const { query, getOne } = require('../config/db');

// List all categories with product count
router.get('/', (req, res) => {
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

// Single category by slug
router.get('/:slug', (req, res) => {
  try {
    const category = getOne('SELECT * FROM categories WHERE slug = ?', [req.params.slug]);
    if (!category) {
      return res.status(404).json({ success: false, message: 'Category not found.' });
    }
    const products = query('SELECT * FROM products WHERE category_id = ? ORDER BY id DESC', [category.id]);
    res.json({ success: true, category, products });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch category details.' });
  }
});

module.exports = router;
