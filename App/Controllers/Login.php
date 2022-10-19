<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;

class Login extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('Login/login.html');
    }

    public function createAction()
    {
        $user = User::authenticate($_POST['login'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {
            Auth::login($user,$remember_me);
            $this->redirect('/Menu/index');
        } else {
            View::renderTemplate('Login/login.html', ['login' => $_POST['login'], 'remember_me' => $remember_me]);
        }
    }

    public function destroyAction()
    {
        Auth::logout();
        $this->redirect('/');
    }
}
