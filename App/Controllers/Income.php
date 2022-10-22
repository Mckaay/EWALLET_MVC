<?php

namespace App\Controllers;

use \Core\View;
use \App\Date;

class Income extends Authenticated
{
  
  protected function before()
  {
    parent::before();
  }

  public function indexAction()
  {

    View::renderTemplate('Income/income.html',['maxDate' => Date::currentDate()]);
  }
}
