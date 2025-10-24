<?php
require 'db.php';
require 'helpers.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // validations
    if ($name === '') $errors[] = 'Name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';

    if (!$errors) {
        // check duplicate email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered. Try logging in.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);
            // auto-login
            $user_id = $pdo->lastInsertId();
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - AI Reviews</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="header"><div class="logo">AI Reviews</div></div>

    <h2>Create account</h2>
    <?php if ($errors): ?>
      <div class="card">
        <strong>Errors:</strong>
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= esc($e) ?></li>
          <?php endforeach;?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="form-row">
        <label for="name">Name</label>
        <input id="name" name="name" value="<?= esc($_POST['name'] ?? '') ?>" required maxlength="100">
      </div>
      <div class="form-row">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="<?= esc($_POST['email'] ?? '') ?>" required maxlength="255">
      </div>
      <div class="form-row">
        <label for="password">Password (min 8 chars)</label>
        <input id="password" type="password" name="password" required>
      </div>
      <div class="actions">
        <button type="submit">Register</button>
        <div class="small">Already have an account? <a href="login.php">Login</a></div>
      </div>
    </form>

    <div class="footer">Built for XAMPP. Be sure to secure in production.</div>
  </div>
</body>
</html>
