<?php

namespace App\Controllers;

use \Core\View;
use \App\Date;
use \App\Models\Expense;
use \App\Flash;

class Expenses extends Authenticated
{

  protected function before() 
  {
    parent::before();
  }

  public function indexAction()
  {
    View::renderTemplate('Expense/expense.html', [
      'maxDate' => Date::currentDate(),
      'expenseCategories' => Expense::getExpenseCategories(),
      'paymentMethods' => Expense::getPaymentMethods()
    ]);
  }

  public function newAction()
  {

    if (isset($_POST['amount'])) {
      $expense = new Expense($_POST);

      if ($expense->saveExpense()) {
        Flash::addMessage('Expense successfully added!', FLASH::SUCCESS);
        $this->redirect('/expenses/index');
      } else {
        Flash::addMessage('Expense was not added!', FLASH::DANGER);

        View::renderTemplate('Expense/expense.html', [
          'expense' => $expense,
          'maxDate' => Date::currentDate(),
          'expenseCategories' => Expense::getExpenseCategories(),
          'paymentMethods' => Expense::getPaymentMethods()
        ]);

      }
    } else {
      $this->redirect('/expenses/index');
    }
  }
}
