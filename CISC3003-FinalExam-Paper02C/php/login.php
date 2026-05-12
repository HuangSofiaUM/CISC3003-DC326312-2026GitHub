<?php
declare(strict_types=1);
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = (string) filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    require __DIR__ . '/connect.php';
    $sql = 'SELECT id, full_name, email, password_hash, email_verified_at FROM users WHERE email = ? LIMIT 1';
    $stmt = $mysqli->prepare($sql);
    if ($stmt !== false) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if ($user && password_verify($password, (string) $user['password_hash'])) {
            if ($user['email_verified_at'] === null) {
                $error = 'Please verify your email before login.';
            } else {
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_name'] = (string) $user['full_name'];
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Login query preparation failed.';
    }
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C - Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Login</h1>
        <?php if ($error !== ''): ?>
            <section class="message error"><p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="field-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
            </div>
            <div class="field-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p class="links"><a href="register.php">Create account</a> | <a href="forgot_password.php">Forgot password?</a></p>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
</body>
</html>
