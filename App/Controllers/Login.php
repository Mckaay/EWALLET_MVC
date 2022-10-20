<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Mail;

class Login extends \Core\Controller
{

    public function indexAction()
    {
        if(Auth::getUser()){
            $this->redirect('/menu/index');
        } else {
            View::renderTemplate('Login/login.html');
        }
    }

    public function createAction()
    {
        if(isset($_POST['login'])){

            $user = User::authenticate($_POST['login'], $_POST['password']);
            $remember_me = isset($_POST['remember_me']);

            if ($user) {
                Auth::login($user, $remember_me);
                $this->redirect('/Menu/index');
            } else {
                View::renderTemplate('Login/login.html', ['login' => $_POST['login'], 'remember_me' => $remember_me]);
            }
        }
    }

    public function destroyAction()
    {
        Auth::logout();
        $this->redirect('/');
    }
}
