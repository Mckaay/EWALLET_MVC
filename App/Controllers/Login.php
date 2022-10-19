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

        if ($user) {
            Auth::login($user);
            $this->redirect('/Menu/index');
        } else {
            View::renderTemplate('Login/login.html', ['login' => $_POST['login']]);
        }
    }

    public function destroyAction()
    {
        Auth::logout();
        $this->redirect('/');
    }
}
