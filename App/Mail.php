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
    $mail->Host = Config::mailHost;
    $mail->Port = Config::mailPort;

    $mail->Username = Config::mailUsername;
    $mail->Password = config::mailPassword;

    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->send();

  }

}