<?php


namespace App\Models;

use PDO;

class Income extends \Core\Model
{

  public static function getIncomeCategories()
  {    
    $sql = 'SELECT id,name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();

    $incomeCategories = $db->prepare($sql);

    $incomeCategories->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);

    $incomeCategories->execute();

    return $incomeCategories->fetchAll(PDO::FETCH_ASSOC);
  }
}
