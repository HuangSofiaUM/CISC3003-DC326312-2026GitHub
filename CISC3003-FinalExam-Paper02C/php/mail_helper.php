<?php
declare(strict_types=1);

/**
 * ==========================================
 * Scenario C - PHPMailer Helper
 * ==========================================
 */
function sendMailMessage(string $toEmail, string $toName, string $subject, string $htmlBody, string $plainBody): array
{
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
    }

    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        return [false, 'PHPMailer not installed. Run: composer require phpmailer/phpmailer'];
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@163.com';
        $mail->Password = 'your_smtp_authorization_code';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('your_email@163.com', 'CISC3003 Scenario C');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $plainBody;
        $mail->send();
        return [true, 'Mail sent'];
    } catch (Throwable $e) {
        return [false, $e->getMessage()];
    }
}
?>
