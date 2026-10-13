<?php
require_once '../config.php';
require_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? 'Admin Panel') ?> | CampusConnect</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav class="navbar admin-nav">
    <div class="container nav-inner">
        <a class="brand" href="dashboard.php">Campus<span>Connect</span> <small>ADMIN</small></a>
        <div class="nav-user"><span><?= e($_SESSION['admin_name'] ?? 'Administrator') ?></span><a class="btn btn-light btn-sm" href="logout.php">Logout</a></div>
    </div>
</nav>
<div class="container page">
<nav class="tabs">
    <a href="dashboard.php">Dashboard</a><a href="events.php">Events</a><a href="students.php">Students</a><a href="announcements.php">Announcements</a>
</nav>