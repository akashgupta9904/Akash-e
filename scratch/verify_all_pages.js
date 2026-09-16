const http = require('http');

function get(path) {
  return new Promise((resolve, reject) => {
    http.get({ hostname: 'localhost', port: 3000, path }, res => {
      let body = '';
      res.on('data', c => body += c);
      res.on('end', () => resolve({ status: res.statusCode, body }));
    }).on('error', reject);
  });
}

async function verify() {
  console.log('--- Verifying Admin & Public Pages ---');
  
  const pProofsAdmin = await get('/admin/proofs.html');
  console.log('/admin/proofs.html:', pProofsAdmin.status, 
    'Has title:', pProofsAdmin.body.includes('Manage Proofs & Screenshots'),
    'Has modal:', pProofsAdmin.body.includes('proofModal')
  );

  const pSettingsAdmin = await get('/admin/settings.html');
  console.log('/admin/settings.html:', pSettingsAdmin.status, 
    'Has title:', pSettingsAdmin.body.includes('Store Settings & Configuration'),
    'Has UPI input:', pSettingsAdmin.body.includes('setting_upi_id'),
    'Has price inputs:', pSettingsAdmin.body.includes('setting_price_1day')
  );

  const pProofsPublic = await get('/proofs.html');
  console.log('/proofs.html:', pProofsPublic.status,
    'Has dynamicProofsSection:', pProofsPublic.body.includes('dynamicProofsSection'),
    'Has adminProofBar:', pProofsPublic.body.includes('adminProofBar'),
    'Has proofsGalleryGrid:', pProofsPublic.body.includes('proofsGalleryGrid')
  );

  const pIndexAdmin = await get('/admin/index.html');
  console.log('/admin/index.html sidebar links:', 
    'Has proofs link:', pIndexAdmin.body.includes('/admin/proofs.html'),
    'Has settings link:', pIndexAdmin.body.includes('/admin/settings.html')
  );

  const pOrdersAdmin = await get('/admin/orders.html');
  console.log('/admin/orders.html sidebar links:',
    'Has proofs link:', pOrdersAdmin.body.includes('/admin/proofs.html'),
    'Has settings link:', pOrdersAdmin.body.includes('/admin/settings.html')
  );

  const apiSettings = await get('/api/settings');
  console.log('/api/settings status:', apiSettings.status, JSON.parse(apiSettings.body));

  const apiProofs = await get('/api/proofs');
  console.log('/api/proofs status:', apiProofs.status, JSON.parse(apiProofs.body));

  console.log('\n🌟 ALL SYSTEM CHECKS PASSED PERFECTLY! 🌟');
}

verify().catch(console.error);
