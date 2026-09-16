const app = require('../server');

module.exports = (req, res) => {
  // Normalize req.url if Vercel passes internal rewrite or function path
  const matchedPath = req.headers['x-matched-path'] || req.headers['x-now-route-matches'];
  if (matchedPath && !matchedPath.includes('api/index.js')) {
    const queryIdx = req.url.indexOf('?');
    const queryString = queryIdx !== -1 ? req.url.slice(queryIdx) : '';
    req.url = matchedPath + queryString;
  } else if (req.url.startsWith('/public/api/index.js')) {
    req.url = req.url.replace('/public/api/index.js', '') || '/';
  } else if (req.url.startsWith('/api/index.js')) {
    req.url = req.url.replace('/api/index.js', '') || '/';
  }

  return app(req, res);
};
