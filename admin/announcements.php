<?php
$page_title = 'Announcements';
require 'header.php';

$error = '';
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM announcements WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

if (is_post()) {
    verify_csrf();

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $stmt = db()->prepare('DELETE FROM announcements WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'Announcement deleted.');
        redirect('announcements.php');
    }

    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $posted_by = trim($_POST['posted_by'] ?? '');

    if ($title === '' || $message === '' || $posted_by === '') {
        $error = 'All fields are required.';
    } elseif ($id > 0) {
        $stmt = db()->prepare('UPDATE announcements SET title = ?, message = ?, posted_by = ? WHERE id = ?');
        $stmt->execute([$title, $message, $posted_by, $id]);
        flash('success', 'Announcement updated.');
        redirect('announcements.php');
    } else {
        $stmt = db()->prepare('INSERT INTO announcements (title, message, posted_by) VALUES (?, ?, ?)');
        $stmt->execute([$title, $message, $posted_by]);
        flash('success', 'Announcement published.');
        redirect('announcements.php');
    }
}

$success = flash('success');
$items = db()->query('SELECT * FROM announcements ORDER BY created_at DESC')->fetchAll();
$form = $edit ?: ['id' => 0, 'title' => '', 'message' => '', 'posted_by' => 'CampusConnect Admin'];
?>
<div class="section-head"><div><span class="eyebrow">Administration</span><h1>Announcements</h1></div><a class="btn btn-primary" href="announcements.php?action=add">+ New Announcement</a></div>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

<?php if (isset($_GET['action']) || $edit): ?>
<div class="admin-form"><h2><?= $form['id'] ? 'Edit Announcement' : 'New Announcement' ?></h2>
<form method="post" class="form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($form['id']) ?>"><input type="hidden" name="action" value="save"><label>Title *<input name="title" required value="<?= e($form['title']) ?>"></label><label>Message *<textarea name="message" rows="4" required><?= e($form['message']) ?></textarea></label><label>Posted By *<input name="posted_by" required value="<?= e($form['posted_by']) ?>"></label><button class="btn btn-primary" type="submit">Save</button> <a class="btn btn-light" href="announcements.php">Cancel</a></form></div>
<?php endif; ?>

<div class="announcement-list">
<?php foreach ($items as $item): ?><article class="announcement"><div class="ann-icon">📢</div><div class="grow"><h3><?= e($item['title']) ?></h3><p><?= e($item['message']) ?></p><small><?= e($item['posted_by']) ?> • <?= date('d M Y', strtotime($item['created_at'])) ?></small></div><div class="actions"><a class="btn btn-light btn-sm" href="announcements.php?edit=<?= $item['id'] ?>">Edit</a><form class="inline-form" method="post" onsubmit="return confirm('Delete this announcement?')"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $item['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form></div></article><?php endforeach; ?>
<?php if (!$items): ?><div class="empty">No announcements available.</div><?php endif; ?>
</div>
<?php require 'footer.php'; ?>