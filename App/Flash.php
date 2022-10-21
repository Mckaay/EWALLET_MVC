<?php


namespace App;



class Flash
{

  const SUCCESS = 'alert alert-success';

  const WARNING = 'alert alert-warning';

  const INFO = 'alert alert-info';

  const DANGER = "alert alert-danger";

  public static function addMessage($message, $type = 'alert alert-success')
  {
    if (!isset($_SESSION['flash_notifications'])) {
      $_SESSION['flash_notifications'] = [];
    }

    $_SESSION['flash_notifications'][] = [
      'body' => $message,
      'type' => $type
    ];
  }

  public static function getMessages()
  {
    if (isset($_SESSION['flash_notifications'])) {
      $messages = $_SESSION['flash_notifications'];
      unset($_SESSION['flash_notifications']);

      return $messages;
    }
  }
}
