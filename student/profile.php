<?php
$page_title = 'My Profile';
require 'header.php';

$error = '';
$success = flash('success');

if (is_post()) {
    verify_csrf();

    $phone = trim($_POST['phone'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $semester = (int) ($_POST['semester'] ?? 0);

    if ($course === '' || $semester < 1 || $semester > 8) {
        $error = 'Please enter valid profile details.';
    } else {
        $stmt = db()->prepare('UPDATE students SET phone = ?, course = ?, semester = ? WHERE id = ?');
        $stmt->execute([$phone, $course, $semester, $student['id']]);
        flash('success', 'Profile updated successfully.');
        redirect('profile.php');
    }
}
?>
<div class="page-title"><div><span class="eyebrow">Student Profile</span><h1><?= e($student['student_name']) ?></h1><p>View and update your academic information.</p></div></div>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

<div class="profile-card">
    <div class="avatar"><?= e(strtoupper(substr($student['student_name'], 0, 1))) ?></div>
    <div class="profile-info"><h2><?= e($student['student_name']) ?></h2><p><?= e($student['enrollment_no']) ?> • <?= e($student['email']) ?></p></div>
    <form method="post" class="form profile-form">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <label>Student Name<input value="<?= e($student['student_name']) ?>" disabled></label>
        <label>Enrollment No.<input value="<?= e($student['enrollment_no']) ?>" disabled></label>
        <label>Email<input value="<?= e($student['email']) ?>" disabled></label>
        <label>Phone<input name="phone" value="<?= e($student['phone']) ?>"></label>
        <label>Course<input name="course" required value="<?= e($student['course']) ?>"></label>
        <label>Semester<select name="semester" required><?php for ($i = 1; $i <= 8; $i++): ?><option value="<?= $i ?>" <?= ((int) $student['semester'] === $i) ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select></label>
        <button class="btn btn-primary" type="submit">Save Changes</button>
    </form>
</div>
<?php require 'footer.php'; ?>