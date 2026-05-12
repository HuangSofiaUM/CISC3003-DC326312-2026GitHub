<?php
declare(strict_types=1);
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C - Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <main class="container">
        <h1>User Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars((string) $_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?>.</p>
        <p class="services">
            <a href="index.php">Home</a>
            <a href="forgot_password.php">Reset Password</a>
            <a href="logout.php">Logout</a>
        </p>
        <section class="card">
            <h2>My Services</h2>
            <ul>
                <li>Profile Center</li>
                <li>Message Center</li>
                <li>Security Settings</li>
            </ul>
        </section>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
</body>
</html>
