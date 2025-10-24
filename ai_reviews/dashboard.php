<?php
require 'helpers.php';
require_login();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard - AI Reviews</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">AI Reviews</div>
      <div class="small">Hello, <?= esc($_SESSION['user_name']) ?> — <a href="logout.php">Logout</a></div>
    </div>

    <h2 class="center">What do you want to do?</h2>

    <div class="grid">
      <div class="card center">
        <h3>Give a review</h3>
        <p class="small">Share your rating & comment for an AI tool.</p>
        <a href="give_review.php"><button>Give a review</button></a>
      </div>

      <div class="card center">
        <h3>Read reviews</h3>
        <p class="small">Search reviews from other users.</p>
        <a href="search.php"><button>Read reviews</button></a>
      </div>
    </div>

    <div style="margin-top:18px" class="small">Tip: You can only edit or delete your own reviews (feature not enabled in this simple build).</div>
  </div>
</body>
</html>
