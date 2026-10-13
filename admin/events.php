<?php
$page_title = 'Manage Events';
require 'header.php';

$error = '';
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM events WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

if (is_post()) {
    verify_csrf();

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $stmt = db()->prepare('DELETE FROM events WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'Event deleted successfully.');
        redirect('events.php');
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $organizer = trim($_POST['organizer'] ?? '');
    $max_seats = (int) ($_POST['max_seats'] ?? 0);

    if ($title === '' || $description === '' || $event_date === '' || $event_time === '' || $venue === '' || $organizer === '' || $max_seats < 1) {
        $error = 'All fields are required and maximum seats must be greater than zero.';
    } elseif ($id > 0) {
        $stmt = db()->prepare('UPDATE events SET title = ?, description = ?, event_date = ?, event_time = ?, venue = ?, organizer = ?, max_seats = ? WHERE id = ?');
        $stmt->execute([$title, $description, $event_date, $event_time, $venue, $organizer, $max_seats, $id]);
        flash('success', 'Event updated successfully.');
        redirect('events.php');
    } else {
        $stmt = db()->prepare('INSERT INTO events (title, description, event_date, event_time, venue, organizer, max_seats) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$title, $description, $event_date, $event_time, $venue, $organizer, $max_seats]);
        flash('success', 'Event added successfully.');
        redirect('events.php');
    }
}

$success = flash('success');
$events = db()->query(
    "SELECT e.*, COUNT(CASE WHEN r.status = 'Registered' THEN 1 END) AS registrations
     FROM events e
     LEFT JOIN event_registrations r ON r.event_id = e.id
     GROUP BY e.id
     ORDER BY e.event_date DESC, e.event_time DESC"
)->fetchAll();

$form = $edit ?: ['id' => 0, 'title' => '', 'description' => '', 'event_date' => '', 'event_time' => '', 'venue' => '', 'organizer' => '', 'max_seats' => 100];
?>
<div class="section-head"><div><span class="eyebrow">Administration</span><h1>Manage Events</h1></div><a class="btn btn-primary" href="events.php?action=add">+ Add Event</a></div>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

<?php if (isset($_GET['action']) || $edit): ?>
<div class="admin-form">
    <h2><?= $form['id'] ? 'Edit Event' : 'Add New Event' ?></h2>
    <form method="post" class="form">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= e($form['id']) ?>">
        <input type="hidden" name="action" value="save">
        <div class="form-grid">
            <label>Event Title *<input name="title" required value="<?= e($form['title']) ?>"></label>
            <label>Organizer *<input name="organizer" required value="<?= e($form['organizer']) ?>"></label>
            <label>Date *<input type="date" name="event_date" required value="<?= e($form['event_date']) ?>"></label>
            <label>Time *<input type="time" name="event_time" required value="<?= e($form['event_time']) ?>"></label>
            <label>Venue *<input name="venue" required value="<?= e($form['venue']) ?>"></label>
            <label>Maximum Seats *<input type="number" min="1" name="max_seats" required value="<?= e($form['max_seats']) ?>"></label>
        </div>
        <label>Description *<textarea name="description" rows="4" required><?= e($form['description']) ?></textarea></label>
        <button class="btn btn-primary" type="submit"><?= $form['id'] ? 'Update Event' : 'Add Event' ?></button>
        <a class="btn btn-light" href="events.php">Cancel</a>
    </form>
</div>
<?php endif; ?>

<div class="table-wrap"><table><thead><tr><th>Event</th><th>Date</th><th>Venue</th><th>Registrations</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($events as $event): ?>
<tr><td><strong><?= e($event['title']) ?></strong><br><small><?= e($event['organizer']) ?></small></td><td><?= date('d M Y', strtotime($event['event_date'])) ?></td><td><?= e($event['venue']) ?></td><td><?= $event['registrations'] ?> / <?= $event['max_seats'] ?></td><td><a class="btn btn-light btn-sm" href="events.php?edit=<?= $event['id'] ?>">Edit</a><form class="inline-form" method="post" onsubmit="return confirm('Delete this event?')"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $event['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form></td></tr>
<?php endforeach; ?>
<?php if (!$events): ?><tr><td colspan="5" class="empty">No events available.</td></tr><?php endif; ?>
</tbody></table></div>
<?php require 'footer.php'; ?>