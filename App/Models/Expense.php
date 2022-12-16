<?php

namespace App\Models;


use PDO;
use \App\Date;

class Expense extends \Core\Model
{

  public $errors = [];

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

  public static function getExpenseCategories()
  {
    $sql = 'SELECT id,name,max_limit FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();

    $expensesCategories = $db->prepare($sql);

    $expensesCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $expensesCategories->execute();

    return $expensesCategories->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getPaymentMethods()
  {
    $sql = 'SELECT id,name FROM payment_methods_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();

    $expensesCategories = $db->prepare($sql);

    $expensesCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $expensesCategories->execute();

    return $expensesCategories->fetchAll(PDO::FETCH_ASSOC);
  }

  public function saveExpense()
  {
    $this->validate();

    if (empty($this->errors)) {
      $sql = 'INSERT INTO expenses VALUES (NULL,:user_id,:category,:payment,:amount,:date,:notes)';
      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindValue(':category', $this->category, PDO::PARAM_INT);
      $stmt->bindValue(':payment', $this->payment, PDO::PARAM_INT);
      $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
      $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
      $stmt->bindValue(':notes', $this->notes, PDO::PARAM_STR);

      return $stmt->execute();
    }
    return false;
  }

  protected function validate()
  {
    if (!$this->checkMaxDate()) {
      $this->errors['maxDate'] = 'Please type in valid date';
    }
    if (!$this->checkMinDate()) {
      $this->errors['minDate'] = 'Please type in valid date';
    }
    if (!$this->checkAmount()) {
      $this->errors['amount'] = 'Amount must be positive number';
    }
    if (!$this->checkNotes()) {
      $this->errors['notes'] = 'Notes can only contain letters and numbers';
    }
  }

  protected function checkMaxDate()
  {
    $date = strtotime($this->date);
    $maxDate = strtotime(Date::currentDate());

    if ($date > $maxDate) {
      return false;
    }

    return true;
  }

  protected function checkMinDate()
  {
    $date = strtotime($this->date);
    $minDate = strtotime(Date::minDate);

    if ($date < $minDate) {
      return false;
    }

    return true;
  }


  protected function checkAmount()
  {
    if ($this->amount <= 0) {
      return false;
    }

    return true;
  }

  protected function checkNotes()
  {
    if($this->notes == ''){
      return true;
    }
    if (!ctype_alnum($this->notes)) {
      return false;
    }

    return true;
  }

  public static function deleteExpensesWithGivenCategoryId($expenseCategoryId)
  {
    $sql = 'DELETE FROM expenses WHERE expense_category_assigned_to_user_id = :categoryId';

    $db = static::getDB();

    $deleteExpensesWithGivenCategory = $db->prepare($sql);

    $deleteExpensesWithGivenCategory->bindValue(':categoryId', $expenseCategoryId, PDO::PARAM_INT);

    $deleteExpensesWithGivenCategory ->execute();
  }

  public static function deleteExpenseCategory($expenseCategoryId)
  {
    $sql = 'DELETE FROM expenses_category_assigned_to_users WHERE id = :categoryId';

    $db = static::getDB();

    $deleteExpenseCategory = $db->prepare($sql);

    $deleteExpenseCategory->bindValue(':categoryId', $expenseCategoryId, PDO::PARAM_INT);

    Expense::deleteExpensesWithGivenCategoryId($expenseCategoryId);

    return $deleteExpenseCategory->execute();
  }

  
  public static function updateExpenseCategory($expenseCategoryId, $newCategoryName, $categoryLimit)
  {
    $sql = 'UPDATE expenses_category_assigned_to_users
            SET name = :newCategoryName,
            max_limit = :max_limit
            WHERE id = :expenseCategoryId';

    $db = static::getDB();

    $updateExpenseCategory = $db->prepare($sql);
    $updateExpenseCategory->bindValue(':newCategoryName',$newCategoryName,PDO::PARAM_STR);
    $updateExpenseCategory->bindValue(':expenseCategoryId',$expenseCategoryId,PDO::PARAM_STR);
    $updateExpenseCategory->bindValue(':max_limit',$categoryLimit,PDO::PARAM_STR);

    return $updateExpenseCategory->execute();
  }


  public static function checkIfCategoryAlreadyExists($newExpenseCategoryName)
  {
    $sql = 'SELECT * FROM expenses_category_assigned_to_users
              WHERE user_id = :user_id AND name = :newExpenseCategoryName';

    $db = static::getDB();
    $checkIfCategoryAlreadyExists = $db->prepare($sql);

    $checkIfCategoryAlreadyExists->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $checkIfCategoryAlreadyExists->bindValue(':newExpenseCategoryName', $newExpenseCategoryName, PDO::PARAM_STR);


    $checkIfCategoryAlreadyExists->execute();

    $result = $checkIfCategoryAlreadyExists->fetchAll(PDO::FETCH_ASSOC);

    if (empty($result)) {
      return true;
    } else {
      return false;
    }
  }

  public static function addExpenseCategory($newExpenseCategoryName)
  {

    if (Expense::checkIfCategoryAlreadyExists($newExpenseCategoryName) == false) {
      return false;
    } else {
      $sql = 'INSERT INTO expenses_category_assigned_to_users
            VALUES (NULL,:user_id,:newCategoryName,0)';

      $db = static::getDB();

      $addExpenseCategory = $db->prepare($sql);
      $addExpenseCategory->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $addExpenseCategory->bindValue(':newCategoryName', $newExpenseCategoryName, PDO::PARAM_STR);

      return $addExpenseCategory->execute();
    }
  }


  public static function deletePaymentMethod($paymentMethodId)
  {
    $sql = 'DELETE FROM payment_methods_assigned_to_users WHERE id = :paymentMethodId';

    $db = static::getDB();

    $deletePaymentMethod = $db->prepare($sql);

    $deletePaymentMethod->bindValue(':paymentMethodId', $paymentMethodId, PDO::PARAM_INT);

    return $deletePaymentMethod->execute();
  }

  public static function updatePaymentMethod($paymentMethodId,$newPaymentMethodName)
  {
    $sql = 'UPDATE payment_methods_assigned_to_users
            SET name = :newPaymentMethodName
            WHERE id = :paymentMethodId';

    $db = static::getDB();

    $updatePaymentMethod = $db->prepare($sql);

    $updatePaymentMethod->bindValue(':paymentMethodId', $paymentMethodId, PDO::PARAM_INT);
    $updatePaymentMethod->bindValue(':newPaymentMethodName', $newPaymentMethodName, PDO::PARAM_STR);

    return $updatePaymentMethod->execute();
  }

  public static function addPaymentMethod($newPaymentMethodName)
  {
    $sql = 'INSERT INTO payment_methods_assigned_to_users
            VALUES(NULL,:user_id, :newPaymentMethodName)';

    $db = static::getDB();

    $addPaymentMethod = $db->prepare($sql);

    $addPaymentMethod->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $addPaymentMethod->bindValue(':newPaymentMethodName', $newPaymentMethodName, PDO::PARAM_STR);

    return $addPaymentMethod->execute();
  }

  
  public static function deleteAllExpenses(){

    $sql = 'DELETE FROM expenses
            WHERE id = :user_id';

    $db = static::getDB();

    $deleteAllExpenses = $db->prepare($sql);
    $deleteAllExpenses->bindValue(':user_id', $_SESSION['user_id'],PDO::PARAM_INT);

    return $deleteAllExpenses->execute();
}


}
