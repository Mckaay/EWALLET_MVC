<?php

namespace App\Controllers;

use \Core\View;
use App\Models\User;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Register extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Register/index.html');
    }

    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {
            $user->sendActivationEmail();
            $this->redirect('/Register/success');
        } else {
            View::renderTemplate('Register/index.html', ['user' => $user]);
        }
    }

    public function successAction()
    {
        View::renderTemplate('Register/success.html');
    }
}
