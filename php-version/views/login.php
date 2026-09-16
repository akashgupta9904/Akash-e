<?php
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseDir = dirname($scriptName);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = '';
}

// If already logged in, redirect to admin
if (is_admin_authenticated()) {
    header('Location: ' . ($baseDir ?: '') . '/admin');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'] ?? '';
    if ($pass === ADMIN_PASSWORD) {
        $_SESSION['akash_admin_logged_in'] = true;
        header('Location: ' . ($baseDir ?: '') . '/admin');
        exit;
    } else {
        $error = 'Invalid Owner Security Key!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Security Portal | AKASH X STORE</title>
  <link rel="icon" type="image/svg+xml" href="<?= $baseDir ?>/favicon.svg">
  <link rel="stylesheet" href="<?= $baseDir ?>/assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body {
      background: radial-gradient(circle at top center, #0d2040 0%, #050b16 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }
    .login-card {
      background: rgba(13, 25, 48, 0.9);
      border: 1px solid var(--border-green);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 2.5rem 2rem;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.7);
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="login-card">
    <img src="<?= $baseDir ?>/assets/img/logo.png" alt="Logo" style="width: 64px; height: 64px; border-radius: 50%; border: 2px solid var(--neon-green-bright); margin-bottom: 1rem;">
    <h2 style="color: #fff; font-size: 1.5rem; font-weight: 900; margin: 0 0 0.25rem;">Owner Control Portal</h2>
    <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 1.75rem;">Enter master password to access store management</p>

    <?php if (!empty($error)): ?>
    <div style="background: rgba(255, 50, 50, 0.15); border: 1px solid rgba(255, 50, 50, 0.4); color: #ff6b6b; padding: 10px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem;">
      <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div style="margin-bottom: 1.5rem; text-align: left;">
        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 0.4rem;">
          <i class="fa-solid fa-lock" style="color: var(--neon-green-bright); margin-right: 5px;"></i> Security Key / Password
        </label>
        <input type="password" name="password" placeholder="Enter owner key..." required autofocus style="width: 100%; box-sizing: border-box; background: #070e1c; border: 1px solid var(--border-green); color: #fff; padding: 12px 14px; border-radius: 10px; font-size: 1rem; outline: none;">
      </div>

      <button type="submit" style="width: 100%; padding: 13px; background: var(--neon-green-bright); color: #060d1a; font-weight: 900; border: none; border-radius: 10px; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <i class="fa-solid fa-arrow-right-to-bracket"></i> Unlock Control Center
      </button>
    </form>

    <div style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1rem;">
      <a href="<?= $baseDir ?>/" style="color: #94a3b8; font-size: 0.82rem; text-decoration: none;">
        ← Return to Storefront
      </a>
    </div>
  </div>

</body>
</html>
