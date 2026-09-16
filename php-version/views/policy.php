<?php
require_once __DIR__ . '/header.php';
$type = $_GET['type'] ?? 'terms';
?>

<main class="container" style="max-width:800px;margin:40px auto;padding-bottom:80px;">
  <div class="glass-card" style="padding:36px 32px;">

    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px;">
      <a href="?type=terms"
         style="font-size:0.8rem;font-weight:600;padding:6px 14px;border-radius:20px;
                background:<?= $type === 'terms' ? 'var(--neon-green)' : 'rgba(154,230,0,0.1)' ?>;
                color:<?= $type === 'terms' ? '#0a1400' : 'var(--neon-green-bright)' ?>;
                border:1px solid var(--border-green);text-decoration:none;">
        Terms of Service
      </a>
      <a href="?type=privacy"
         style="font-size:0.8rem;font-weight:600;padding:6px 14px;border-radius:20px;
                background:<?= $type === 'privacy' ? 'var(--neon-green)' : 'rgba(154,230,0,0.1)' ?>;
                color:<?= $type === 'privacy' ? '#0a1400' : 'var(--neon-green-bright)' ?>;
                border:1px solid var(--border-green);text-decoration:none;">
        Privacy Policy
      </a>
      <a href="?type=refund"
         style="font-size:0.8rem;font-weight:600;padding:6px 14px;border-radius:20px;
                background:<?= $type === 'refund' ? 'var(--neon-green)' : 'rgba(154,230,0,0.1)' ?>;
                color:<?= $type === 'refund' ? '#0a1400' : 'var(--neon-green-bright)' ?>;
                border:1px solid var(--border-green);text-decoration:none;">
        Refund Policy
      </a>
      <a href="?type=disclaimer"
         style="font-size:0.8rem;font-weight:600;padding:6px 14px;border-radius:20px;
                background:<?= $type === 'disclaimer' ? 'var(--neon-green)' : 'rgba(154,230,0,0.1)' ?>;
                color:<?= $type === 'disclaimer' ? '#0a1400' : 'var(--neon-green-bright)' ?>;
                border:1px solid var(--border-green);text-decoration:none;">
        Disclaimer
      </a>
    </div>

    <?php if ($type === 'privacy'): ?>
      <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:#ffffff;margin:0 0 6px;">Privacy Policy</h1>
      <p style="color:var(--text-muted);font-size:0.82rem;margin-bottom:24px;">Last updated: September 2026</p>
      <div class="policy-content" style="color:var(--text-secondary);line-height:1.8;font-size:0.92rem;">
        <p>We respect your privacy. We collect minimal information required to deliver and verify orders (such as Transaction UTR and chosen platform). We do not store financial payment credentials.</p>
        <h3>Data Security</h3>
        <p>All transaction verification is processed via secure SSL protocols and no personal identifiable info is sold to third parties.</p>
      </div>
    <?php elseif ($type === 'refund'): ?>
      <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:#ffffff;margin:0 0 6px;">Refund Policy</h1>
      <p style="color:var(--text-muted);font-size:0.82rem;margin-bottom:24px;">Last updated: September 2026</p>
      <div class="policy-content" style="color:var(--text-secondary);line-height:1.8;font-size:0.92rem;">
        <p>Due to the nature of digital license keys and instant software activation, all sales are considered final. If you encounter setup difficulty, our 24/7 support channel is available to assist your activation.</p>
      </div>
    <?php elseif ($type === 'disclaimer'): ?>
      <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:#ffffff;margin:0 0 6px;">Disclaimer</h1>
      <p style="color:var(--text-muted);font-size:0.82rem;margin-bottom:24px;">Last updated: September 2026</p>
      <div class="policy-content" style="color:var(--text-secondary);line-height:1.8;font-size:0.92rem;">
        <p>VIP X STORE is an independent provider. All trademarks and brand names belong to their respective copyright holders. Panels and digital utilities are provided as-is for enthusiast entertainment purposes.</p>
      </div>
    <?php else: ?>
      <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:#ffffff;margin:0 0 6px;">Terms of Service</h1>
      <p style="color:var(--text-muted);font-size:0.82rem;margin-bottom:24px;">Last updated: September 2026</p>
      <div class="policy-content" style="color:var(--text-secondary);line-height:1.8;font-size:0.92rem;">
        <p>By purchasing from VIP X STORE, you agree to these terms. All sales are final. Digital products are delivered after payment verification. We reserve the right to modify these terms at any time.</p>
        <h3>Usage Policy</h3>
        <p>Products are for personal use only. Sharing or reselling access is strictly prohibited and will result in immediate termination without refund.</p>
      </div>
    <?php endif; ?>

  </div>

  <div style="text-align:center;margin-top:20px;">
    <a href="<?= $baseDir ?>/" style="color:var(--text-muted);font-size:0.85rem;">← Back to Home</a>
  </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
