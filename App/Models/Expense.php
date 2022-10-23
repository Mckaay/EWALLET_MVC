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
    $sql = 'SELECT id,name FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

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
    if (!ctype_alnum($this->notes)) {
      return false;
    }

    return true;
  }
}
