<?php

namespace App\Controllers;


use \Core\View;

use App\Models\User;

class Password extends \Core\Controller
{

  public function forgotAction()
  {
    View::renderTemplate('Password/forgot.html');
  }

  public function requestResetAction()
  {
    User::sendPasswordReset($_POST['email']);

    View::renderTemplate('Password/requested.html');
  }

  public function resetAction()
  {
    $token = $this->route_params['token'];
    
    $user = $this->getUserOrExit($token);

    View::renderTemplate('Password/reset.html', ['token' => $token]);

  }

  public function resetPasswordAction()
  {
    $token = $_POST['token'];

    $user = $this->getUserOrExit($token);

    echo "reset user's password here";

  }

  protected function getUserOrExit($token)
  {
    $user = User::findByPasswordReset($token);

    if ($user) {
      return $user;
    } else {
      View::renderTemplate('Password/tokenexpired.html');
    }
  }
}
