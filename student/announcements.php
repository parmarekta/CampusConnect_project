<?php
$page_title = 'Announcements';
require 'header.php';

$items = db()->query('SELECT * FROM announcements ORDER BY created_at DESC')->fetchAll();
?>
<div class="page-title"><div><span class="eyebrow">Campus Updates</span><h1>Announcements</h1><p>Important updates for students.</p></div></div>
<div class="announcement-list">
<?php foreach ($items as $announcement): ?>
    <article class="announcement large">
        <div class="ann-icon">📢</div>
        <div>
            <h3><?= e($announcement['title']) ?></h3>
            <p><?= nl2br(e($announcement['message'])) ?></p>
            <small>Posted by <?= e($announcement['posted_by']) ?> • <?= date('d M Y, h:i A', strtotime($announcement['created_at'])) ?></small>
        </div>
    </article>
<?php endforeach; ?>
<?php if (!$items): ?><div class="empty">No announcements available.</div><?php endif; ?>
</div>
<?php require 'footer.php'; ?>