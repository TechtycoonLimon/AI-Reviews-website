<?php
require 'db.php';
require 'helpers.php';
require_login();

$q = trim($_GET['q'] ?? '');
$results = [];
$stats = null;

if ($q !== '') {
    // partial match - case-insensitive
    $search = "%{$q}%";
    $stmt = $pdo->prepare("
        SELECT r.id, r.tool_name, r.rating, r.comment, r.created_at, u.name AS reviewer
        FROM reviews r
        JOIN users u ON u.id = r.user_id
        WHERE r.tool_name LIKE ?
        ORDER BY r.created_at DESC
        LIMIT 200
    ");
    $stmt->execute([$search]);
    $results = $stmt->fetchAll();

    // stats: average & count
    $s2 = $pdo->prepare("SELECT COUNT(*) AS cnt, AVG(rating) AS avg_rating FROM reviews WHERE tool_name LIKE ?");
    $s2->execute([$search]);
    $stats = $s2->fetch();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Search Reviews - AI Reviews</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">AI Reviews</div>
      <div class="small">Hello, <?= esc($_SESSION['user_name']) ?> — <a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div>
    </div>

    <h2>Search reviews</h2>

    <form method="get">
      <div class="form-row">
        <label for="q">AI tool name (partial names allowed)</label>
        <input id="q" name="q" value="<?= esc($q) ?>" placeholder="e.g., ChatGPT, Claude, Midjourney">
      </div>
      <div class="actions">
        <button type="submit">Search</button>
        <a href="dashboard.php"><button type="button" style="background:#e2e8f0;color:#1a202c">Back</button></a>
      </div>
    </form>

    <?php if ($q !== ''): ?>
      <div class="card reviews">
        <h3>Results for "<?= esc($q) ?>"</h3>
        <?php if ($stats && $stats['cnt'] > 0): ?>
          <div class="small">Average rating: <span class="rating-badge"><?= round($stats['avg_rating'],2) ?></span> — <?= (int)$stats['cnt'] ?> reviews</div>
          <?php foreach ($results as $r): ?>
            <div class="review">
              <span class="rating-badge"><?= esc($r['rating']) ?></span>
              <strong><?= esc($r['tool_name']) ?></strong> — <span class="small">by <?= esc($r['reviewer']) ?> on <?= esc($r['created_at']) ?></span>
              <?php if (trim($r['comment']) !== ''): ?>
                <div style="margin-top:6px"><?= nl2br(esc($r['comment'])) ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="small">No reviews found for that query. Be the first to review it!</div>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div class="card">
        <p class="small">Try searching for an AI tool name like "ChatGPT", "Claude", "Midjourney". If you don't find it, use "Give a review" to add the first review.</p>
      </div>
    <?php endif; ?>

  </div>
</body>
</html>
