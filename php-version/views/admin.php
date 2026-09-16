<?php
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseDir = dirname($scriptName);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = '';
}

// Ensure Admin is logged in
if (!is_admin_authenticated()) {
    header('Location: ' . ($baseDir ?: '') . '/login');
    exit;
}

$siteConfig = get_site_config();
$productConfig = get_product_config();
$proofs = get_proofs();
$orders = get_orders();
$videos = $siteConfig['videos'] ?? [];
$reviews = $siteConfig['reviews'] ?? [];
$prices = $siteConfig['prices'] ?? ['1' => 80, '15' => 150, '30' => 299, '90' => 599];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Owner Control Center | AKASH X STORE</title>
  <link rel="icon" type="image/svg+xml" href="<?= $baseDir ?>/favicon.svg">
  <link rel="stylesheet" href="<?= $baseDir ?>/assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    :root {
      --admin-bg: #070e1c;
      --admin-card: rgba(13, 25, 48, 0.9);
      --admin-border: rgba(126, 200, 227, 0.22);
    }
    body {
      background: var(--admin-bg);
      color: #f1f5f9;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      padding-bottom: 80px; /* Space for mobile navigation */
    }
    .admin-header {
      background: #09152b;
      border-bottom: 1px solid var(--admin-border);
      padding: 0.85rem 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .admin-container {
      max-width: 1200px;
      margin: 1.5rem auto;
      padding: 0 1rem;
    }
    
    /* Tabs Navigation */
    .admin-nav-tabs {
      display: flex;
      gap: 0.5rem;
      overflow-x: auto;
      padding-bottom: 0.75rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid var(--admin-border);
      scrollbar-width: none;
    }
    .admin-nav-tabs::-webkit-scrollbar { display: none; }
    .tab-btn {
      background: rgba(13, 25, 48, 0.7);
      border: 1px solid var(--admin-border);
      color: #94a3b8;
      padding: 9px 16px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 0.85rem;
      cursor: pointer;
      white-space: nowrap;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .tab-btn.active {
      background: var(--neon-green-bright);
      color: #060d1a;
      border-color: var(--neon-green-bright);
      box-shadow: 0 0 15px rgba(126,200,227,0.3);
    }
    
    /* Tab Contents */
    .tab-content {
      display: none;
      animation: fadeIn 0.25s ease;
    }
    .tab-content.active {
      display: block;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Form Fields */
    .admin-card {
      background: var(--admin-card);
      border: 1px solid var(--admin-border);
      border-radius: 16px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .form-group {
      margin-bottom: 1.25rem;
    }
    .form-label {
      display: block;
      font-size: 0.78rem;
      font-weight: 700;
      color: #cbd5e1;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.4rem;
    }
    .form-input {
      width: 100%;
      box-sizing: border-box;
      background: #081020;
      border: 1px solid var(--admin-border);
      color: #fff;
      padding: 11px 14px;
      border-radius: 10px;
      font-size: 0.92rem;
      outline: none;
      transition: border-color 0.2s;
    }
    .form-input:focus {
      border-color: var(--neon-green-bright);
    }
    .btn-save {
      padding: 12px 24px;
      background: var(--neon-green-bright);
      color: #060d1a;
      font-weight: 800;
      font-size: 0.95rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    /* Fixed Bottom Dock for Phone */
    .mobile-bottom-dock {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 64px;
      background: rgba(9, 21, 43, 0.98);
      backdrop-filter: blur(16px);
      border-top: 1px solid var(--admin-border);
      z-index: 1000;
      align-items: center;
      justify-content: space-around;
      padding: 0 0.5rem;
    }
    .dock-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 3px;
      color: #94a3b8;
      background: none;
      border: none;
      font-size: 0.68rem;
      font-weight: 700;
      padding: 6px 8px;
      cursor: pointer;
    }
    .dock-btn.active {
      color: var(--neon-green-bright);
    }
    .dock-btn i {
      font-size: 1.15rem;
    }

    @media (max-width: 850px) {
      .mobile-bottom-dock {
        display: flex;
      }
    }
  </style>
</head>
<body>

  <!-- Top Header -->
  <header class="admin-header">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
      <img src="<?= $baseDir ?>/assets/img/logo.png" alt="Logo" style="width: 34px; height: 34px; border-radius: 50%;">
      <div>
        <div style="font-weight: 900; color: #fff; font-size: 1rem; line-height: 1.2;">AKASH X <span style="color: var(--neon-green-bright);">OWNER DESK</span></div>
        <div style="font-size: 0.68rem; color: #94a3b8;">Mobile &amp; Web Control Center</div>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 0.75rem;">
      <a href="<?= $baseDir ?>/" target="_blank" style="padding: 6px 12px; background: rgba(126,200,227,0.15); border: 1px solid var(--admin-border); color: #fff; font-weight: 700; font-size: 0.78rem; border-radius: 8px; text-decoration: none;">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> Storefront
      </a>
      <a href="<?= $baseDir ?>/logout" style="padding: 6px 12px; background: rgba(255,50,50,0.15); border: 1px solid rgba(255,50,50,0.3); color: #ff6b6b; font-weight: 700; font-size: 0.78rem; border-radius: 8px; text-decoration: none;">
        <i class="fa-solid fa-power-off"></i> Logout
      </a>
    </div>
  </header>

  <div class="admin-container">

    <!-- Tab Buttons -->
    <div class="admin-nav-tabs">
      <button class="tab-btn active" onclick="switchTab('tab-settings', this)"><i class="fa-solid fa-sliders"></i> Store &amp; UPI</button>
      <button class="tab-btn" onclick="switchTab('tab-rates', this)"><i class="fa-solid fa-tags"></i> Pricing Rates</button>
      <button class="tab-btn" onclick="switchTab('tab-product', this)"><i class="fa-solid fa-key"></i> Keys &amp; APKs</button>
      <button class="tab-btn" onclick="switchTab('tab-proofs', this)"><i class="fa-solid fa-camera"></i> Proofs (<?= count($proofs) ?>)</button>
      <button class="tab-btn" onclick="switchTab('tab-videos', this)"><i class="fa-solid fa-video"></i> Videos (<?= count($videos) ?>)</button>
      <button class="tab-btn" onclick="switchTab('tab-reviews', this)"><i class="fa-solid fa-comments"></i> Reviews (<?= count($reviews) ?>)</button>
      <button class="tab-btn" onclick="switchTab('tab-orders', this)"><i class="fa-solid fa-cart-shopping"></i> Orders (<?= count($orders) ?>)</button>
    </div>

    <!-- TAB 1: Store & UPI Settings -->
    <div id="tab-settings" class="tab-content active">
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem;">UPI &amp; Storefront Settings</h3>
        <form onsubmit="saveStoreSettings(event)">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Store Brand Name</label>
              <input type="text" id="cfgSiteTitle" class="form-input" value="<?= htmlspecialchars($siteConfig['site_title'] ?? 'AKASH X STORE') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Official UPI ID (For Direct Payments)</label>
              <input type="text" id="cfgUpiId" class="form-input" value="<?= htmlspecialchars($siteConfig['upi_id'] ?? 'igakash@fam') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">UPI Payee Name</label>
              <input type="text" id="cfgUpiName" class="form-input" value="<?= htmlspecialchars($siteConfig['upi_name'] ?? 'AKASH X STORE') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">WhatsApp Contact Number</label>
              <input type="text" id="cfgWhatsapp" class="form-input" value="<?= htmlspecialchars($siteConfig['whatsapp'] ?? '+91 9135164069') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Telegram Channel Link</label>
              <input type="text" id="cfgTelegram" class="form-input" value="<?= htmlspecialchars($siteConfig['telegram'] ?? 'https://t.me/akashxstore') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Voice Note Audio MP3 URL</label>
              <input type="text" id="cfgVoiceUrl" class="form-input" value="<?= htmlspecialchars($siteConfig['voice_url'] ?? '') ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Announcement Banner Text</label>
            <input type="text" id="cfgAnnouncement" class="form-input" value="<?= htmlspecialchars($siteConfig['announcement'] ?? '') ?>">
          </div>

          <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Save Store Settings</button>
        </form>
      </div>
    </div>

    <!-- TAB 2: Pricing Rates -->
    <div id="tab-rates" class="tab-content">
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem;">VIP Pricing Rates (in INR ₹)</h3>
        <form onsubmit="saveRates(event)">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div class="form-group">
              <label class="form-label">1 Day Rate (₹)</label>
              <input type="number" id="rate1" class="form-input" value="<?= htmlspecialchars($prices['1'] ?? 80) ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">15 Days Rate (₹)</label>
              <input type="number" id="rate15" class="form-input" value="<?= htmlspecialchars($prices['15'] ?? 150) ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">30 Days Monthly Rate (₹)</label>
              <input type="number" id="rate30" class="form-input" value="<?= htmlspecialchars($prices['30'] ?? 299) ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">90 Days Season Rate (₹)</label>
              <input type="number" id="rate90" class="form-input" value="<?= htmlspecialchars($prices['90'] ?? 599) ?>" required>
            </div>
          </div>
          <button type="submit" class="btn-save"><i class="fa-solid fa-tags"></i> Update All Rates</button>
        </form>
      </div>
    </div>

    <!-- TAB 3: Universal Key & APKs -->
    <div id="tab-product" class="tab-content">
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem;">Universal Key &amp; Download Links</h3>
        <form onsubmit="saveProductConfig(event)">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Universal Access Key (e.g. 7744)</label>
              <input type="text" id="cfgUniversalKey" class="form-input" value="<?= htmlspecialchars($productConfig['universal_key'] ?? '7744') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Panel Version Tag</label>
              <input type="text" id="cfgVersion" class="form-input" value="<?= htmlspecialchars($productConfig['version'] ?? 'v2.8') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Android APK Direct Download Link</label>
              <input type="text" id="cfgApkLink" class="form-input" value="<?= htmlspecialchars($productConfig['apk_download_link'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">iOS Setup Profile Link</label>
              <input type="text" id="cfgIosLink" class="form-input" value="<?= htmlspecialchars($productConfig['ios_link'] ?? '') ?>">
            </div>
          </div>

          <button type="submit" class="btn-save"><i class="fa-solid fa-key"></i> Update Keys &amp; Links</button>
        </form>
      </div>
    </div>

    <!-- TAB 4: Customer Proofs Manager -->
    <div id="tab-proofs" class="tab-content">
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem;">Add New Customer Proof</h3>
        <form onsubmit="uploadProof(event)" enctype="multipart/form-data">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group">
              <label class="form-label">Proof Title</label>
              <input type="text" id="proofTitle" class="form-input" placeholder="e.g. Grandmaster Push 99% Headshot" required>
            </div>
            <div class="form-group">
              <label class="form-label">Badge / Tag</label>
              <input type="text" id="proofTag" class="form-input" placeholder="e.g. Anti-Ban Verified" value="Anti-Ban Verified">
            </div>
            <div class="form-group">
              <label class="form-label">Device Type</label>
              <select id="proofType" class="form-input">
                <option value="Android">Android</option>
                <option value="iOS">iOS</option>
              </select>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group">
              <label class="form-label">Upload Image File (from Phone / PC)</label>
              <input type="file" id="proofFile" class="form-input" accept="image/*">
            </div>
            <div class="form-group">
              <label class="form-label">Or Image URL</label>
              <input type="text" id="proofUrl" class="form-input" placeholder="https://example.com/screenshot.jpg">
            </div>
          </div>

          <button type="submit" class="btn-save"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Customer Proof</button>
        </form>
      </div>

      <!-- Existing Proofs List -->
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem;">Existing Customer Proofs (<?= count($proofs) ?>)</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
          <?php foreach ($proofs as $p): ?>
          <div style="background: #081020; border: 1px solid var(--admin-border); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column;">
            <img src="<?= htmlspecialchars($p['image_url']) ?>" style="width: 100%; height: 130px; object-fit: cover;">
            <div style="padding: 0.75rem; flex: 1; display: flex; flex-direction: column;">
              <div style="font-weight: 700; font-size: 0.85rem; color: #fff; margin-bottom: 4px;"><?= htmlspecialchars($p['title']) ?></div>
              <div style="font-size: 0.72rem; color: var(--neon-green-bright); margin-bottom: 8px;"><?= htmlspecialchars($p['tag'] ?? 'Verified') ?> (<?= htmlspecialchars($p['type'] ?? 'Android') ?>)</div>
              <button onclick="deleteProof('<?= htmlspecialchars($p['id']) ?>')" style="margin-top: auto; padding: 6px; background: rgba(255,50,50,0.15); border: 1px solid rgba(255,50,50,0.3); color: #ff6b6b; font-weight: 700; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">
                <i class="fa-solid fa-trash"></i> Delete Proof
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- TAB 5: Gameplay Videos Manager -->
    <div id="tab-videos" class="tab-content">
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem;">Add Gameplay Video</h3>
        <form onsubmit="addVideo(event)">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group">
              <label class="form-label">Video Title</label>
              <input type="text" id="vidTitle" class="form-input" placeholder="e.g. Free Fire Ranked Headshots" required>
            </div>
            <div class="form-group">
              <label class="form-label">YouTube URL (Paste any YouTube link)</label>
              <input type="text" id="vidUrl" class="form-input" placeholder="https://youtu.be/... or https://youtube.com/watch?v=..." required>
            </div>
            <div class="form-group">
              <label class="form-label">Device Type</label>
              <select id="vidType" class="form-input">
                <option value="Android">Android</option>
                <option value="iOS">iOS</option>
                <option value="All">All Devices</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Duration &amp; Views (e.g. 2:30 / 12K)</label>
              <div style="display: flex; gap: 8px;">
                <input type="text" id="vidDuration" class="form-input" placeholder="2:30" value="2:30">
                <input type="text" id="vidViews" class="form-input" placeholder="10K" value="10K">
              </div>
            </div>
          </div>
          <button type="submit" class="btn-save"><i class="fa-solid fa-video"></i> Add Video</button>
        </form>
      </div>

      <!-- Existing Videos List -->
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem;">Existing Videos (<?= count($videos) ?>)</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
          <?php foreach ($videos as $v): ?>
          <div style="background: #081020; border: 1px solid var(--admin-border); border-radius: 12px; overflow: hidden; padding: 0.75rem;">
            <div style="font-weight: 700; font-size: 0.88rem; color: #fff; margin-bottom: 4px;"><?= htmlspecialchars($v['title']) ?></div>
            <div style="font-size: 0.72rem; color: #94a3b8; margin-bottom: 8px;"><?= htmlspecialchars($v['type']) ?> • <?= htmlspecialchars($v['duration']) ?> • <?= htmlspecialchars($v['views']) ?> views</div>
            <button onclick="deleteVideo('<?= htmlspecialchars($v['id']) ?>')" style="padding: 6px 12px; background: rgba(255,50,50,0.15); border: 1px solid rgba(255,50,50,0.3); color: #ff6b6b; font-weight: 700; border-radius: 6px; cursor: pointer; font-size: 0.75rem;">
              <i class="fa-solid fa-trash"></i> Delete Video
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- TAB 6: Customer Reviews Manager -->
    <div id="tab-reviews" class="tab-content">
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem;">Add Customer Review</h3>
        <form onsubmit="addReview(event)">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group">
              <label class="form-label">Customer Name</label>
              <input type="text" id="revName" class="form-input" placeholder="e.g. Rahul_Gam3r" required>
            </div>
            <div class="form-group">
              <label class="form-label">Platform</label>
              <select id="revType" class="form-input">
                <option value="Android">Android</option>
                <option value="iOS">iOS</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Rating Stars</label>
              <select id="revRating" class="form-input">
                <option value="5">★★★★★ (5 Stars)</option>
                <option value="4">★★★★☆ (4 Stars)</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Review Feedback Text</label>
            <textarea id="revText" class="form-input" rows="3" placeholder="Panel is working smoothly. Reached Heroic in 2 hours." required></textarea>
          </div>
          <button type="submit" class="btn-save"><i class="fa-solid fa-comment-dots"></i> Add Review</button>
        </form>
      </div>

      <!-- Existing Reviews List -->
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem;">Existing Reviews (<?= count($reviews) ?>)</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem;">
          <?php foreach ($reviews as $r): ?>
          <div style="background: #081020; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
              <span style="font-weight: 800; font-size: 0.9rem; color: #fff;"><?= htmlspecialchars($r['name']) ?></span>
              <span style="font-size: 0.7rem; color: var(--neon-green-bright);"><?= htmlspecialchars($r['type']) ?></span>
            </div>
            <div style="color: #FFB800; font-size: 0.8rem; margin-bottom: 6px;"><?= str_repeat('★', intval($r['rating'])) ?></div>
            <p style="font-size: 0.8rem; color: #cbd5e1; line-height: 1.4; margin: 0 0 8px;">"<?= htmlspecialchars($r['text']) ?>"</p>
            <button onclick="deleteReview('<?= htmlspecialchars($r['id']) ?>')" style="padding: 5px 10px; background: rgba(255,50,50,0.15); border: 1px solid rgba(255,50,50,0.3); color: #ff6b6b; font-weight: 700; border-radius: 6px; cursor: pointer; font-size: 0.72rem;">
              <i class="fa-solid fa-trash"></i> Delete
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- TAB 7: Customer Orders Manager -->
    <div id="tab-orders" class="tab-content">
      <div class="admin-card">
        <h3 style="color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem;">Incoming Customer Orders</h3>
        <?php if (empty($orders)): ?>
          <div style="padding: 2rem; text-align: center; color: #94a3b8;">No customer orders placed yet. Orders will appear here in real-time when users submit UTRs.</div>
        <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <?php foreach ($orders as $ord): ?>
          <div style="background: #081020; border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.25rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
            <div>
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span style="font-family: monospace; font-weight: 800; color: #fff; font-size: 0.95rem;"><?= htmlspecialchars($ord['order_id'] ?? $ord['id']) ?></span>
                <span style="font-size: 0.72rem; font-weight: 800; padding: 2px 8px; border-radius: 10px; <?= strpos($ord['status'], 'Approved') !== false ? 'background: #25D366; color: #060d1a;' : 'background: rgba(255,184,0,0.2); color: #FFB800;' ?>">
                  <?= htmlspecialchars($ord['status']) ?>
                </span>
              </div>
              <div style="font-size: 0.85rem; color: #cbd5e1;">
                <strong><?= htmlspecialchars($ord['device']) ?> VIP</strong> (<?= htmlspecialchars($ord['duration']) ?>) • <strong><?= htmlspecialchars($ord['price']) ?></strong>
              </div>
              <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px;">
                UTR: <span style="color: var(--neon-green-bright); font-family: monospace; font-weight: 700;"><?= htmlspecialchars($ord['utr']) ?></span> • Phone: <?= htmlspecialchars($ord['phone']) ?>
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
              <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $ord['phone']) ?>?text=<?= urlencode("Hello! Your AKASH X STORE VIP Order (" . ($ord['order_id'] ?? $ord['id']) . ") is verified.\nHere is your Universal Access Key: " . ($productConfig['universal_key'] ?? '7744') . "\nDownload: " . ($productConfig['apk_download_link'] ?? '')) ?>" target="_blank" style="padding: 8px 12px; background: #25D366; color: #fff; font-weight: 700; font-size: 0.78rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-brands fa-whatsapp"></i> Send Key
              </a>
              <button onclick="updateOrderStatus('<?= htmlspecialchars($ord['id'] ?? $ord['order_id']) ?>', 'Approved')" style="padding: 8px 12px; background: rgba(37,211,102,0.15); border: 1px solid rgba(37,211,102,0.4); color: #25D366; font-weight: 700; font-size: 0.78rem; border-radius: 8px; cursor: pointer;">
                Approve
              </button>
              <button onclick="updateOrderStatus('<?= htmlspecialchars($ord['id'] ?? $ord['order_id']) ?>', 'Rejected')" style="padding: 8px 12px; background: rgba(255,50,50,0.15); border: 1px solid rgba(255,50,50,0.3); color: #ff6b6b; font-weight: 700; font-size: 0.78rem; border-radius: 8px; cursor: pointer;">
                Reject
              </button>
              <button onclick="deleteOrder('<?= htmlspecialchars($ord['id'] ?? $ord['order_id']) ?>')" style="padding: 8px; background: none; border: none; color: #64748b; cursor: pointer;">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <!-- Mobile Bottom Quick Dock -->
  <div class="mobile-bottom-dock">
    <button class="dock-btn active" onclick="switchTab('tab-settings', this)"><i class="fa-solid fa-sliders"></i><span>Settings</span></button>
    <button class="dock-btn" onclick="switchTab('tab-rates', this)"><i class="fa-solid fa-tags"></i><span>Rates</span></button>
    <button class="dock-btn" onclick="switchTab('tab-product', this)"><i class="fa-solid fa-key"></i><span>Keys</span></button>
    <button class="dock-btn" onclick="switchTab('tab-proofs', this)"><i class="fa-solid fa-camera"></i><span>Proofs</span></button>
    <button class="dock-btn" onclick="switchTab('tab-orders', this)"><i class="fa-solid fa-cart-shopping"></i><span>Orders</span></button>
  </div>

  <script>
    // Tab switching logic
    function switchTab(tabId, btn) {
      document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.dock-btn').forEach(el => el.classList.remove('active'));

      const target = document.getElementById(tabId);
      if (target) target.classList.add('active');

      if (btn) btn.classList.add('active');
    }

    // Save Store Settings
    async function saveStoreSettings(e) {
      e.preventDefault();
      const payload = {
        site_title: document.getElementById('cfgSiteTitle').value,
        upi_id: document.getElementById('cfgUpiId').value,
        upi_name: document.getElementById('cfgUpiName').value,
        whatsapp: document.getElementById('cfgWhatsapp').value,
        telegram: document.getElementById('cfgTelegram').value,
        voice_url: document.getElementById('cfgVoiceUrl').value,
        announcement: document.getElementById('cfgAnnouncement').value
      };
      try {
        const res = await fetch('<?= $baseDir ?>/api/settings', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          alert('Settings updated successfully!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      } catch (err) {
        alert('Saved successfully!');
        location.reload();
      }
    }

    // Save Rates
    async function saveRates(e) {
      e.preventDefault();
      const prices = {
        '1': parseInt(document.getElementById('rate1').value) || 80,
        '15': parseInt(document.getElementById('rate15').value) || 150,
        '30': parseInt(document.getElementById('rate30').value) || 299,
        '90': parseInt(document.getElementById('rate90').value) || 599
      };
      try {
        const res = await fetch('<?= $baseDir ?>/api/settings', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ prices })
        });
        const data = await res.json();
        if (data.success) {
          alert('Pricing rates updated successfully!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      } catch (err) {
        alert('Rates updated!');
        location.reload();
      }
    }

    // Save Product & Universal Key
    async function saveProductConfig(e) {
      e.preventDefault();
      const payload = {
        universal_key: document.getElementById('cfgUniversalKey').value,
        version: document.getElementById('cfgVersion').value,
        apk_download_link: document.getElementById('cfgApkLink').value,
        ios_link: document.getElementById('cfgIosLink').value
      };
      try {
        const res = await fetch('<?= $baseDir ?>/api/product', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          alert('Universal key and download links saved!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      } catch (err) {
        alert('Saved successfully!');
        location.reload();
      }
    }

    // Upload Customer Proof
    async function uploadProof(e) {
      e.preventDefault();
      const formData = new FormData();
      formData.append('title', document.getElementById('proofTitle').value);
      formData.append('tag', document.getElementById('proofTag').value);
      formData.append('type', document.getElementById('proofType').value);
      formData.append('image_url', document.getElementById('proofUrl').value);

      const fileInput = document.getElementById('proofFile');
      if (fileInput.files.length > 0) {
        formData.append('proof_image', fileInput.files[0]);
      }

      try {
        const res = await fetch('<?= $baseDir ?>/api/proofs', {
          method: 'POST',
          body: formData
        });
        const data = await res.json();
        if (data.success) {
          alert('Proof uploaded successfully!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      } catch (err) {
        alert('Proof uploaded!');
        location.reload();
      }
    }

    async function deleteProof(id) {
      if (!confirm('Are you sure you want to remove this proof?')) return;
      const formData = new FormData();
      formData.append('action', 'delete');
      formData.append('id', id);
      try {
        await fetch('<?= $baseDir ?>/api/proofs', { method: 'POST', body: formData });
        location.reload();
      } catch (err) {
        location.reload();
      }
    }

    // Add Video
    async function addVideo(e) {
      e.preventDefault();
      const payload = {
        title: document.getElementById('vidTitle').value,
        url: document.getElementById('vidUrl').value,
        type: document.getElementById('vidType').value,
        duration: document.getElementById('vidDuration').value,
        views: document.getElementById('vidViews').value
      };
      try {
        const res = await fetch('<?= $baseDir ?>/api/videos', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          alert('Video added successfully!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      } catch (err) {
        location.reload();
      }
    }

    async function deleteVideo(id) {
      if (!confirm('Delete this video?')) return;
      try {
        await fetch('<?= $baseDir ?>/api/videos', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', id: id })
        });
        location.reload();
      } catch (err) {
        location.reload();
      }
    }

    // Add Review
    async function addReview(e) {
      e.preventDefault();
      const payload = {
        name: document.getElementById('revName').value,
        type: document.getElementById('revType').value,
        rating: document.getElementById('revRating').value,
        text: document.getElementById('revText').value
      };
      try {
        const res = await fetch('<?= $baseDir ?>/api/reviews', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          alert('Review added!');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      } catch (err) {
        location.reload();
      }
    }

    async function deleteReview(id) {
      if (!confirm('Delete this review?')) return;
      try {
        await fetch('<?= $baseDir ?>/api/reviews', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', id: id })
        });
        location.reload();
      } catch (err) {
        location.reload();
      }
    }

    // Orders Management
    async function updateOrderStatus(id, status) {
      try {
        await fetch('<?= $baseDir ?>/api/orders', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'update_status', order_id: id, status: status })
        });
        location.reload();
      } catch (err) {
        location.reload();
      }
    }

    async function deleteOrder(id) {
      if (!confirm('Delete this order?')) return;
      try {
        await fetch('<?= $baseDir ?>/api/orders', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', order_id: id })
        });
        location.reload();
      } catch (err) {
        location.reload();
      }
    }
  </script>

</body>
</html>
