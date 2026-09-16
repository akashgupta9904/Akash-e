const jwt = require('jsonwebtoken');
const { getOne } = require('../config/db');

const JWT_SECRET = process.env.JWT_SECRET || 'nexus_super_secret_jwt_key_2026';

function verifyToken(token) {
  try {
    return jwt.verify(token, JWT_SECRET);
  } catch (err) {
    return null;
  }
}

function extractToken(req) {
  const authHeader = req.headers['authorization'];
  if (authHeader && authHeader.startsWith('Bearer ')) {
    return authHeader.split(' ')[1];
  }
  if (req.cookies && req.cookies.token) {
    return req.cookies.token;
  }
  if (req.query && req.query.token) {
    return req.query.token;
  }
  return null;
}

function optionalAuth(req, res, next) {
  const token = extractToken(req);
  if (!token) {
    req.user = null;
    return next();
  }
  const payload = verifyToken(token);
  if (payload && payload.id) {
    const user = getOne('SELECT id, name, email, role, phone FROM users WHERE id = ?', [payload.id]);
    req.user = user || null;
  } else {
    req.user = null;
  }
  next();
}

function requireAuth(req, res, next) {
  const token = extractToken(req);
  if (!token) {
    return res.status(401).json({ success: false, message: 'Authentication required. Please login.' });
  }
  const payload = verifyToken(token);
  if (!payload || !payload.id) {
    return res.status(401).json({ success: false, message: 'Invalid or expired session. Please log in again.' });
  }

  const user = getOne('SELECT id, name, email, role, phone FROM users WHERE id = ?', [payload.id]);
  if (!user) {
    return res.status(401).json({ success: false, message: 'User account not found.' });
  }

  req.user = user;
  next();
}

function requireAdmin(req, res, next) {
  requireAuth(req, res, () => {
    if (req.user.role !== 'admin') {
      return res.status(403).json({ success: false, message: 'Access denied: Admin privileges required.' });
    }
    next();
  });
}

function generateToken(user) {
  return jwt.sign(
    {
      id: user.id,
      email: user.email,
      role: user.role,
      name: user.name
    },
    JWT_SECRET,
    { expiresIn: '7d' }
  );
}

module.exports = {
  optionalAuth,
  requireAuth,
  requireAdmin,
  generateToken,
  JWT_SECRET
};
