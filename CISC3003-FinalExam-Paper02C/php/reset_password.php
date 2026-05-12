<?php
declare(strict_types=1);

$token = (string) ($_GET['token'] ?? $_POST['token'] ?? '');
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    $confirmPassword = (string) filter_input(INPUT_POST, 'confirm_password', FILTER_UNSAFE_RAW);

    if ($token === '') {
        $error = 'Invalid reset token.';
    } elseif (mb_strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Password confirmation does not match.';
    } else {
        require __DIR__ . '/connect.php';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires_at = NULL WHERE reset_token = ? AND reset_expires_at > NOW()';
        $stmt = $mysqli->prepare($sql);
        if ($stmt !== false) {
            $stmt->bind_param('ss', $passwordHash, $token);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $message = 'Password has been reset. You can now login.';
            } else {
                $error = 'Reset token invalid or expired.';
            }
            $stmt->close();
        } else {
            $error = 'Reset query failed.';
        }
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Reset Password</h1>
        <?php if ($error !== ''): ?>
            <section class="message error"><p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php endif; ?>
        <?php if ($message !== ''): ?>
            <section class="message success"><p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p></section>
            <p><a href="login.php">Go to login</a></p>
        <?php else: ?>
            <form method="post" action="reset_password.php">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
                <div class="field-group">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" required minlength="8">
                </div>
                <div class="field-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required minlength="8">
                </div>
                <button type="submit">Update Password</button>
            </form>
        <?php endif; ?>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
</body>
</html>
