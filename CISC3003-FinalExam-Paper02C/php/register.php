<?php
declare(strict_types=1);
session_start();

$errors = [];
$success = '';
$debug = '';
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * ==========================================
     * C.02 Server-side Validation
     * ==========================================
     */
    $fullName = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_UNSAFE_RAW));
    $email = (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = (string) filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    $confirmPassword = (string) filter_input(INPUT_POST, 'confirm_password', FILTER_UNSAFE_RAW);

    if ($fullName === '' || mb_strlen($fullName) < 3) {
        $errors[] = 'Full name must be at least 3 characters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (mb_strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Password confirmation does not match.';
    }

    /**
     * ==========================================
     * C.03 Save signup data to MySQL by PHP
     * ==========================================
     */
    if (count($errors) === 0) {
        require __DIR__ . '/connect.php';
        $verificationToken = bin2hex(random_bytes(24));
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (full_name, email, password_hash, email_verification_token) VALUES (?, ?, ?, ?)';
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            $errors[] = 'Failed to prepare SQL statement.';
        } else {
            $stmt->bind_param('ssss', $fullName, $email, $passwordHash, $verificationToken);
            if ($stmt->execute()) {
                require_once __DIR__ . '/mail_helper.php';

                $verifyLink = 'http://localhost/CISC3003-FinalExam-Paper02C/php/verify_email.php?token=' . urlencode($verificationToken);
                [$mailOk, $mailMessage] = sendMailMessage(
                    $email,
                    $fullName,
                    'Verify your account',
                    '<p>Please confirm your email by clicking this link:</p><p><a href="' . htmlspecialchars($verifyLink, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($verifyLink, ENT_QUOTES, 'UTF-8') . '</a></p>',
                    'Verify your account: ' . $verifyLink
                );

                $success = 'Registration successful. Please verify your email before login.';
                if (!$mailOk) {
                    $debug = 'Mail debug: ' . $mailMessage . ' | Verification URL: ' . $verifyLink;
                }

                $fullName = '';
                $email = '';
            } else {
                $errors[] = 'Registration failed: ' . $stmt->error;
            }
            $stmt->close();
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
    <title>Scenario C - Register</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Sign Up</h1>
        <p>Scenario C.01 + C.02 + C.03 + C.08</p>

        <?php if (count($errors) > 0): ?>
            <section class="message error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <section class="message success"><p><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php endif; ?>

        <?php if ($debug !== ''): ?>
            <section class="message warning"><p><?= htmlspecialchars($debug, ENT_QUOTES, 'UTF-8') ?></p></section>
        <?php endif; ?>

        <form id="registerForm" action="register.php" method="post" novalidate>
            <div class="field-group">
                <label for="full_name">Full Name</label>
                <input id="full_name" name="full_name" type="text" required minlength="3" value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="field-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
                <small id="emailCheckResult"></small>
            </div>
            <div class="field-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required minlength="8">
            </div>
            <div class="field-group">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password" required minlength="8">
            </div>
            <button type="submit">Create Account</button>
        </form>
        <p class="links"><a href="login.php">Already have account? Login</a></p>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
    <script src="../js/script.js"></script>
</body>
</html>
