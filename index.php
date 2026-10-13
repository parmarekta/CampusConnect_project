<?php
require_once 'config.php';

if (!empty($_SESSION['student_id'])) {
    redirect('student/dashboard.php');
}

if (!empty($_SESSION['admin_id'])) {
    redirect('admin/dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Student Activity & Event Management</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar">
    <div class="container nav-inner">
        <a class="brand" href="index.php">Campus<span>Connect</span></a>
        <div class="nav-actions">
            <a href="login.php">Student Login</a>
            <a class="btn btn-primary btn-sm" href="register.php">Register</a>
        </div>
    </div>
</nav>

<main>
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <span class="eyebrow">Smart Campus Platform</span>
                <h1>Connect. Participate. <span>Grow.</span></h1>
                <p>
                    CampusConnect helps students discover college activities, register for events,
                    and stay updated with campus announcements.
                </p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="register.php">Create Student Account</a>
                    <a class="btn btn-outline" href="login.php">Student Login</a>
                </div>
            </div>

            <div class="hero-card">
                <div class="hero-icon">🎓</div>
                <h2>Everything in one place</h2>
                <p>Events, registrations, announcements and student activity history.</p>
                <div class="mini-stats">
                    <div><strong>24×7</strong><small>Access</small></div>
                    <div><strong>Online</strong><small>Registration</small></div>
                    <div><strong>Easy</strong><small>To Use</small></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <span class="eyebrow">Why CampusConnect?</span>
            <h2>Built for student participation</h2>
            <div class="cards three">
                <article class="feature-card"><div class="feature-icon">📅</div><h3>Discover Events</h3><p>View workshops, competitions, cultural activities and technical events.</p></article>
                <article class="feature-card"><div class="feature-icon">📝</div><h3>Register Online</h3><p>Register for events quickly and keep a record of your participation.</p></article>
                <article class="feature-card"><div class="feature-icon">📢</div><h3>Stay Updated</h3><p>Read important campus announcements from the administration.</p></article>
            </div>
        </div>
    </section>
</main>

<footer><div class="container">© 2026 CampusConnect — Student Activity & Event Management System</div></footer>
</body>
</html>