require('dotenv').config();
const express = require('express');
const path = require('path');
const cors = require('cors');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');

// Database initialization & seeding
const { initSchema } = require('./src/config/db');
const { seedDatabase } = require('./src/config/seed');

// Initialize database
initSchema();
seedDatabase();

const app = express();
const PORT = process.env.PORT || 3000;

// Security Headers
app.use(
  helmet({
    contentSecurityPolicy: false, // Allows Unsplash images, Google Fonts, FontAwesome, CDN scripts
    crossOriginEmbedderPolicy: false
  })
);

app.use(cors());
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Rate Limiting (Section 15: Security)
const apiLimiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 500, // 500 requests per window
  message: { success: false, message: 'Too many requests from this IP, please try again after 15 minutes.' }
});

const authLimiter = rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 30, // 30 login/register attempts per 15 min
  message: { success: false, message: 'Too many authentication attempts. Please wait 15 minutes.' }
});

app.use('/api/', apiLimiter);
app.use('/api/auth/login', authLimiter);
app.use('/api/auth/register', authLimiter);

// SEO Routes (Section 12)
app.use('/', require('./src/routes/seo.routes'));

// API Routes
app.use('/api/auth', require('./src/routes/auth.routes'));
app.use('/api/categories', require('./src/routes/category.routes'));
app.use('/api/products', require('./src/routes/product.routes'));
app.use('/api/cart', require('./src/routes/cart.routes'));
app.use('/api/coupons', require('./src/routes/coupon.routes'));
app.use('/api/orders', require('./src/routes/order.routes'));
app.use('/api/payments', require('./src/routes/payment.routes'));
app.use('/api/proofs', require('./src/routes/proof.routes'));
app.use('/api/settings', require('./src/routes/setting.routes'));
app.use('/api/admin', require('./src/routes/admin.routes'));

// Health Check Endpoint
app.get('/api/health', (req, res) => {
  res.json({
    status: 'healthy',
    timestamp: new Date(),
    uptime: process.uptime(),
    platform: 'Nexus E-Commerce Engine v1.0.0'
  });
});

// Serve static frontend assets from public directory with no-cache for HTML files
app.use(express.static(path.join(__dirname, 'public'), {
  setHeaders: (res, filePath) => {
    if (filePath.endsWith('.html')) {
      res.setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
      res.setHeader('Pragma', 'no-cache');
      res.setHeader('Expires', '0');
    }
  }
}));

// Clean URL Routes & .php aliases
app.get(['/android', '/android.php'], (req, res) => res.sendFile(path.join(__dirname, 'public/android.html')));
app.get(['/ios', '/ios.php'], (req, res) => res.sendFile(path.join(__dirname, 'public/ios.html')));
app.get(['/proofs', '/proofs.php'], (req, res) => res.sendFile(path.join(__dirname, 'public/proofs.html')));
app.get(['/gameplay', '/gameplay.php'], (req, res) => res.sendFile(path.join(__dirname, 'public/gameplay.html')));
app.get(['/policy', '/policy.php'], (req, res) => res.sendFile(path.join(__dirname, 'public/policy.html')));
app.get('/login', (req, res) => res.sendFile(path.join(__dirname, 'public/login.html')));
app.get('/admin', (req, res) => res.sendFile(path.join(__dirname, 'public/admin/index.html')));

// Fallback route for SPA or root
app.get('*', (req, res, next) => {
  if (req.path.startsWith('/api/')) {
    return res.status(404).json({ success: false, message: 'API endpoint not found' });
  }
  next();
});

// Centralized Error Handling
app.use((err, req, res, next) => {
  console.error('Unhandled server error:', err);
  res.status(err.status || 500).json({
    success: false,
    message: err.message || 'Internal server error occurred.'
  });
});

const os = require('os');

function getLocalIp() {
  const ifaces = os.networkInterfaces();
  for (const list of Object.values(ifaces)) {
    for (const info of list) {
      if (info.family === 'IPv4' && !info.internal) {
        return info.address;
      }
    }
  }
  return 'localhost';
}

const localIp = getLocalIp();

app.listen(PORT, '0.0.0.0', () => {
  console.log(`
  =============================================================
  ⚡ AKASH X STORE PLATFORM ENGINE RUNNING ON WI-FI / HOTSPOT
  =============================================================
  🌐 PC Storefront:     http://localhost:${PORT}
  📱 PHONE STOREFRONT:  http://${localIp}:${PORT}
  👑 PHONE OWNER LOGIN: http://${localIp}:${PORT}/login.html
  🔐 Owner Key:         akash1245
  =============================================================
  `);
});

