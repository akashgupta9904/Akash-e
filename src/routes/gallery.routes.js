const express = require('express');
const crypto = require('crypto');
const { requireAdmin } = require('../middleware/auth');
const { readGallery, mutateGallery, validateItem } = require('../config/gallery');

const publicRouter = express.Router();
const adminRouter = express.Router();
adminRouter.use(requireAdmin);

function sendError(res, err) {
  res.status(err.status || 400).json({ success: false, message: err.message });
}

function filterItems(items, kind) {
  return kind === 'proof' || kind === 'gameplay' ? items.filter(item => item.kind === kind) : items;
}

async function list(req, res) {
  try {
    res.set('Cache-Control', 'no-store');
    const { items } = await readGallery();
    res.json({ success: true, items: filterItems(items, req.query.kind) });
  } catch (err) {
    console.error('Gallery read:', err);
    res.status(502).json({ success: false, message: 'Gallery is temporarily unavailable.' });
  }
}

function requireEditKey(req, res, next) {
  if (!process.env.VERCEL) return next();
  const secret = process.env.GALLERY_EDIT_SECRET;
  if (!secret || !process.env.GALLERY_GITHUB_TOKEN) {
    return res.status(503).json({ success: false, message: 'Gallery editing needs GALLERY_GITHUB_TOKEN and GALLERY_EDIT_SECRET in Vercel settings.' });
  }
  const given = req.get('x-gallery-edit-key') || '';
  const expectedHash = crypto.createHash('sha256').update(secret).digest();
  const givenHash = crypto.createHash('sha256').update(given).digest();
  if (!crypto.timingSafeEqual(expectedHash, givenHash)) {
    return res.status(403).json({ success: false, message: 'Gallery edit key is incorrect.' });
  }
  next();
}

publicRouter.get('/', list);
adminRouter.get('/', list);
adminRouter.use(requireEditKey);

adminRouter.post('/', async (req, res) => {
  try {
    const item = validateItem(req.body || {});
    await mutateGallery(items => [...items, item]);
    res.status(201).json({ success: true, item });
  } catch (err) { sendError(res, err); }
});

adminRouter.put('/:id', async (req, res) => {
  try {
    let updated;
    await mutateGallery(items => {
      const index = items.findIndex(item => item.id === req.params.id);
      if (index === -1) { const err = new Error('Gallery item not found.'); err.status = 404; throw err; }
      updated = validateItem(req.body || {}, items[index]);
      const result = [...items];
      result[index] = updated;
      return result;
    });
    res.json({ success: true, item: updated });
  } catch (err) { sendError(res, err); }
});

adminRouter.delete('/:id', async (req, res) => {
  try {
    await mutateGallery(items => {
      if (!items.some(item => item.id === req.params.id)) { const err = new Error('Gallery item not found.'); err.status = 404; throw err; }
      return items.filter(item => item.id !== req.params.id);
    });
    res.json({ success: true });
  } catch (err) { sendError(res, err); }
});

module.exports = { publicRouter, adminRouter };
