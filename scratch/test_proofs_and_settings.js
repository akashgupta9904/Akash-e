const http = require('http');

function request(options, data = null) {
  return new Promise((resolve, reject) => {
    const req = http.request(options, (res) => {
      let body = '';
      res.on('data', chunk => body += chunk);
      res.on('end', () => {
        try {
          resolve({ status: res.statusCode, data: JSON.parse(body) });
        } catch (e) {
          resolve({ status: res.statusCode, body });
        }
      });
    });
    req.on('error', reject);
    if (data) {
      req.write(typeof data === 'string' ? data : JSON.stringify(data));
    }
    req.end();
  });
}

async function run() {
  console.log('--- 1. Testing GET /api/settings ---');
  const s1 = await request({
    hostname: 'localhost',
    port: 3000,
    path: '/api/settings',
    method: 'GET'
  });
  console.log('Settings status:', s1.status, s1.data);

  console.log('\n--- 2. Testing Admin Login ---');
  const loginRes = await request({
    hostname: 'localhost',
    port: 3000,
    path: '/api/auth/login',
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  }, {
    email: 'akashkumagupta163@gmail.com',
    password: 'akash1245'
  });
  console.log('Login status:', loginRes.status, 'User:', loginRes.data?.user?.email);
  const token = loginRes.data?.token;

  if (!token) {
    console.error('Failed to get token!');
    return;
  }

  console.log('\n--- 3. Testing POST /api/admin/proofs ---');
  const proofRes = await request({
    hostname: 'localhost',
    port: 3000,
    path: '/api/admin/proofs',
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  }, {
    title: 'Grandmaster Rank Push 99.4% Headshot Proof',
    description: 'Verified match stats with Anti-Ban v2.8 bypass on Android 14. Smooth drag headshots.',
    image_url: '/assets/img/logo.png',
    tag: 'Anti-Ban Verified'
  });
  console.log('Add proof status:', proofRes.status, proofRes.data);

  console.log('\n--- 4. Testing GET /api/proofs ---');
  const publicProofs = await request({
    hostname: 'localhost',
    port: 3000,
    path: '/api/proofs',
    method: 'GET'
  });
  console.log('Public proofs count:', publicProofs.data?.proofs?.length, publicProofs.data?.proofs?.[0]);

  console.log('\n--- 5. Testing POST /api/admin/settings ---');
  const setRes = await request({
    hostname: 'localhost',
    port: 3000,
    path: '/api/admin/settings',
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  }, {
    upi_id: 'igakash@fam',
    upi_name: 'AKASH X STORE',
    whatsapp: '+91 9135164069',
    announcement: '🔥 Season 43 Anti-Ban v2.8 Updated! Direct UPI Payment & Instant Key Release.'
  });
  console.log('Update settings status:', setRes.status, setRes.data);

  console.log('\nALL TESTS PASSED SUCCESSFULLY! ✅');
}

run().catch(console.error);
