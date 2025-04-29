<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

function createMailer($to, $toName, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'baranggay.sta.lucia@gmail.com';
        $mail->Password   = 'hxgo sabg qxls ovpi'; // Make sure this is the correct app-specific password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('baranggay.sta.lucia@gmail.com', 'Bayanihan Hub');
        $mail->addAddress($to, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send the email
        if ($mail->send()) {
            return true; // Email sent successfully
        } else {
            return false; // Failed to send email
        }

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return "Error sending email: " . $e->getMessage();
    }
}


