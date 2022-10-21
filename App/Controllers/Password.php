<?php

namespace App\Controllers;


use \Core\View;
use App\Models\User;
use \App\Flash;

class Password extends \Core\Controller
{

  public function forgotAction()
  {
    View::renderTemplate('Password/forgot.html');
  }

  public function requestResetAction()
  {
    User::sendPasswordReset($_POST['email']);

    Flash::addMessage('Request has been sent. Please check your email address.',Flash::INFO);
    $this->redirect('/Password/forgot');
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

    if ($user->resetPassword($_POST['password'])) {

      Flash::addMessage('Password successfully changed! You can login with your new password.',Flash::SUCCESS);
      $this->redirect('/');
    }
    else {
      View::renderTemplate('Password/reset.html',[
        'token' => $token,
        'user' => $user
      ]);
    }
  }

  protected function getUserOrExit($token)
  {
    $user = User::findByPasswordReset($token);

    if ($user) {
      return $user;
    } else {
      Flash::addMessage('Password reset link invalid or expired you need to request another one',Flash::DANGER);
      $this->redirect('/Password/forgot');
    }
  }
}
