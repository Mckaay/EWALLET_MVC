<?php

namespace App\Controllers;

use \Core\View;
use \App\Date;
use \App\Models\Balance;
use \App\Flash;

class Balances extends Authenticated
{

  protected function before() 
  {
    parent::before();
  }

  public function indexAction()
  {
    View::renderTemplate('Balance/balance.html');
  }

}
