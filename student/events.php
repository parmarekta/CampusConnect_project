<?php
$page_title = 'Events';
require 'header.php';

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = db()->prepare(
        "SELECT e.*,
                (SELECT COUNT(*) FROM event_registrations r WHERE r.event_id = e.id AND r.status = 'Registered') AS registered_count
         FROM events e
         WHERE e.event_date >= CURDATE()
           AND (e.title LIKE ? OR e.venue LIKE ? OR e.organizer LIKE ?)
         ORDER BY e.event_date, e.event_time"
    );
    $like = '%' . $search . '%';
    $stmt->execute([$like, $like, $like]);
    $events = $stmt->fetchAll();
} else {
    $events = db()->query(
        "SELECT e.*,
                (SELECT COUNT(*) FROM event_registrations r WHERE r.event_id = e.id AND r.status = 'Registered') AS registered_count
         FROM events e
         WHERE e.event_date >= CURDATE()
         ORDER BY e.event_date, e.event_time"
    )->fetchAll();
}
?>
<div class="page-title">
    <div>
        <span class="eyebrow">Campus Activities</span>
        <h1>Upcoming Events</h1>
        <p>Find an activity that interests you, <?= e($student['student_name']) ?>.</p>
    </div>
</div>

<form class="searchbar" method="get">
    <input name="search" placeholder="Search events, venue or organizer..." value="<?= e($search) ?>">
    <button class="btn btn-primary" type="submit">Search</button>
</form>

<div class="cards two">
<?php foreach ($events as $event):
    $full = (int) $event['registered_count'] >= (int) $event['max_seats'];
    $remaining = (int) $event['max_seats'] - (int) $event['registered_count'];
?>
    <article class="event-card">
        <div class="event-date"><?= date('d', strtotime($event['event_date'])) ?><small><?= date('M Y', strtotime($event['event_date'])) ?></small></div>
        <div class="event-body">
            <div class="event-top"><h3><?= e($event['title']) ?></h3><span class="pill <?= $full ? 'pill-danger' : 'pill-success' ?>"><?= $full ? 'Full' : $remaining . ' seats left' ?></span></div>
            <p><?= e($event['description']) ?></p>
            <div class="meta">📍 <?= e($event['venue']) ?> • 🕒 <?= date('h:i A', strtotime($event['event_time'])) ?></div>
            <div class="meta">👤 <?= e($event['organizer']) ?></div>
            <a class="btn btn-primary btn-sm" href="event.php?id=<?= $event['id'] ?>">View Details</a>
        </div>
    </article>
<?php endforeach; ?>
<?php if (!$events): ?><div class="empty">No events found.</div><?php endif; ?>
</div>
<?php require 'footer.php'; ?>