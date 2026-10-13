<?php
require_once '../config.php';
require_student();

$student = current_student();
if (!$student) {
    redirect('../logout.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? 'Student Dashboard') ?> | CampusConnect</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav class="navbar">
    <div class="container nav-inner">
        <a class="brand" href="dashboard.php">Campus<span>Connect</span></a>
        <div class="nav-user">
            <span>Welcome, <strong><?= e($student['student_name']) ?></strong></span>
            <a href="profile.php">Profile</a>
            <a class="btn btn-light btn-sm" href="../logout.php">Logout</a>
        </div>
    </div>
</nav>
<div class="container page">
    <nav class="tabs">
        <a href="dashboard.php">Dashboard</a>
        <a href="events.php">Events</a>
        <a href="my_events.php">My Events</a>
        <a href="announcements.php">Announcements</a>
        <a href="profile.php">Profile</a>
    </nav>