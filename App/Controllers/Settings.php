<?php


namespace App\Controllers;

use Core\View;
use App\Models\Income;
use App\Models\Expense;
use App\Models\User;
use \App\Flash;
use \App\Auth;


class Settings extends Authenticated
{

  protected function before()
  {
    parent::before();
  }


  public function indexAction()
  {
    View::renderTemplate('Settings/settings.html', [
      'incomeCategories' => Income::getIncomeCategories(),
      'expenseCategories' => Expense::getExpenseCategories(),
      'paymentMethods' => Expense::getPaymentMethods()
    ]);
  }

  public function deleteIncomeCategoryAction()
  {
    if (isset($_POST['newCategoryName'])) {
      if (Income::deleteIncomeCategory($_POST['categoryId'])) {
        Flash::addMessage('Income category successfully deleted!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function updateIncomeCategoryAction()
  {
    if (isset($_POST['newCategoryName'])) {
      if (Income::updateIncomeCategory($_POST['categoryId'], $_POST['newCategoryName'])) {
        Flash::addMessage('Income category successfully updated!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function addIncomeCategoryAction()
  {
    if (isset($_POST['newIncomeCategoryName'])) {
      if (Income::addIncomeCategory($_POST['newIncomeCategoryName'])) {
        Flash::addMessage('Income category successfully added!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Category Already Exists!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function deleteExpenseCategoryAction()
  {
    if (isset($_POST['newCategoryName'])) {
      if (Expense::deleteExpenseCategory($_POST['categoryId'])) {
        Flash::addMessage('Expense category successfully deleted!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function updateExpenseCategoryAction()
  {
    if (isset($_POST['newCategoryName'])) {
      if (Expense::updateExpenseCategory($_POST['categoryId'], $_POST['newCategoryName'], $_POST['newCategoryLimit'])) {
        Flash::addMessage('Expense category successfully updated!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER); 
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }


  public function addExpenseCategoryAction()
  {
    if (isset($_POST['newExpenseCategoryName'])) {
      if (Expense::addExpenseCategory($_POST['newExpenseCategoryName'])) {
        Flash::addMessage('Expense category successfully added!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Category Already Exists!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function deletePaymentMethodAction()
  {
    if (isset($_POST['changePayment'])) {
      if (Expense::deletePaymentMethod($_POST['categoryId'])) {
        Flash::addMessage('Payment Method successfully deleted!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function updatePaymentMethodAction()
  {
    if (isset($_POST['changePayment'])) {
      if (Expense::updatePaymentMethod($_POST['categoryId'], $_POST['changePayment'])) {
        Flash::addMessage('Payment Method successfully updated!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }


  public function addPaymentMethodAction()
  {
    if (isset($_POST['newPaymentMethodName'])) {
      if (Expense::addPaymentMethod($_POST['newPaymentMethodName'])) {
        Flash::addMessage('Payment Method successfully added!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Payment Method Already Exists!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function deleteAccountAction()
  {
    if (isset($_POST['submit'])) {
      if (User::deleteAccount()) {
        Auth::logout();
        $this->redirect('/login/show-logout-message');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

  public function resetTransactionsAction(){
    if (isset($_POST['submit'])) {
      if (Income::deleteAllIncomes() and Expense::deleteAllExpenses()) {
        Flash::addMessage('Transactions successfully reseted!', FLASH::SUCCESS);
        $this->redirect('/settings');
      } else {
        Flash::addMessage('Something went wrong!', FLASH::DANGER);
        $this->redirect('/settings');
      }
    } else {
      $this->redirect('/settings');
    }
  }

}


