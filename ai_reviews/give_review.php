<?php
require 'db.php';
require 'helpers.php';
require_login();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tool_name = trim($_POST['tool_name'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($tool_name === '') $errors[] = 'Tool name is required.';
    if ($rating < 1 || $rating > 10) $errors[] = 'Rating must be between 1 and 10.';
    if (strlen($tool_name) > 255) $errors[] = 'Tool name too long.';
    if (strlen($comment) > 2000) $errors[] = 'Comment too long.';

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, tool_name, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $tool_name, $rating, $comment]);
        $success = 'Review saved successfully.';
        // clear form
        $_POST = [];
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Give Review - AI Reviews</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">AI Reviews</div>
      <div class="small">Hello, <?= esc($_SESSION['user_name']) ?> — <a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></div>
    </div>

    <h2>Submit a review</h2>

    <?php if ($errors): ?>
      <div class="card"><ul><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach;?></ul></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="card"><strong><?= esc($success) ?></strong></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="form-row">
        <label for="tool_name">AI tool name</label>
        <input id="tool_name" name="tool_name" value="<?= esc($_POST['tool_name'] ?? '') ?>" maxlength="255" required>
      </div>

      <div class="form-row">
        <label for="rating">Rating (1–10)</label>
        <select id="rating" name="rating" required>
          <?php for($i=1;$i<=10;$i++): ?>
            <option value="<?= $i ?>" <?= (isset($_POST['rating']) && (int)$_POST['rating'] === $i) ? 'selected' : '' ?>><?= $i ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="form-row">
        <label for="comment">Comment (optional)</label>
        <textarea id="comment" name="comment" rows="5" maxlength="2000"><?= esc($_POST['comment'] ?? '') ?></textarea>
      </div>

      <div class="actions">
        <button type="submit">Save review</button>
        <a href="dashboard.php"><button type="button" style="background:#e2e8f0;color:#1a202c">Back</button></a>
      </div>
    </form>
  </div>
</body>
</html>
