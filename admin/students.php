<?php
$page_title = 'Students';
require 'header.php';

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = db()->prepare(
        "SELECT s.*, COUNT(CASE WHEN r.status = 'Registered' THEN 1 END) AS registrations
         FROM students s
         LEFT JOIN event_registrations r ON r.student_id = s.id
         WHERE s.student_name LIKE ? OR s.enrollment_no LIKE ? OR s.email LIKE ?
         GROUP BY s.id ORDER BY s.student_name"
    );
    $like = '%' . $search . '%';
    $stmt->execute([$like, $like, $like]);
    $students = $stmt->fetchAll();
} else {
    $students = db()->query(
        "SELECT s.*, COUNT(CASE WHEN r.status = 'Registered' THEN 1 END) AS registrations
         FROM students s
         LEFT JOIN event_registrations r ON r.student_id = s.id
         GROUP BY s.id ORDER BY s.student_name"
    )->fetchAll();
}
?>
<div class="page-title"><div><span class="eyebrow">Administration</span><h1>Registered Students</h1></div></div>
<form class="searchbar" method="get"><input name="search" placeholder="Search student name, enrollment or email..." value="<?= e($search) ?>"><button class="btn btn-primary" type="submit">Search</button></form>
<div class="table-wrap"><table><thead><tr><th>Student Name</th><th>Enrollment</th><th>Email</th><th>Course</th><th>Semester</th><th>Registrations</th></tr></thead><tbody>
<?php foreach ($students as $student): ?><tr><td><strong><?= e($student['student_name']) ?></strong></td><td><?= e($student['enrollment_no']) ?></td><td><?= e($student['email']) ?></td><td><?= e($student['course']) ?></td><td><?= e($student['semester']) ?></td><td><?= e($student['registrations']) ?></td></tr><?php endforeach; ?>
<?php if (!$students): ?><tr><td colspan="6" class="empty">No students found.</td></tr><?php endif; ?>
</tbody></table></div>
<?php require 'footer.php'; ?>