<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

//Load Composer's autoloader
require './../vendor/autoload.php'; //vendor/autoload.php';

class EmailDao
{

    public function sendEmailApp($dados)
    {

        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = 'smtp.mailtrap.io'; //EMAIL_HOST; //Set the SMTP server to send through
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = 'c67b26603c1c38'; //APP_EMAIL; //SMTP username
            $mail->Password = 'fd880ac7622385'; //APP_EMAIL_PASS; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = 587; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($dados['from_email'], $dados['from_name']);
            $mail->addAddress($dados['recipient_email'], $dados['recipient_name']); //Add a recipient
            //  $mail->addAddress('marcosgalvaotecnologia@gmail.com'); //Name is optional
            //  $mail->addReplyTo('info@example.com', 'Information');
            //  $mail->addCC('cc@example.com');
            //  $mail->addBCC('bcc@example.com');

            //Attachments
            //  $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
            //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $dados['subject'];
            $mail->Body = $dados['message_html'];
            $mail->AltBody = $dados['message'];
            $mail->send();
            return true;
        } catch (Exception $e) {
            // echo "A mensagem não foi enviada. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

    }

}
