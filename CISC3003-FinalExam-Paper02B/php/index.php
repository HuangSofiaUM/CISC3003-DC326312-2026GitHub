<?php
declare(strict_types=1);
session_start();

$status = $_GET['status'] ?? '';
$flash = $_SESSION['flash'] ?? [];
$old = $_SESSION['old'] ?? ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
unset($_SESSION['flash'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B - Contact Form</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Scenario B: Contact Form + PHPMailer</h1>
        <p>Demonstrates client-side validation, PHPMailer integration, email debugging, and PRG workflow.</p>

        <?php if (!empty($flash['errors'])): ?>
            <section class="message error">
                <h2>Validation Errors</h2>
                <ul>
                    <?php foreach ($flash['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if ($status === 'success'): ?>
            <section class="message success">
                <p>Email sent successfully and request completed with PRG pattern.</p>
            </section>
        <?php elseif ($status === 'mail_error'): ?>
            <section class="message warning">
                <p>Email was not sent. Check SMTP settings or debug output below.</p>
                <?php if (!empty($flash['debug'])): ?>
                    <pre><?= htmlspecialchars((string) $flash['debug'], ENT_QUOTES, 'UTF-8') ?></pre>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <form id="contactForm" action="send_mail.php" method="post" novalidate>
            <div class="field-group">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" required minlength="2" value="<?= htmlspecialchars((string) $old['name'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="field-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required value="<?= htmlspecialchars((string) $old['email'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="field-group">
                <label for="subject">Subject</label>
                <input id="subject" name="subject" type="text" required minlength="3" value="<?= htmlspecialchars((string) $old['subject'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="field-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required minlength="10"><?= htmlspecialchars((string) $old['message'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <button type="submit">Send Email</button>
        </form>

        <p class="links">
            <a href="login.php">Admin Login</a> |
            <a href="register.php">Register (Deliverable)</a> |
            <a href="dashboard.php">Dashboard</a>
        </p>
    </main>

    <footer>
        CISC3003 Web Programming: Huang Sofia + dc326312 + 2026
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
