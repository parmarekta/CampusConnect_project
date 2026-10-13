<?php
require_once '../config.php';

if (!empty($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}

$error = '';
if (is_post()) {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = db()->prepare('SELECT * FROM admins WHERE email = ?');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        redirect('dashboard.php');
    }

    $error = 'Invalid administrator credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin Login | CampusConnect</title><link rel="stylesheet" href="../assets/style.css"></head>
<body class="auth-bg">
<div class="auth-card">
    <a class="brand center" href="../index.php">Campus<span>Connect</span></a>
    <h1>Administrator Login</h1><p class="muted">Manage events, students and announcements.</p>
    <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <label>Email Address<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="btn btn-primary full" type="submit">Admin Login</button>
    </form>
    <div class="demo-box"><strong>Demo Admin</strong><br>admin@campusconnect.com / admin123</div>
    <p class="auth-bottom"><a href="../index.php">← Back to CampusConnect</a></p>
</div>
</body>
</html>