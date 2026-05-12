<?php
declare(strict_types=1);

$token = (string) ($_GET['token'] ?? '');
$message = 'Invalid verification link.';

if ($token !== '') {
    require __DIR__ . '/connect.php';
    $sql = 'UPDATE users SET email_verified_at = NOW(), email_verification_token = NULL WHERE email_verification_token = ?';
    $stmt = $mysqli->prepare($sql);
    if ($stmt !== false) {
        $stmt->bind_param('s', $token);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $message = 'Email verified successfully. You can now login.';
        } else {
            $message = 'Verification token expired or not found.';
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Email Verification</h1>
        <section class="message success"><p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p></section>
        <p><a href="login.php">Go to Login</a></p>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
</body>
</html>
