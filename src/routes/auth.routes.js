const express = require('express');
const router = express.Router();
const bcrypt = require('bcryptjs');
const { query, getOne, execute } = require('../config/db');
const { requireAuth, generateToken } = require('../middleware/auth');
const { validateRegister, validateLogin, sanitizeString } = require('../middleware/validate');

// Register
router.post('/register', validateRegister, (req, res) => {
  try {
    const { name, email, password, phone } = req.body;
    const existing = getOne('SELECT id FROM users WHERE email = ?', [email]);
    if (existing) {
      return res.status(400).json({ success: false, message: 'An account with this email already exists.' });
    }

    const password_hash = bcrypt.hashSync(password, 10);
    const result = execute(
      `INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, 'customer', ?)`,
      [name, email, password_hash, phone ? sanitizeString(phone) : '']
    );

    const newUser = getOne('SELECT id, name, email, role, phone FROM users WHERE id = ?', [result.lastInsertRowid]);
    const token = generateToken(newUser);

    res.status(201).json({
      success: true,
      message: 'Account created successfully!',
      token,
      user: newUser
    });
  } catch (err) {
    console.error('Register error:', err);
    res.status(500).json({ success: false, message: 'Server error during registration.' });
  }
});

// Login
router.post('/login', validateLogin, (req, res) => {
  try {
    const { email, password } = req.body;

    // Direct check for Akash Owner master credentials
    if (email === 'akash@4141' && password === '4141') {
      let user = getOne('SELECT * FROM users WHERE email = ?', ['akash@4141']);
      if (!user) {
        const hash = bcrypt.hashSync('4141', 10);
        const r = execute(
          `INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, 'admin', ?)`,
          ['Akash Owner', 'akash@4141', hash, '+91 9135164069']
        );
        user = getOne('SELECT * FROM users WHERE id = ?', [r.lastInsertRowid]);
      }
      const safeUser = {
        id: user.id,
        name: user.name || 'Akash Owner',
        email: user.email,
        role: 'admin',
        phone: user.phone
      };
      const token = generateToken(safeUser);
      return res.json({
        success: true,
        message: 'Owner login successful!',
        token,
        user: safeUser
      });
    }

    const user = getOne('SELECT * FROM users WHERE email = ?', [email]);
    if (!user) {
      return res.status(401).json({ success: false, message: 'Invalid email or password.' });
    }

    const isMatch = bcrypt.compareSync(password, user.password_hash);
    if (!isMatch) {
      return res.status(401).json({ success: false, message: 'Invalid email or password.' });
    }

    const safeUser = {
      id: user.id,
      name: user.name,
      email: user.email,
      role: user.role,
      phone: user.phone
    };
    const token = generateToken(safeUser);

    res.json({
      success: true,
      message: 'Login successful!',
      token,
      user: safeUser
    });
  } catch (err) {
    console.error('Login error:', err);
    res.status(500).json({ success: false, message: 'Server error during login.' });
  }
});

// Current user profile
router.get('/me', requireAuth, (req, res) => {
  res.json({ success: true, user: req.user });
});

// Update profile
router.put('/profile', requireAuth, (req, res) => {
  try {
    const { name, phone, password } = req.body;
    const updates = [];
    const params = [];

    if (name && name.trim()) {
      updates.push('name = ?');
      params.push(sanitizeString(name));
    }
    if (phone !== undefined) {
      updates.push('phone = ?');
      params.push(sanitizeString(phone));
    }
    if (password && password.length >= 6) {
      updates.push('password_hash = ?');
      params.push(bcrypt.hashSync(password, 10));
    }

    if (updates.length > 0) {
      params.push(req.user.id);
      execute(`UPDATE users SET ${updates.join(', ')} WHERE id = ?`, params);
    }

    const updated = getOne('SELECT id, name, email, role, phone FROM users WHERE id = ?', [req.user.id]);
    res.json({ success: true, message: 'Profile updated successfully!', user: updated });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to update profile.' });
  }
});

// Addresses
router.get('/addresses', requireAuth, (req, res) => {
  try {
    const addresses = query('SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC', [req.user.id]);
    res.json({ success: true, addresses });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to fetch addresses.' });
  }
});

router.post('/addresses', requireAuth, (req, res) => {
  try {
    const { full_name, phone, street, city, state, postal_code, country, is_default } = req.body;
    if (!full_name || !phone || !street || !city || !state || !postal_code) {
      return res.status(400).json({ success: false, message: 'All address fields are required.' });
    }

    if (is_default) {
      execute('UPDATE addresses SET is_default = 0 WHERE user_id = ?', [req.user.id]);
    }

    const resDb = execute(
      `INSERT INTO addresses (user_id, full_name, phone, street, city, state, postal_code, country, is_default)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        req.user.id,
        sanitizeString(full_name),
        sanitizeString(phone),
        sanitizeString(street),
        sanitizeString(city),
        sanitizeString(state),
        sanitizeString(postal_code),
        country ? sanitizeString(country) : 'India',
        is_default ? 1 : 0
      ]
    );

    res.status(201).json({ success: true, message: 'Address saved successfully!', addressId: resDb.lastInsertRowid });
  } catch (err) {
    res.status(500).json({ success: false, message: 'Failed to save address.' });
  }
});

module.exports = router;
