<?php

namespace App\Controllers;

use \App\Models\User;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class Account extends \Core\Controller
{

  public function validateEmailAction()
  {
    $is_valid = ! User::emailExists($_GET['email']);

    header('Content-Type: application/json');
    echo json_encode($is_valid);
  }

  public function validateUsernameAction(){

    

    $is_valid = ! User::usernameExists($_GET['login']);
    header('Content-Type: application/json');
    echo json_encode($is_valid);
  }

}