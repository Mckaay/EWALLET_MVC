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
    View::renderTemplate('Balance/balance.html',[
      'incomes' => Balance::getUserIncomes(Date::startMonthDate(),Date::endMonthDate()),
      'expenses' => Balance::getUserExpenses(Date::startMonthDate(),Date::endMonthDate()),
      'incomesAmount' => Balance::getIncomesSum(Date::startMonthDate(),Date::endMonthDate()),
      'expensesAmount' => Balance::getExpensesSum(Date::startMonthDate(),Date::endMonthDate()),
      'incomesCategoriesSum' => Balance::getIncomesCategoriesSum(Date::startMonthDate(),Date::endMonthDate()),
      'expensesCategoriesSum' => Balance::getExpensesCategoriesSum(Date::startMonthDate(),Date::endMonthDate())
    ]);
  }

}
