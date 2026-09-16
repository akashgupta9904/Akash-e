const bcrypt = require('bcryptjs');
const { query, getOne, execute } = require('./db');

function seedDatabase() {
  console.log('🔄 Checking database seed data...');

  // 1. Seed Users (Admin & Customer)
  const akash4141 = getOne('SELECT id FROM users WHERE email = ?', ['akash@4141']);
  const hash4141 = bcrypt.hashSync('4141', 10);
  if (!akash4141) {
    execute(
      `INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, ?, ?)`,
      ['Akash Owner', 'akash@4141', hash4141, 'admin', '+91 9135164069']
    );
    console.log('✅ Admin user created: akash@4141 / 4141');
  } else {
    execute('UPDATE users SET password_hash = ?, role = ? WHERE email = ?', [hash4141, 'admin', 'akash@4141']);
    console.log('✅ Admin user updated: akash@4141 / 4141');
  }

  const akashAdmin = getOne('SELECT id FROM users WHERE email = ?', ['akashkumagupta163@gmail.com']);
  if (!akashAdmin) {
    const akashPassHash = bcrypt.hashSync('akash1245', 10);
    execute(
      `INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, ?, ?)`,
      ['Akash Gupta', 'akashkumagupta163@gmail.com', akashPassHash, 'admin', '+91 9135164069']
    );
  }

  const existingAdmin = getOne('SELECT id FROM users WHERE email = ?', ['admin@store.com']);
  if (!existingAdmin) {
    const adminPasswordHash = bcrypt.hashSync('Admin@12345', 10);
    execute(
      `INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, ?, ?)`,
      ['Store Admin', 'admin@store.com', adminPasswordHash, 'admin', '+91 9876543210']
    );
  }

  const existingCustomer = getOne('SELECT id FROM users WHERE email = ?', ['customer@store.com']);
  let customerId;
  if (!existingCustomer) {
    const customerPasswordHash = bcrypt.hashSync('Customer@12345', 10);
    const res = execute(
      `INSERT INTO users (name, email, password_hash, role, phone) VALUES (?, ?, ?, ?, ?)`,
      ['Rahul Sharma', 'customer@store.com', customerPasswordHash, 'customer', '+91 9812345678']
    );
    customerId = res.lastInsertRowid;
    console.log('✅ Demo customer created: customer@store.com / Customer@12345');

    // Default address for customer
    execute(
      `INSERT INTO addresses (user_id, full_name, phone, street, city, state, postal_code, country, is_default)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)`,
      [customerId, 'Rahul Sharma', '+91 9812345678', 'Flat 402, Lotus Heights, Park Street', 'Mumbai', 'Maharashtra', '400001', 'India']
    );
  } else {
    customerId = existingCustomer.id;
  }

  // 2. Seed Categories
  const categoriesData = [
    {
      name: 'Electronics & Gadgets',
      slug: 'electronics',
      description: 'Cutting-edge smartphones, smartwatches, and innovative electronics.',
      image_url: 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800&auto=format&fit=crop&q=80'
    },
    {
      name: 'Audio & Acoustics',
      slug: 'audio',
      description: 'High-fidelity noise-canceling headphones, studio monitors, and wireless earbuds.',
      image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'
    },
    {
      name: 'Modern Fashion & Apparel',
      slug: 'fashion',
      description: 'Contemporary streetwear, minimalist jackets, luxury eyewear, and urban styles.',
      image_url: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&auto=format&fit=crop&q=80'
    },
    {
      name: 'Home & Workspace',
      slug: 'home-workspace',
      description: 'Ergonomic office essentials, smart ambient lighting, and modern decor.',
      image_url: 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=800&auto=format&fit=crop&q=80'
    }
  ];

  const categoryMap = {};
  for (const cat of categoriesData) {
    let row = getOne('SELECT id FROM categories WHERE slug = ?', [cat.slug]);
    if (!row) {
      const res = execute(
        `INSERT INTO categories (name, slug, description, image_url) VALUES (?, ?, ?, ?)`,
        [cat.name, cat.slug, cat.description, cat.image_url]
      );
      categoryMap[cat.slug] = res.lastInsertRowid;
    } else {
      categoryMap[cat.slug] = row.id;
    }
  }

  // 3. Seed Products
  const productsCount = getOne('SELECT COUNT(*) as count FROM products');
  if (productsCount.count === 0) {
    const productsData = [
      {
        name: 'Aura Studio Wireless ANC Headphones',
        slug: 'aura-studio-wireless-anc-headphones',
        category_id: categoryMap['audio'],
        description: 'Engineered for audiophiles. Features 45mm custom graphene dynamic drivers, active hybrid noise cancellation (up to -40dB), spatial audio tracking, and up to 55 hours of battery life with ultra-plush memory foam earcups.',
        price: 14999,
        discount_percent: 15,
        stock: 25,
        is_featured: 1,
        rating: 4.9,
        image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([
          'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&auto=format&fit=crop&q=80',
          'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80'
        ])
      },
      {
        name: 'Apex Pro OLED Smartwatch Ultra',
        slug: 'apex-pro-oled-smartwatch-ultra',
        category_id: categoryMap['electronics'],
        description: 'Titanium aerospace-grade case with sapphire crystal 1.95" Always-On AMOLED display. Dual-frequency GPS, 100m water resistance, ECG heart tracking, and 7-day battery life for extreme outdoor adventure.',
        price: 24999,
        discount_percent: 20,
        stock: 18,
        is_featured: 1,
        rating: 4.8,
        image_url: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([
          'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80'
        ])
      },
      {
        name: 'Nomad Minimalist Waterproof Backpack',
        slug: 'nomad-minimalist-waterproof-backpack',
        category_id: categoryMap['fashion'],
        description: 'Crafted from 100% recycled Cordura fabric with YKK AquaGuard zippers. Includes a padded 16" laptop sleeve, magnetic Fidlock quick-access buckle, luggage pass-through strap, and secret anti-theft pocket.',
        price: 4999,
        discount_percent: 10,
        stock: 40,
        is_featured: 1,
        rating: 4.7,
        image_url: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([
          'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=800&auto=format&fit=crop&q=80'
        ])
      },
      {
        name: 'CyberMechanical Backlit Keyboard RGB',
        slug: 'cybermechanical-backlit-keyboard-rgb',
        category_id: categoryMap['electronics'],
        description: 'Custom hot-swappable tactile switches, CNC aluminum chassis, sound-dampening silicone gaskets, and per-key RGB lighting. Connects wirelessly via 2.4GHz dongle, Bluetooth 5.2, or USB-C.',
        price: 8499,
        discount_percent: 12,
        stock: 12,
        is_featured: 1,
        rating: 4.9,
        image_url: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([
          'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80'
        ])
      },
      {
        name: 'Vanguard Polarized Titanium Sunglasses',
        slug: 'vanguard-polarized-titanium-sunglasses',
        category_id: categoryMap['fashion'],
        description: 'Featherlight Japanese titanium frame weighing just 14 grams. Category 3 polarized UV400 anti-reflective lenses provide crystal clear optic fidelity in bright sunlight with timeless geometric styling.',
        price: 3799,
        discount_percent: 0,
        stock: 30,
        is_featured: 0,
        rating: 4.6,
        image_url: 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([])
      },
      {
        name: 'Lumina Smart Ambient Desk Lamp',
        slug: 'lumina-smart-ambient-desk-lamp',
        category_id: categoryMap['home-workspace'],
        description: 'Architectural desk lamp with dual-axis articulation, auto-dimming circadian rhythm sync, 98 CRI eye-care illumination, and integrated 15W Qi fast wireless charging base for smartphones.',
        price: 5499,
        discount_percent: 18,
        stock: 22,
        is_featured: 1,
        rating: 4.8,
        image_url: 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([])
      },
      {
        name: 'SonicBlast 360 Portable Waterproof Speaker',
        slug: 'sonicblast-360-portable-waterproof-speaker',
        category_id: categoryMap['audio'],
        description: 'IP67 dust and waterproof bluetooth speaker with 360-degree omnidirectional drivers and dual passive bass radiators. Up to 24 hours playtime, party-link pairing, and buoyant floating design.',
        price: 6999,
        discount_percent: 25,
        stock: 35,
        is_featured: 0,
        rating: 4.7,
        image_url: 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([])
      },
      {
        name: 'ErgoPro Precision Wireless Trackball Mouse',
        slug: 'ergopro-precision-wireless-trackball-mouse',
        category_id: categoryMap['home-workspace'],
        description: 'Engineered by ergonomic specialists to reduce wrist strain by 40%. Features high-accuracy optical tracking, smooth thumb ball control, adjustable 20-degree tilt angle, and dual device switching.',
        price: 4299,
        discount_percent: 5,
        stock: 15,
        is_featured: 0,
        rating: 4.5,
        image_url: 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
        gallery_json: JSON.stringify([])
      }
    ];

    for (const prod of productsData) {
      execute(
        `INSERT INTO products (name, slug, category_id, description, price, discount_percent, stock, is_featured, rating, image_url, gallery_json)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        [
          prod.name,
          prod.slug,
          prod.category_id,
          prod.description,
          prod.price,
          prod.discount_percent,
          prod.stock,
          prod.is_featured,
          prod.rating,
          prod.image_url,
          prod.gallery_json
        ]
      );
    }
    console.log(`✅ Seeded ${productsData.length} premium sample products.`);
  }

  // 4. Seed Coupons
  const existingCoupon = getOne('SELECT id FROM coupons WHERE code = ?', ['WELCOME10']);
  if (!existingCoupon) {
    execute(
      `INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_discount, usage_limit, is_active)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['WELCOME10', 'percentage', 10, 1000, 1500, 500, 1]
    );
    execute(
      `INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_discount, usage_limit, is_active)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      ['FLAT500', 'fixed', 500, 3000, 500, 200, 1]
    );
    console.log('✅ Active coupons seeded: WELCOME10 (10% off), FLAT500 (₹500 off)');
  }

  console.log('✨ Seed check completed.');
}

module.exports = { seedDatabase };
