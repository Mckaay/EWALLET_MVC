<?php

namespace App\Controllers;

use \Core\View;
use App\Models\User;
use \App\Flash;

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
            Flash::addMessage('User successfully registered! Please check email for activation link.',Flash::SUCCESS);
            $this->redirect('/register/index');
        } else {
            View::renderTemplate('Register/index.html', ['user' => $user]);
        }
    }

    public function activateAction()
    {
        User::activate($this->route_params['token']);

        Flash::addMessage('User successfully activated! You can log in and use our website!',Flash::SUCCESS);
        $this->redirect('/');
    }
}
