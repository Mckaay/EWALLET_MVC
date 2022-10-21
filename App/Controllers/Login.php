<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Mail;
use \App\Flash;

class Login extends \Core\Controller
{

    public function indexAction()
    {
        if (Auth::getUser()) {
            $this->redirect('/menu/index');
        } else {
            View::renderTemplate('Login/login.html');
        }
    }

    public function createAction()
    {
        if (isset($_POST['login'])) {

            $user = User::authenticate($_POST['login'], $_POST['password']);
            $remember_me = isset($_POST['remember_me']);

            if ($user) {
                Auth::login($user, $remember_me);

                Flash::addMessage('Login successful');
                $this->redirect('/Menu/index');
            } else {
                Flash::addMessage('Login unsuccessful, please try again',Flash::DANGER);
                View::renderTemplate('Login/login.html', ['login' => $_POST['login'], 'remember_me' => $remember_me]);
            }
        }
    }

    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/login/show-logout-message');
    }

    public function showLogoutMessageAction()
    {
        Flash::addMessage('Logout successful');
        $this->redirect('/');
    }
}
