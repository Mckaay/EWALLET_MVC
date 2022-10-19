<?php

namespace App\Controllers;

use \Core\View;

class Login extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('Login/login.html');
    }
}
