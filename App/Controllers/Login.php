<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

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
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/Menu/index', true, 303);
            exit;
        }
        else {
            View::renderTemplate('Login/login.html');
        }
    }
}
