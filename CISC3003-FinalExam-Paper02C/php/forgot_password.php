<?php
declare(strict_types=1);
session_start();

$message = '';
$debug = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        require __DIR__ . '/connect.php';
        $token = bin2hex(random_bytes(24));

        $sql = 'UPDATE users SET reset_token = ?, reset_expires_at = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE email = ?';
        $stmt = $mysqli->prepare($sql);
        if ($stmt !== false) {
            $stmt->bind_param('ss', $token, $email);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                require_once __DIR__ . '/mail_helper.php';
                $resetLink = 'http://localhost/CISC3003-FinalExam-Paper02C/php/reset_password.php?token=' . urlencode($token);
                [$mailOk, $mailMessage] = sendMailMessage(
                    $email,
                    $email,
                    'Password Reset Request',
                    '<p>Reset your password by this link:</p><p><a href="' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '</a></p>',
                    'Reset link: ' . $resetLink
                );
                $message = 'If your email exists, reset instructions have been sent.';
                if (!$mailOk) {
                    $debug = 'Mail debug: ' . $mailMessage . ' | Reset URL: ' . $resetLink;
                }
            } else {
                $message = 'If your email exists, reset instructions have been sent.';
            }
            $stmt->close();
        }
        $mysqli->close();
    } else {
        $message = 'Please enter a valid email.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Forgot Password</h1>
        <?php if ($message !== ''): ?>
            <section class="message success"><p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php endif; ?>
        <?php if ($debug !== ''): ?>
            <section class="message warning"><p><?= htmlspecialchars($debug, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php endif; ?>
        <form method="post" action="forgot_password.php">
            <div class="field-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
            </div>
            <button type="submit">Send Reset Email</button>
        </form>
        <p class="links"><a href="login.php">Back to login</a></p>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
</body>
</html>
