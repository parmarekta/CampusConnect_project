<?php
$page_title = 'My Events';
require 'header.php';

if (is_post()) {
    verify_csrf();

    $registration_id = (int) ($_POST['registration_id'] ?? 0);
    $stmt = db()->prepare("UPDATE event_registrations SET status = 'Cancelled' WHERE id = ? AND student_id = ? AND status = 'Registered'");
    $stmt->execute([$registration_id, $student['id']]);

    flash('success', 'Event registration cancelled.');
    redirect('my_events.php');
}

$success = flash('success');
$stmt = db()->prepare(
    'SELECT e.*, r.id AS registration_id, r.status, r.registered_at
     FROM event_registrations r
     INNER JOIN events e ON e.id = r.event_id
     WHERE r.student_id = ?
     ORDER BY e.event_date DESC, e.event_time DESC'
);
$stmt->execute([$student['id']]);
$items = $stmt->fetchAll();
?>
<div class="page-title">
    <div><span class="eyebrow">Your Activity</span><h1>My Registered Events</h1><p>Participation history for <?= e($student['student_name']) ?>.</p></div>
</div>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>

<div class="table-wrap">
<table>
<thead><tr><th>Event</th><th>Date</th><th>Venue</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php foreach ($items as $item): ?>
<tr>
    <td><strong><?= e($item['title']) ?></strong></td>
    <td><?= date('d M Y', strtotime($item['event_date'])) ?></td>
    <td><?= e($item['venue']) ?></td>
    <td><span class="pill <?= $item['status'] === 'Registered' ? 'pill-success' : 'pill-danger' ?>"><?= e($item['status']) ?></span></td>
    <td>
        <?php if ($item['status'] === 'Registered' && strtotime($item['event_date']) >= strtotime(date('Y-m-d'))): ?>
        <form method="post" onsubmit="return confirm('Cancel this registration?')">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="registration_id" value="<?= $item['registration_id'] ?>">
            <button class="btn btn-danger btn-sm" type="submit">Cancel</button>
        </form>
        <?php else: ?>—<?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
<?php if (!$items): ?><tr><td colspan="5" class="empty">You have not registered for any event yet.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php require 'footer.php'; ?>