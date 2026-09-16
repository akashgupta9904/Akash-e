const http = require('http');

function check(url, searchStr) {
  return new Promise((resolve, reject) => {
    http.get(url, res => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        const found = data.includes(searchStr);
        console.log((found ? '✅ PASS' : '❌ FAIL') + ' ' + url + ' contains: ' + searchStr);
        resolve(found);
      });
    }).on('error', reject);
  });
}

(async () => {
  console.log('--- Checking Akash Personal Store Integration ---');
  await check('http://localhost:3000/', 'igakash@fam');
  await check('http://localhost:3000/', '9135164069');
  await check('http://localhost:3000/android.html', 'igakash@fam');
  await check('http://localhost:3000/android.html', '9135164069');
  await check('http://localhost:3000/ios.html', 'igakash@fam');
  await check('http://localhost:3000/ios.html', '9135164069');
  await check('http://localhost:3000/proofs.html', '9135164069');
  await check('http://localhost:3000/gameplay.html', '9135164069');
  await check('http://localhost:3000/js/store.js', 'igakash@fam');
  await check('http://localhost:3000/js/store.js', '9135164069');
  console.log('--- Complete ---');
})();
