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
    $startDate = Date::startMonthDate();
    $endDate = Date::endMonthDate();

    View::renderTemplate('Balance/balance.html', [
      'incomes' => Balance::getUserIncomes($startDate, $endDate),
      'expenses' => Balance::getUserExpenses($startDate, $endDate),
      'incomesAmount' => Balance::getIncomesSum($startDate, $endDate),
      'expensesAmount' => Balance::getExpensesSum($startDate, $endDate),
      'incomesCategoriesSum' => Balance::getIncomesCategoriesSum($startDate, $endDate),
      'expensesCategoriesSum' => Balance::getExpensesCategoriesSum($startDate, $endDate),
      'maxDate' => Date::endMonthDate()
    ]);
  }


  public function previousAction()
  {
    $startDate = Date::previousMonthStartDate();
    $endDate = Date::previousMonthEndDate();

    View::renderTemplate('Balance/balance.html', [
      'incomes' => Balance::getUserIncomes($startDate, $endDate),
      'expenses' => Balance::getUserExpenses($startDate, $endDate),
      'incomesAmount' => Balance::getIncomesSum($startDate, $endDate),
      'expensesAmount' => Balance::getExpensesSum($startDate, $endDate),
      'incomesCategoriesSum' => Balance::getIncomesCategoriesSum($startDate, $endDate),
      'expensesCategoriesSum' => Balance::getExpensesCategoriesSum($startDate, $endDate),
      'maxDate' => Date::endMonthDate()
    ]);
  }

  public function periodAction()
  {

    if (isset($_POST['firstDate'])) {
      $startDate = $_POST['firstDate'];
      $endDate = $_POST['secondDate'];
      View::renderTemplate('Balance/balance.html', [
        'incomes' => Balance::getUserIncomes($startDate, $endDate),
        'expenses' => Balance::getUserExpenses($startDate, $endDate),
        'incomesAmount' => Balance::getIncomesSum($startDate, $endDate),
        'expensesAmount' => Balance::getExpensesSum($startDate, $endDate),
        'incomesCategoriesSum' => Balance::getIncomesCategoriesSum($startDate, $endDate),
        'expensesCategoriesSum' => Balance::getExpensesCategoriesSum($startDate, $endDate),
        'maxDate' => Date::endMonthDate()
      ]);
    }
    else {
      $this->redirect('/balances/index');
    }
  }
}
