<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

class Menu extends Authenticated
{
    protected function before()
	{	
		parent::before();
	}


    public function indexAction()
    {
        View::renderTemplate('menu/menu.html');
    }
}
