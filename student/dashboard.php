<?php
$page_title = 'Student Dashboard';
require 'header.php';

$events = db()->query(
    "SELECT e.*,
            (SELECT COUNT(*) FROM event_registrations r WHERE r.event_id = e.id AND r.status = 'Registered') AS registered_count
     FROM events e
     WHERE e.event_date >= CURDATE()
     ORDER BY e.event_date, e.event_time
     LIMIT 3"
)->fetchAll();

$stmt = db()->prepare("SELECT COUNT(*) FROM event_registrations WHERE student_id = ? AND status = 'Registered'");
$stmt->execute([$student['id']]);
$my_registrations = (int) $stmt->fetchColumn();

$announcements = db()->query('SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3')->fetchAll();
?>
<div class="welcome">
    <div>
        <span class="eyebrow">Student Dashboard</span>
        <h1>Hello, <?= e($student['student_name']) ?> 👋</h1>
        <p>Explore activities and make the most of your campus experience.</p>
    </div>
    <a class="btn btn-primary" href="events.php">Explore Events</a>
</div>

<div class="stats three-stats">
    <div class="stat"><span>📅</span><div><strong><?= count($events) ?></strong><small>Upcoming events</small></div></div>
    <div class="stat"><span>✅</span><div><strong><?= $my_registrations ?></strong><small>My registrations</small></div></div>
    <div class="stat"><span>🎓</span><div><strong><?= e($student['semester']) ?></strong><small>Current semester</small></div></div>
</div>

<div class="section-head"><h2>Upcoming Events</h2><a href="events.php">View all →</a></div>
<div class="cards three">
<?php foreach ($events as $event): ?>
    <article class="event-card">
        <div class="event-date"><?= date('d', strtotime($event['event_date'])) ?><small><?= date('M', strtotime($event['event_date'])) ?></small></div>
        <div class="event-body">
            <h3><?= e($event['title']) ?></h3>
            <p><?= e(mb_strimwidth($event['description'], 0, 100, '...')) ?></p>
            <div class="meta">📍 <?= e($event['venue']) ?></div>
            <div class="meta">🕒 <?= date('h:i A', strtotime($event['event_time'])) ?></div>
            <a class="btn btn-primary btn-sm" href="event.php?id=<?= $event['id'] ?>">View Details</a>
        </div>
    </article>
<?php endforeach; ?>
<?php if (!$events): ?><div class="empty">No upcoming events are available.</div><?php endif; ?>
</div>

<div class="section-head"><h2>Latest Announcements</h2><a href="announcements.php">View all →</a></div>
<div class="announcement-list">
<?php foreach ($announcements as $announcement): ?>
    <article class="announcement">
        <div class="ann-icon">📢</div>
        <div>
            <h3><?= e($announcement['title']) ?></h3>
            <p><?= e($announcement['message']) ?></p>
            <small>Posted by <?= e($announcement['posted_by']) ?> • <?= date('d M Y', strtotime($announcement['created_at'])) ?></small>
        </div>
    </article>
<?php endforeach; ?>
</div>
<?php require 'footer.php'; ?>