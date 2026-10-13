<?php
require_once 'config.php';

if (!empty($_SESSION['student_id'])) {
    redirect('student/dashboard.php');
}

$error = '';
$success = flash('success');

if (is_post()) {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {
        $stmt = db()->prepare('SELECT * FROM students WHERE email = ?');
        $stmt->execute([$email]);
        $student = $stmt->fetch();

        if ($student && password_verify($password, $student['password'])) {
            session_regenerate_id(true);
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['student_name'];
            redirect('student/dashboard.php');
        }

        $error = 'Invalid student email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | CampusConnect</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-bg">
<div class="auth-card">
    <a class="brand center" href="index.php">Campus<span>Connect</span></a>
    <h1>Student Login</h1>
    <p class="muted">Sign in to manage your campus activities.</p>

    <?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

    <form method="post" class="form">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <label>Email Address
            <input type="email" name="email" required autocomplete="email">
        </label>
        <label>Password
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button class="btn btn-primary full" type="submit">Login</button>
    </form>

    <div class="demo-box"><strong>Demo Student</strong><br>ekta@campusconnect.com / student123</div>
    <p class="auth-bottom">New student? <a href="register.php">Create an account</a></p>
    <p class="auth-bottom"><a href="admin/login.php">Administrator Login</a></p>
</div>
</body>
</html>