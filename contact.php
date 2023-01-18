<?php
require_once('./cfg.php');

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail()
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bagno294@gmail.com';
        $mail->Password   = 'uapw lkch khbe oouy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setLanguage('pl', './PHPMailer/language');
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($_POST['email'], $_POST['imie'] . ' ' . $_POST['nazwisko']. ' ');
        $mail->addAddress('bagno294@gmail.com');
        $mail->addReplyTo($_POST['email'], $_POST['imie'] . ' ' . $_POST['nazwisko']);

        $mail->isHTML(false);
        $mail->Subject = 'Jakis temat';
        $mail->Body    = $_POST['ms'];
        $mail->AltBody = $_POST['ms'];

        $mail->send();
        return '<span>Wiadomość wysłana.</span>';
    }
    
    catch (Exception $e) {
        return '<span>Nie wysłano wiadomości. <br />' . $mail->ErrorInfo . '</span>';
    }
}

?>