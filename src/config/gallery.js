const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const bundledGallery = require('../../data/gallery.json');

const filePath = path.join(__dirname, '../../data/gallery.json');
const repo = process.env.GALLERY_GITHUB_REPO || 'akashgupta9904/Akash-e';
const branch = process.env.GALLERY_GITHUB_BRANCH || 'main';
const token = process.env.GALLERY_GITHUB_TOKEN;
const remote = Boolean(process.env.VERCEL);

function validateItem(input, previous = {}) {
  const kind = String(input.kind ?? previous.kind ?? '').trim();
  if (!['proof', 'gameplay'].includes(kind)) throw new Error('Choose Proof or Gameplay.');
  const title = String(input.title ?? previous.title ?? '').trim().slice(0, 120);
  if (!title) throw new Error('Title is required.');
  const media_url = String(input.media_url ?? previous.media_url ?? '').trim();
  const thumbnail_url = String(input.thumbnail_url ?? previous.thumbnail_url ?? '').trim();
  for (const value of [media_url, thumbnail_url].filter(Boolean)) {
    if (!/^https:\/\/[^\s]+$/i.test(value)) throw new Error('Use a full HTTPS media URL.');
  }
  if (!media_url) throw new Error('Image or video URL is required.');
  return {
    id: previous.id || crypto.randomUUID(),
    kind, title, media_url, thumbnail_url,
    platform: String(input.platform ?? previous.platform ?? 'Android').trim().slice(0, 40),
    tag: String(input.tag ?? previous.tag ?? 'Verified').trim().slice(0, 60),
    description: String(input.description ?? previous.description ?? '').trim().slice(0, 500)
  };
}

async function readRemote() {
  const url = `https://api.github.com/repos/${repo}/contents/data/gallery.json?ref=${encodeURIComponent(branch)}`;
  const headers = { Accept: 'application/vnd.github+json', 'User-Agent': 'Akash-X-Store-Gallery' };
  if (token) headers.Authorization = `Bearer ${token}`;
  const response = await fetch(url, { headers, cache: 'no-store' });
  if (!response.ok) throw new Error(`Gallery read failed (GitHub ${response.status}).`);
  const payload = await response.json();
  const json = Buffer.from(payload.content.replace(/\s/g, ''), 'base64').toString('utf8');
  const data = JSON.parse(json);
  if (!Array.isArray(data.items)) throw new Error('Gallery data is invalid.');
  return { items: data.items, sha: payload.sha };
}

async function readGallery() {
  if (remote) return token ? readRemote() : { items: bundledGallery.items };
  return { items: JSON.parse(fs.readFileSync(filePath, 'utf8')).items };
}

async function mutateGallery(change) {
  if (remote && !token) {
    const err = new Error('Gallery editing needs GALLERY_GITHUB_TOKEN in Vercel environment settings.');
    err.status = 503;
    throw err;
  }
  for (let attempt = 0; attempt < 3; attempt++) {
    const current = await readGallery();
    const items = change(current.items);
    if (!Array.isArray(items)) throw new Error('Gallery update failed.');
    const content = JSON.stringify({ items }, null, 2) + '\n';
    if (!remote) {
      fs.writeFileSync(filePath, content, 'utf8');
      return items;
    }
    const response = await fetch(`https://api.github.com/repos/${repo}/contents/data/gallery.json`, {
      method: 'PUT',
      headers: {
        Accept: 'application/vnd.github+json',
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
        'User-Agent': 'Akash-X-Store-Gallery'
      },
      body: JSON.stringify({
        message: 'Update website gallery from admin',
        content: Buffer.from(content).toString('base64'),
        sha: current.sha,
        branch
      })
    });
    if (response.ok) return items;
    if (response.status !== 409) {
      const err = new Error(`Gallery save failed (GitHub ${response.status}). Check token Contents write access.`);
      err.status = 502;
      throw err;
    }
  }
  const err = new Error('Gallery changed at the same time. Please try again.');
  err.status = 409;
  throw err;
}

module.exports = { readGallery, mutateGallery, validateItem };
