<?php
require_once 'config.php';

if (!empty($_SESSION['student_id'])) {
    redirect('student/dashboard.php');
}

$error = '';

if (is_post()) {
    verify_csrf();

    $name = trim($_POST['student_name'] ?? '');
    $enrollment = trim($_POST['enrollment_no'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $semester = (int) ($_POST['semester'] ?? 0);
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || $enrollment === '' || $email === '' || $course === '' || $semester < 1 || $semester > 8) {
        $error = 'Please complete all required fields correctly.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must contain at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $stmt = db()->prepare(
                'INSERT INTO students (student_name, enrollment_no, email, phone, course, semester, password)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $name,
                $enrollment,
                $email,
                $phone,
                $course,
                $semester,
                password_hash($password, PASSWORD_DEFAULT),
            ]);

            flash('success', 'Registration successful. Please log in.');
            redirect('login.php');
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $error = 'Enrollment number or email address is already registered.';
            } else {
                $error = 'Unable to create the account. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | CampusConnect</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-bg">
<div class="auth-card wide">
    <a class="brand center" href="index.php">Campus<span>Connect</span></a>
    <h1>Create Student Account</h1>
    <p class="muted">Register to participate in college activities and events.</p>

    <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

    <form method="post" class="form">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <div class="form-grid">
            <label>Student Name *<input name="student_name" required value="<?= e($_POST['student_name'] ?? '') ?>"></label>
            <label>Enrollment No. *<input name="enrollment_no" required value="<?= e($_POST['enrollment_no'] ?? '') ?>"></label>
            <label>Email Address *<input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label>
            <label>Phone Number<input name="phone" maxlength="20" value="<?= e($_POST['phone'] ?? '') ?>"></label>
            <label>Course *<input name="course" required value="<?= e($_POST['course'] ?? 'B.Tech Computer Science Engineering') ?>"></label>
            <label>Semester *<select name="semester" required><?php for ($i = 1; $i <= 8; $i++): ?><option value="<?= $i ?>" <?= ((int) ($_POST['semester'] ?? 3) === $i) ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select></label>
            <label>Password *<input type="password" name="password" minlength="6" required></label>
            <label>Confirm Password *<input type="password" name="confirm_password" minlength="6" required></label>
        </div>
        <button class="btn btn-primary full" type="submit">Create Account</button>
    </form>

    <p class="auth-bottom">Already registered? <a href="login.php">Login here</a></p>
</div>
</body>
</html>