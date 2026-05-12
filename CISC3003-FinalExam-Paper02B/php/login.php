<?php
declare(strict_types=1);
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = (string) filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    if ($email === 'admin@cisc3003.local' && $password === 'admin123') {
        $_SESSION['scenario_b_admin'] = $email;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid demo account. Use admin@cisc3003.local / admin123';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B - Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Scenario B - Login (Deliverable)</h1>
        <?php if ($error !== ''): ?>
            <section class="message error"><p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="field-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required>
            </div>
            <div class="field-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p class="links"><a href="index.php">Back to Contact Form</a></p>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
</body>
</html>
