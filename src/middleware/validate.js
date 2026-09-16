// Sanitize and validate input payloads
function sanitizeString(str) {
  if (typeof str !== 'string') return '';
  return str.trim().replace(/[<>]/g, '');
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return typeof email === 'string' && emailRegex.test(email.trim());
}

function validateRegister(req, res, next) {
  const { name, email, password } = req.body;
  if (!name || name.trim().length < 2) {
    return res.status(400).json({ success: false, message: 'Name must be at least 2 characters.' });
  }
  if (!email || !isValidEmail(email)) {
    return res.status(400).json({ success: false, message: 'Please provide a valid email address.' });
  }
  if (!password || password.length < 6) {
    return res.status(400).json({ success: false, message: 'Password must be at least 6 characters long.' });
  }
  req.body.name = sanitizeString(name);
  req.body.email = email.trim().toLowerCase();
  next();
}

function validateLogin(req, res, next) {
  const { email, password } = req.body;
  if (!email || typeof email !== 'string' || email.trim().length === 0) {
    return res.status(400).json({ success: false, message: 'Please provide a valid username or email address.' });
  }
  if (!password) {
    return res.status(400).json({ success: false, message: 'Password is required.' });
  }
  req.body.email = email.trim().toLowerCase();
  next();
}

function validateProduct(req, res, next) {
  const { name, price, stock } = req.body;
  if (!name || name.trim().length === 0) {
    return res.status(400).json({ success: false, message: 'Product name is required.' });
  }
  const numPrice = Number(price);
  if (isNaN(numPrice) || numPrice <= 0) {
    return res.status(400).json({ success: false, message: 'Valid positive price is required.' });
  }
  const numStock = Number(stock !== undefined ? stock : 0);
  if (isNaN(numStock) || numStock < 0) {
    return res.status(400).json({ success: false, message: 'Valid stock count is required.' });
  }
  req.body.name = sanitizeString(name);
  if (req.body.description) req.body.description = sanitizeString(req.body.description);
  req.body.price = numPrice;
  req.body.stock = Math.floor(numStock);
  if (req.body.discount_percent !== undefined) {
    const disc = Number(req.body.discount_percent);
    req.body.discount_percent = isNaN(disc) || disc < 0 ? 0 : Math.min(100, disc);
  }
  next();
}

module.exports = {
  sanitizeString,
  isValidEmail,
  validateRegister,
  validateLogin,
  validateProduct
};
