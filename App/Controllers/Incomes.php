<?php

namespace App\Controllers;

use \Core\View;
use \App\Date;
use \App\Models\Income;
use \App\Flash;

class Incomes extends Authenticated
{

  protected function before() 
  {
    parent::before();
  }

  public function indexAction()
  {
    View::renderTemplate('Income/income.html', [
      'maxDate' => Date::currentDate(),
      'incomeCategories' => Income::getIncomeCategories()
    ]);
  }

  public function newAction()
  {

    if (isset($_POST['amount'])) {
      $income = new Income($_POST);

      if ($income->saveIncome()) {
        Flash::addMessage('Income successfully added!', FLASH::SUCCESS);
        $this->redirect('/incomes/index');
      } else {
        Flash::addMessage('Income was not added!', FLASH::DANGER);

        View::renderTemplate('Income/income.html', [
          'income' => $income,
          'maxDate' => Date::currentDate(),
          'incomeCategories' => Income::getIncomeCategories()
        ]);

      }
    } else {
      $this->redirect('/incomes/index');
    }
  }
}
