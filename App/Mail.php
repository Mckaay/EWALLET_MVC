<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use App\Config;

class Mail
{
  public static function send($to,$subject,$message)
  {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Host = $_ENV["mailHost"];
    $mail->Port = $_ENV["mailPort"];

    $mail->Username = $_ENV["mailUsername"];
    $mail->Password = $_ENV["mailPassword"];

    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->isHTML(true);

    $mail->send();

  }

}