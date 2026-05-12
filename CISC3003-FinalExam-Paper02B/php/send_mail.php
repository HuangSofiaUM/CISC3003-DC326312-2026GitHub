<?php
declare(strict_types=1);
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

/**
 * ==========================================
 * Scenario B - Server Validation
 * ==========================================
 */
$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW));
$email = (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = trim((string) filter_input(INPUT_POST, 'subject', FILTER_UNSAFE_RAW));
$message = trim((string) filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW));

$errors = [];
if ($name === '' || mb_strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}
if ($subject === '' || mb_strlen($subject) < 3) {
    $errors[] = 'Subject must be at least 3 characters.';
}
if ($message === '' || mb_strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters.';
}

$_SESSION['old'] = [
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'message' => $message,
];

if (count($errors) > 0) {
    $_SESSION['flash'] = ['errors' => $errors];
    header('Location: index.php');
    exit;
}

/**
 * ==========================================
 * Scenario B - Save to DB by Prepared Statement
 * ==========================================
 */
require __DIR__ . '/connect.php';
$sql = 'INSERT INTO contact_logs (name, email, subject, message_body) VALUES (?, ?, ?, ?)';
$stmt = $mysqli->prepare($sql);
if ($stmt !== false) {
    $stmt->bind_param('ssss', $name, $email, $subject, $message);
    $stmt->execute();
    $stmt->close();
}
$mysqli->close();

/**
 * ==========================================
 * Scenario B - PHPMailer + Debugging + PRG
 * ==========================================
 */
$debugOutput = '';
$mailSent = false;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@163.com';
        $mail->Password = 'your_smtp_authorization_code';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('your_email@163.com', 'CISC3003 Contact Form');
        $mail->addAddress('your_email@163.com', 'Site Owner');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = '[Scenario B] ' . $subject;
        $mail->Body = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        $mail->AltBody = $message;

        $mail->send();
        $mailSent = true;
    } catch (Throwable $e) {
        $debugOutput = $e->getMessage();
    }
} else {
    $debugOutput = 'PHPMailer not installed. Run: composer require phpmailer/phpmailer';
}

$_SESSION['flash'] = ['debug' => $debugOutput];
unset($_SESSION['old']);

if ($mailSent) {
    header('Location: index.php?status=success');
} else {
    header('Location: index.php?status=mail_error');
}
exit;
?>
