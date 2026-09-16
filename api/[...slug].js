const app = require('../server');

module.exports = (req, res) => {
  // If req.url was stripped or rewritten, ensure it retains path
  const matchedPath = req.headers['x-matched-path'] || req.headers['x-now-route-matches'];
  if (matchedPath && !matchedPath.includes('[...slug]')) {
    const queryIdx = req.url.indexOf('?');
    const queryString = queryIdx !== -1 ? req.url.slice(queryIdx) : '';
    req.url = matchedPath + queryString;
  }
  return app(req, res);
};
