const express = require('express');
const router = express.Router();
const { query } = require('../config/db');

// robots.txt
router.get('/robots.txt', (req, res) => {
  const host = req.get('host') || 'localhost:3000';
  const protocol = req.protocol;
  const content = `User-agent: *
Disallow: /admin/
Disallow: /api/admin/
Disallow: /checkout.html
Disallow: /orders.html

Sitemap: ${protocol}://${host}/sitemap.xml
`;
  res.type('text/plain').send(content);
});

// sitemap.xml
router.get('/sitemap.xml', (req, res) => {
  try {
    const host = req.get('host') || 'localhost:3000';
    const protocol = req.protocol;
    const baseUrl = `${protocol}://${host}`;

    const staticPages = [
      { loc: `${baseUrl}/`, changefreq: 'daily', priority: '1.0' },
      { loc: `${baseUrl}/shop.html`, changefreq: 'daily', priority: '0.9' },
      { loc: `${baseUrl}/cart.html`, changefreq: 'monthly', priority: '0.4' },
      { loc: `${baseUrl}/login.html`, changefreq: 'monthly', priority: '0.3' }
    ];

    const products = query('SELECT slug, created_at FROM products ORDER BY id DESC LIMIT 500');
    const categories = query('SELECT slug, created_at FROM categories ORDER BY id DESC');

    let xml = `<?xml version="1.0" encoding="UTF-8"?>\n`;
    xml += `<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n`;

    for (const page of staticPages) {
      xml += `  <url>\n    <loc>${page.loc}</loc>\n    <changefreq>${page.changefreq}</changefreq>\n    <priority>${page.priority}</priority>\n  </url>\n`;
    }

    for (const cat of categories) {
      xml += `  <url>\n    <loc>${baseUrl}/shop.html?category=${cat.slug}</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n`;
    }

    for (const prod of products) {
      xml += `  <url>\n    <loc>${baseUrl}/product.html?slug=${prod.slug}</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n`;
    }

    xml += `</urlset>`;
    res.type('application/xml').send(xml);
  } catch (err) {
    res.status(500).send('Error generating sitemap');
  }
});

module.exports = router;
