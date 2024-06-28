<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendEmail($email, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pro.hussein.kteish@gmail.com';
        $mail->Password   = 'udlqjivnhxnsvkey';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('pro.hussein.kteish@gmail.com', 'Pro.Hussein Kteish');
        $mail->addAddress($email, 'Dear user');

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        // HTML email template
        $htmlContent = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 100%;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                    background-color: #007bff;
                    color: #ffffff;
                    border-radius: 10px 10px 0 0;
                }
                .header h1 {
                    margin: 0;
                }
                .content {
                    padding: 20px;
                }
                .content p {
                    font-size: 16px;
                    line-height: 1.5;
                    color: #333333;
                }
                .footer {
                    text-align: center;
                    padding: 20px;
                    background-color: #f4f4f4;
                    color: #777777;
                    border-radius: 0 0 10px 10px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Welcome to my website</h1>
                </div>
                <div class="content">
                    <p>Dear user,</p>
                    <p>' . $message . '</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' Pro.Hussein Kteish. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $htmlContent;

        //Send email
        $mail->send();
        if($subject!="Password Changed"){//In case of change password from settings.
        header("Location: verify.php");
        }
    } catch (Exception $e) {
        echo "Message could not be sent check your connection. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
