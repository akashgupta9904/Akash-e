let DatabaseSync;
try {
  DatabaseSync = require('node:sqlite').DatabaseSync;
} catch (e) {
  try {
    DatabaseSync = require('sqlite').DatabaseSync;
  } catch (e2) {
    DatabaseSync = null;
  }
}

const path = require('path');
const fs = require('fs');

const dataDir = process.env.VERCEL ? '/tmp' : path.join(__dirname, '../../data');
if (!fs.existsSync(dataDir)) {
  try { fs.mkdirSync(dataDir, { recursive: true }); } catch (e) {}
}

const dbPath = path.join(dataDir, 'ecommerce.db');
let db = null;
if (DatabaseSync) {
  try {
    db = new DatabaseSync(dbPath);
    try {
      db.exec('PRAGMA journal_mode = WAL;');
      db.exec('PRAGMA foreign_keys = ON;');
    } catch (e) {}
  } catch (err) {
    console.error('Warning: could not initialize DatabaseSync:', err.message);
    db = null;
  }
}

// Initialize tables
function initSchema() {
  if (!db) return;
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      email TEXT UNIQUE NOT NULL,
      password_hash TEXT NOT NULL,
      role TEXT NOT NULL DEFAULT 'customer',
      phone TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS categories (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      slug TEXT UNIQUE NOT NULL,
      description TEXT,
      image_url TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS products (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      slug TEXT UNIQUE NOT NULL,
      category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
      description TEXT,
      price REAL NOT NULL,
      discount_percent REAL DEFAULT 0,
      stock INTEGER DEFAULT 10,
      image_url TEXT,
      gallery_json TEXT DEFAULT '[]',
      is_featured INTEGER DEFAULT 0,
      rating REAL DEFAULT 4.8,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS cart_items (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
      session_id TEXT,
      product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
      quantity INTEGER DEFAULT 1,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      UNIQUE(user_id, product_id),
      UNIQUE(session_id, product_id)
    );

    CREATE TABLE IF NOT EXISTS addresses (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
      full_name TEXT NOT NULL,
      phone TEXT NOT NULL,
      street TEXT NOT NULL,
      city TEXT NOT NULL,
      state TEXT NOT NULL,
      postal_code TEXT NOT NULL,
      country TEXT DEFAULT 'India',
      is_default INTEGER DEFAULT 0,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS coupons (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      code TEXT UNIQUE NOT NULL,
      discount_type TEXT NOT NULL DEFAULT 'percentage',
      discount_value REAL NOT NULL,
      min_order_amount REAL DEFAULT 0,
      max_discount REAL DEFAULT 0,
      valid_until DATETIME,
      usage_limit INTEGER DEFAULT 1000,
      times_used INTEGER DEFAULT 0,
      is_active INTEGER DEFAULT 1,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS orders (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      order_number TEXT UNIQUE NOT NULL,
      user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
      customer_name TEXT NOT NULL,
      customer_email TEXT NOT NULL,
      customer_phone TEXT NOT NULL,
      shipping_address_json TEXT NOT NULL,
      subtotal REAL NOT NULL,
      discount_amount REAL DEFAULT 0,
      coupon_code TEXT,
      shipping_fee REAL DEFAULT 0,
      total_amount REAL NOT NULL,
      payment_method TEXT NOT NULL DEFAULT 'test_gateway',
      payment_status TEXT NOT NULL DEFAULT 'pending',
      order_status TEXT NOT NULL DEFAULT 'pending',
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS order_items (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
      product_id INTEGER REFERENCES products(id) ON DELETE SET NULL,
      product_name TEXT NOT NULL,
      product_price REAL NOT NULL,
      quantity INTEGER NOT NULL,
      subtotal REAL NOT NULL
    );

    CREATE TABLE IF NOT EXISTS payments (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
      gateway TEXT NOT NULL,
      transaction_id TEXT UNIQUE,
      amount REAL NOT NULL,
      currency TEXT DEFAULT 'INR',
      status TEXT NOT NULL,
      signature TEXT,
      raw_payload TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS proofs (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      title TEXT NOT NULL,
      description TEXT,
      image_url TEXT NOT NULL,
      tag TEXT DEFAULT 'Anti-Ban Verified',
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS site_settings (
      key TEXT PRIMARY KEY,
      value TEXT NOT NULL
    );

    CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id);
    CREATE INDEX IF NOT EXISTS idx_products_featured ON products(is_featured);
    CREATE INDEX IF NOT EXISTS idx_orders_user ON orders(user_id);
    CREATE INDEX IF NOT EXISTS idx_orders_number ON orders(order_number);
    CREATE INDEX IF NOT EXISTS idx_cart_session ON cart_items(session_id);
    CREATE INDEX IF NOT EXISTS idx_cart_user ON cart_items(user_id);
  `);

  // Populate default settings if not yet set
  const defaultSettings = [
    ['upi_id', 'igakash@fam'],
    ['upi_name', 'Akash X Store'],
    ['upi_qr_url', ''],
    ['whatsapp', '+91 9135164069'],
    ['telegram', 'https://t.me/Real_Panel_100'],
    ['telegram_id', '@Real_Panel_100'],
    ['site_title', 'Akash X Store'],
    ['logo_url', '/assets/img/logo.png'],
    ['price_1day', '35'],
    ['price_3days', '45'],
    ['price_7days', '55'],
    ['price_monthly', '150'],
    ['universal_key', '7744'],
    ['announcement', '🔥 Season 43 Anti-Ban v2.8 Updated! Direct UPI Payment & Instant Key Release.']
  ];

  for (const [key, value] of defaultSettings) {
    const existing = db.prepare('SELECT key FROM site_settings WHERE key = ?').get(key);
    if (!existing) {
      db.prepare('INSERT INTO site_settings (key, value) VALUES (?, ?)').run(key, value);
    } else if (key === 'site_title' || key === 'upi_id' || key === 'logo_url' || key.startsWith('price_') || key === 'telegram' || key === 'telegram_id' || key === 'universal_key') {
      db.prepare('UPDATE site_settings SET value = ? WHERE key = ?').run(value, key);
    }
  }
}

initSchema();

// Safe wrapper functions to simplify queries and ensure BigInt serializability
function normalizeParams(params) {
  return (params || []).map(p => p === undefined ? null : p);
}

const fallbackSettings = {
  site_title: 'Akash X Store',
  upi_id: '9135164069@ybl',
  telegram: 'https://t.me/Real_Panel_100',
  telegram_id: '@Real_Panel_100',
  support_whatsapp: '+919135164069',
  currency: 'INR',
  currency_symbol: '₹',
  price_1day: '35',
  price_3days: '45',
  price_7days: '55',
  price_monthly: '150',
  universal_key: '7744',
  announcement: '🔥 Season 43 Anti-Ban v2.8 Updated! Direct UPI Payment & Instant Key Release.'
};

function query(sql, params = []) {
  if (!db) {
    if (sql.includes('site_settings')) {
      return Object.entries(fallbackSettings).map(([key, value]) => ({ key, value }));
    }
    return [];
  }
  const stmt = db.prepare(sql);
  const rows = stmt.all(...normalizeParams(params));
  return rows.map(row => {
    const clean = {};
    for (const [k, v] of Object.entries(row)) {
      clean[k] = typeof v === 'bigint' ? Number(v) : v;
    }
    return clean;
  });
}

function getOne(sql, params = []) {
  if (!db) return null;
  const stmt = db.prepare(sql);
  const row = stmt.get(...normalizeParams(params));
  if (!row) return null;
  const clean = {};
  for (const [k, v] of Object.entries(row)) {
    clean[k] = typeof v === 'bigint' ? Number(v) : v;
  }
  return clean;
}

function execute(sql, params = []) {
  if (!db) return { changes: 1, lastInsertRowid: 1 };
  const stmt = db.prepare(sql);
  const res = stmt.run(...normalizeParams(params));
  return {
    changes: res.changes,
    lastInsertRowid: Number(res.lastInsertRowid)
  };
}

module.exports = {
  db,
  query,
  getOne,
  execute,
  initSchema
};
