<?php
$page_title = 'Event Details';
require 'header.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare(
    "SELECT e.*,
            (SELECT COUNT(*) FROM event_registrations r WHERE r.event_id = e.id AND r.status = 'Registered') AS registered_count
     FROM events e
     WHERE e.id = ?"
);
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    echo '<div class="alert danger">Event not found.</div>';
    require 'footer.php';
    exit;
}

$stmt = db()->prepare('SELECT * FROM event_registrations WHERE event_id = ? AND student_id = ?');
$stmt->execute([$id, $student['id']]);
$registration = $stmt->fetch();

$error = '';
$success = flash('success');

if (is_post()) {
    verify_csrf();

    if ($registration && $registration['status'] === 'Registered') {
        $error = 'You are already registered for this event.';
    } elseif (strtotime($event['event_date']) < strtotime(date('Y-m-d'))) {
        $error = 'Registration is closed because this event has already passed.';
    } elseif ((int) $event['registered_count'] >= (int) $event['max_seats']) {
        $error = 'Sorry, this event is full.';
    } else {
        if ($registration) {
            $stmt = db()->prepare("UPDATE event_registrations SET status = 'Registered', registered_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$registration['id']]);
        } else {
            $stmt = db()->prepare("INSERT INTO event_registrations (event_id, student_id, status) VALUES (?, ?, 'Registered')");
            $stmt->execute([$id, $student['id']]);
        }

        flash('success', 'You have successfully registered for this event.');
        redirect('event.php?id=' . $id);
    }
}
?>
<div class="detail-card">
    <div class="detail-date"><strong><?= date('d', strtotime($event['event_date'])) ?></strong><span><?= date('M Y', strtotime($event['event_date'])) ?></span></div>
    <div class="detail-content">
        <span class="eyebrow">Campus Event</span>
        <h1><?= e($event['title']) ?></h1>
        <p class="lead"><?= e($event['description']) ?></p>

        <div class="detail-grid">
            <div>📅 <strong>Date</strong><br><?= date('d M Y', strtotime($event['event_date'])) ?></div>
            <div>🕒 <strong>Time</strong><br><?= date('h:i A', strtotime($event['event_time'])) ?></div>
            <div>📍 <strong>Venue</strong><br><?= e($event['venue']) ?></div>
            <div>👤 <strong>Organizer</strong><br><?= e($event['organizer']) ?></div>
        </div>

        <?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

        <?php if ($registration && $registration['status'] === 'Registered'): ?>
            <div class="registered-box">✓ You are registered for this event.</div>
        <?php elseif (strtotime($event['event_date']) < strtotime(date('Y-m-d'))): ?>
            <div class="alert danger">Registration is closed because this event has passed.</div>
        <?php elseif ((int) $event['registered_count'] >= (int) $event['max_seats']): ?>
            <div class="alert danger">This event is currently full.</div>
        <?php else: ?>
            <form method="post">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <button class="btn btn-primary" type="submit">Register for this Event</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php require 'footer.php'; ?>