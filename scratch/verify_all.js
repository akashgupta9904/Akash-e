const http = require('http');

function request(url, options = {}) {
  return new Promise((resolve, reject) => {
    const parsed = new URL(url);
    const req = http.request({
      hostname: parsed.hostname,
      port: parsed.port,
      path: parsed.pathname + parsed.search,
      method: options.method || 'GET',
      headers: options.headers || {}
    }, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        resolve({ status: res.statusCode, headers: res.headers, body: data });
      });
    });
    req.on('error', reject);
    if (options.body) req.write(options.body);
    req.end();
  });
}

async function run() {
  console.log('--- STARTING AKASH X STORE AUDIT ---');
  let errors = 0;

  // 1. Settings check
  try {
    const res = await request('http://localhost:3000/api/settings');
    const json = JSON.parse(res.body);
    console.log('1. /api/settings response:', json.success ? 'OK' : 'FAIL');
    if (!json.success || json.settings.prices['1'] !== 35 || json.settings.upi_id !== 'igakash@fam') {
      console.error('❌ Settings mismatch! Got:', json.settings);
      errors++;
    } else {
      console.log('✅ Settings match: 1 Day = ₹35, UPI = igakash@fam, Title =', json.settings.site_title);
    }
  } catch (e) {
    console.error('❌ Settings test failed:', e.message);
    errors++;
  }

  // 2. Auth Login check
  try {
    const res = await request('http://localhost:3000/api/auth/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: 'akash@4141', password: '4141' })
    });
    const json = JSON.parse(res.body);
    if (json.token && json.user && json.user.role === 'admin') {
      console.log('✅ Login successful for akash@4141 with password 4141! Role:', json.user.role);
    } else {
      console.error('❌ Login failed with response:', json);
      errors++;
    }
  } catch (e) {
    console.error('❌ Auth test failed:', e.message);
    errors++;
  }

  // 3. Homepage check
  try {
    const res = await request('http://localhost:3000/');
    const body = res.body;
    const hasName = body.includes('Akash X Store');
    const hasRedLogo = body.includes('/assets/img/logo.png');
    const hasFloatCards = body.includes('cards-grid') && body.includes('feature-card');
    const hasThemeBtn = body.includes('themeToggleBtn');
    const hasThemePicker = body.includes('id="themePicker"');

    console.log('3. Homepage checks:');
    console.log('   - Has Akash X Store:', hasName ? '✅' : '❌');
    console.log('   - Has Red Logo (/assets/img/logo.png):', hasRedLogo ? '✅' : '❌');
    console.log('   - Has 4 Floating Cards:', hasFloatCards ? '✅' : '❌');
    console.log('   - Has Theme Toggle Sun Wheel:', hasThemeBtn ? '✅' : '❌');
    console.log('   - Has Theme Picker Popup:', hasThemePicker ? '✅' : '❌');

    if (!hasName || !hasRedLogo || !hasFloatCards || !hasThemeBtn || !hasThemePicker) errors++;
  } catch (e) {
    console.error('❌ Homepage check failed:', e.message);
    errors++;
  }

  // 4. Android page check
  try {
    const res = await request('http://localhost:3000/android');
    const body = res.body;
    const has35 = body.includes('35');
    const has55 = body.includes('55');
    const hasUpi = body.includes('igakash@fam');
    console.log('4. Android Page checks:');
    console.log('   - Pricing 35 & 55 present:', (has35 && has55) ? '✅' : '❌');
    console.log('   - UPI igakash@fam present:', hasUpi ? '✅' : '❌');
    if (!has35 || !has55 || !hasUpi) errors++;
  } catch (e) {
    console.error('❌ Android page check failed:', e.message);
    errors++;
  }

  // 5. CSS Themes check
  try {
    const res = await request('http://localhost:3000/css/main.css');
    const css = res.body;
    const hasLime = css.includes('[data-theme="lime"]');
    const hasRed = css.includes('[data-theme="red"]');
    const hasThemePickerCSS = css.includes('.theme-picker.open');
    const hasFloatUp = css.includes('@keyframes cardFloatUp');
    const hasFloatDown = css.includes('@keyframes cardFloatDown');

    console.log('5. CSS main.css checks:');
    console.log('   - Has [data-theme="lime"]:', hasLime ? '✅' : '❌');
    console.log('   - Has [data-theme="red"]:', hasRed ? '✅' : '❌');
    console.log('   - Has .theme-picker.open rule:', hasThemePickerCSS ? '✅' : '❌');
    console.log('   - Has cardFloatUp animation:', hasFloatUp ? '✅' : '❌');
    console.log('   - Has cardFloatDown animation:', hasFloatDown ? '✅' : '❌');

    if (!hasLime || !hasRed || !hasThemePickerCSS || !hasFloatUp || !hasFloatDown) errors++;
  } catch (e) {
    console.error('❌ CSS check failed:', e.message);
    errors++;
  }

  console.log('------------------------------------');
  if (errors === 0) {
    console.log('🎉 ALL AUDIT CHECKS PASSED PERFECTLY!');
  } else {
    console.log(`⚠️ Completed with ${errors} issues.`);
  }
}

run();
