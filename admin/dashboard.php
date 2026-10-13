<?php
$page_title = 'Admin Dashboard';
require 'header.php';

$students = (int) db()->query('SELECT COUNT(*) FROM students')->fetchColumn();
$events = (int) db()->query('SELECT COUNT(*) FROM events')->fetchColumn();
$registrations = (int) db()->query("SELECT COUNT(*) FROM event_registrations WHERE status = 'Registered'")->fetchColumn();
$announcements = (int) db()->query('SELECT COUNT(*) FROM announcements')->fetchColumn();

$latest = db()->query(
    "SELECT e.*, COUNT(CASE WHEN r.status = 'Registered' THEN 1 END) AS registration_count
     FROM events e
     LEFT JOIN event_registrations r ON r.event_id = e.id
     GROUP BY e.id
     ORDER BY e.event_date DESC
     LIMIT 5"
)->fetchAll();
?>
<div class="welcome"><div><span class="eyebrow">Administrator Panel</span><h1>CampusConnect Overview</h1><p>Manage students, events and campus communication.</p></div><a class="btn btn-primary" href="events.php?action=add">+ Add Event</a></div>
<div class="stats">
    <div class="stat"><span>🎓</span><div><strong><?= $students ?></strong><small>Total Students</small></div></div>
    <div class="stat"><span>📅</span><div><strong><?= $events ?></strong><small>Total Events</small></div></div>
    <div class="stat"><span>✅</span><div><strong><?= $registrations ?></strong><small>Registrations</small></div></div>
    <div class="stat"><span>📢</span><div><strong><?= $announcements ?></strong><small>Announcements</small></div></div>
</div>
<div class="section-head"><h2>Event Summary</h2><a href="events.php">Manage events →</a></div>
<div class="table-wrap"><table><thead><tr><th>Event</th><th>Date</th><th>Venue</th><th>Registrations</th></tr></thead><tbody>
<?php foreach ($latest as $event): ?><tr><td><strong><?= e($event['title']) ?></strong></td><td><?= date('d M Y', strtotime($event['event_date'])) ?></td><td><?= e($event['venue']) ?></td><td><?= $event['registration_count'] ?> / <?= $event['max_seats'] ?></td></tr><?php endforeach; ?>
<?php if (!$latest): ?><tr><td colspan="4" class="empty">No events available.</td></tr><?php endif; ?>
</tbody></table></div>
<?php require 'footer.php'; ?>