<?php
require 'db.php';
require 'helpers.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password required.';
    } else {
        $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Invalid credentials.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - AI Reviews</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="header"><div class="logo">AI Reviews</div></div>
    <h2>Login</h2>

    <?php if ($errors): ?>
      <div class="card">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= esc($e) ?></li>
          <?php endforeach;?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="form-row">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="<?= esc($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="form-row">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
      </div>
      <div class="actions">
        <button type="submit">Login</button>
        <div class="small">No account? <a href="register.php">Register</a></div>
      </div>
    </form>

    <div class="footer">Use a secure password. This demo stores hashed passwords.</div>
  </div>
</body>
</html>
