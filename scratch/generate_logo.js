const fs = require('fs');
const path = require('path');

const imgPath = path.join(__dirname, '..', 'public', 'assets', 'img', 'logo.png');
const b64 = fs.readFileSync(imgPath).toString('base64');

const svg = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 500" width="100%" height="100%">
  <defs>
    <clipPath id="avatarCircle">
      <circle cx="250" cy="250" r="232"/>
    </clipPath>
    <filter id="neonGlow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="6" result="blur" />
      <feComposite in="SourceGraphic" in2="blur" operator="over"/>
    </filter>
  </defs>
  <!-- Outer Glow Ring -->
  <circle cx="250" cy="250" r="240" fill="#060d1a" stroke="#7ec8e3" stroke-width="10" filter="url(#neonGlow)"/>
  <!-- User Uploaded Character Avatar -->
  <image href="data:image/jpeg;base64,${b64}" x="18" y="18" width="464" height="464" preserveAspectRatio="xMidYMid slice" clip-path="url(#avatarCircle)"/>
  <!-- Inner Tech Ring -->
  <circle cx="250" cy="250" r="232" fill="none" stroke="rgba(126,200,227,0.5)" stroke-width="4"/>
</svg>`;

const logoSvgPath = path.join(__dirname, '..', 'public', 'assets', 'img', 'logo.svg');
const faviconSvgPath = path.join(__dirname, '..', 'public', 'favicon.svg');

fs.writeFileSync(logoSvgPath, svg, 'utf8');
fs.writeFileSync(faviconSvgPath, svg, 'utf8');
console.log('✅ Generated logo.svg and favicon.svg with new avatar successfully!');
