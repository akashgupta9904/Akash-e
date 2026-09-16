const fs = require('fs');
const path = require('path');

const cssPath = path.join(__dirname, '../public/css/main.css');
let css = fs.readFileSync(cssPath, 'utf8');

const themeRoot = `/* ============================================
   AKASH X STORE — Multi-Theme Cyber Palette
   ============================================ */

:root {
  --bg-primary: #060d1a;
  --bg-secondary: #0a1628;
  --bg-card: rgba(15, 35, 65, 0.6);
  --bg-card-solid: #0d2040;

  --neon-green: #4a8fa8;
  --neon-green-bright: #7ec8e3;
  --neon-green-dim: #2a6080;
  --neon-orange: #5a9ab5;
  --neon-orange-bright: #8dd4ee;

  --status-bar-green: #7ec8e3;

  --text-primary: #f0f8ff;
  --text-secondary: #b8d4e8;
  --text-muted: #5a7a95;

  --border-green:  rgba(126, 200, 227, 0.28);
  --border-orange: rgba(126, 200, 227, 0.22);
  --glass-blur: 18px;

  --radius-lg: 20px;
  --radius-md: 14px;
  --radius-sm: 10px;

  --font-display: 'Poppins', sans-serif;
  --font-body: 'Poppins', 'Segoe UI', sans-serif;

  --transition-fast:   0.35s cubic-bezier(0.16, 1, 0.3, 1);
  --transition-normal: 0.7s cubic-bezier(0.16, 1, 0.3, 1);
  --transition-slow:   0.8s  cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

/* ─── Dynamic Color Themes ─────────────────────── */
[data-theme="lime"] {
  --bg-primary: #05080f;
  --bg-secondary: #0a121d;
  --bg-card: rgba(12, 28, 16, 0.75);
  --bg-card-solid: #0d2212;
  --neon-green: #7ec400;
  --neon-green-bright: #9ae600;
  --neon-green-dim: #4d7a00;
  --neon-orange: #ffaa00;
  --neon-orange-bright: #ffcc00;
  --status-bar-green: #9ae600;
  --text-primary: #ffffff;
  --text-secondary: #d0f0ba;
  --border-green: rgba(154, 230, 0, 0.35);
  --border-orange: rgba(255, 170, 0, 0.3);
}

[data-theme="red"] {
  --bg-primary: #0e0505;
  --bg-secondary: #190909;
  --bg-card: rgba(35, 14, 14, 0.75);
  --bg-card-solid: #260d0d;
  --neon-green: #e03030;
  --neon-green-bright: #ff4757;
  --neon-green-dim: #8b1818;
  --neon-orange: #ff6b6b;
  --neon-orange-bright: #ffa502;
  --status-bar-green: #ff4757;
  --text-primary: #ffffff;
  --text-secondary: #ffd2d6;
  --border-green: rgba(255, 71, 87, 0.38);
  --border-orange: rgba(255, 107, 107, 0.3);
}

[data-theme="purple"] {
  --bg-primary: #0a0614;
  --bg-secondary: #130b24;
  --bg-card: rgba(28, 16, 52, 0.75);
  --bg-card-solid: #21133c;
  --neon-green: #9b51e0;
  --neon-green-bright: #b06ee0;
  --neon-green-dim: #60259b;
  --neon-orange: #c77dff;
  --neon-orange-bright: #e0aaff;
  --status-bar-green: #b06ee0;
  --text-primary: #ffffff;
  --text-secondary: #e2d2f8;
  --border-green: rgba(176, 110, 224, 0.38);
  --border-orange: rgba(199, 125, 255, 0.3);
}

[data-theme="royal"] {
  --bg-primary: #120414;
  --bg-secondary: #1e0822;
  --bg-card: rgba(48, 16, 54, 0.75);
  --bg-card-solid: #36123c;
  --neon-green: #cf4f88;
  --neon-green-bright: #f472b6;
  --neon-green-dim: #882255;
  --neon-orange: #fb7185;
  --neon-orange-bright: #fda4af;
  --status-bar-green: #f472b6;
  --text-primary: #fff1f2;
  --text-secondary: #fcd5ea;
  --border-green: rgba(244, 114, 182, 0.38);
  --border-orange: rgba(251, 113, 133, 0.3);
}

[data-theme="mocha"] {
  --bg-primary: #130a06;
  --bg-secondary: #1f120b;
  --bg-card: rgba(42, 26, 18, 0.75);
  --bg-card-solid: #321f16;
  --neon-green: #c08552;
  --neon-green-bright: #e6a15c;
  --neon-green-dim: #7a4f28;
  --neon-orange: #d4a373;
  --neon-orange-bright: #faedcd;
  --status-bar-green: #e6a15c;
  --text-primary: #fefae0;
  --text-secondary: #ecddb0;
  --border-green: rgba(230, 161, 92, 0.38);
  --border-orange: rgba(212, 163, 115, 0.3);
}

[data-theme="pastel"] {
  --bg-primary: #081216;
  --bg-secondary: #0f2026;
  --bg-card: rgba(22, 44, 50, 0.75);
  --bg-card-solid: #18343b;
  --neon-green: #5ea8a7;
  --neon-green-bright: #84dcc6;
  --neon-green-dim: #326e6d;
  --neon-orange: #ffa69e;
  --neon-orange-bright: #ff686b;
  --status-bar-green: #84dcc6;
  --text-primary: #f7fff7;
  --text-secondary: #c9ede0;
  --border-green: rgba(132, 220, 198, 0.38);
  --border-orange: rgba(255, 166, 158, 0.3);
}

* { 
  box-sizing: border-box; 
  -webkit-tap-highlight-color: transparent;
}

html, body {
  overflow-x: hidden;
}

body {
  margin: 0;
  padding: 0;
  padding-top: 65px;
  background: var(--bg-primary);
  color: var(--text-primary);
  font-family: var(--font-body);
  scroll-behavior: smooth;
  position: relative;
  min-height: 100vh;
  transition: background 0.35s ease, color 0.35s ease;
}`;

const themePickerStyles = `
/* ─── Color Theme Picker Popup Modal / Dropdown ── */
.theme-picker {
  position: fixed;
  top: 75px;
  right: 18px;
  width: 300px;
  max-width: calc(100vw - 36px);
  background: rgba(10, 19, 36, 0.98);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid var(--border-green);
  border-radius: var(--radius-lg);
  padding: 16px;
  z-index: 10005;
  box-shadow: 0 16px 48px rgba(0, 0, 0, 0.8), 0 0 28px rgba(126, 200, 227, 0.25);
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transform: translateY(-12px) scale(0.95);
  transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
}
.theme-picker.open {
  opacity: 1 !important;
  visibility: visible !important;
  pointer-events: auto !important;
  transform: translateY(0) scale(1) !important;
}
.theme-picker-title {
  font-family: var(--font-display);
  font-size: 0.88rem;
  font-weight: 800;
  letter-spacing: 1px;
  color: var(--neon-green-bright);
  text-transform: uppercase;
  margin-bottom: 12px;
  padding-bottom: 8px;
  border-bottom: 1px solid rgba(126, 200, 227, 0.18);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.theme-option {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 11px 14px;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(126, 200, 227, 0.12);
  color: var(--text-primary);
  font-size: 0.92rem;
  font-weight: 600;
  cursor: pointer;
  margin-bottom: 8px;
  transition: all 0.2s ease;
}
.theme-option:hover {
  background: rgba(126, 200, 227, 0.15);
  border-color: var(--border-green);
  transform: translateX(4px);
}
.theme-option.active {
  background: rgba(126, 200, 227, 0.22);
  border-color: var(--neon-green-bright);
  color: var(--neon-green-bright);
  box-shadow: 0 0 12px rgba(126, 200, 227, 0.2);
}
.theme-swatches {
  display: flex;
  gap: 5px;
  align-items: center;
}
.theme-swatches span {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  display: inline-block;
  border: 1.5px solid rgba(255, 255, 255, 0.35);
  box-shadow: 0 1px 4px rgba(0,0,0,0.5);
}
`;

const splitIdx = css.indexOf('@keyframes pageFadeIn');
if (splitIdx !== -1) {
  let rest = css.slice(splitIdx);
  // Also append themePickerStyles if not present
  if (!rest.includes('.theme-picker {')) {
    rest += '\n' + themePickerStyles;
  }
  const finalCss = themeRoot + '\n' + rest;
  fs.writeFileSync(cssPath, finalCss, 'utf8');
  console.log('SUCCESS: public/css/main.css updated with themes and theme-picker styles!');
} else {
  console.error('ERROR: @keyframes pageFadeIn not found');
}
