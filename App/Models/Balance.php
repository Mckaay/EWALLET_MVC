<?php

namespace App\Models;


use PDO;
use \App\Date;

class Balance extends \Core\Model
{

  public static function getUserIncomes($firstDate, $secondDate)
  {
    $sql = 'SELECT cat.name,inc.amount FROM
            incomes_category_assigned_to_users AS cat,incomes AS inc
            WHERE inc.user_id = :user_id
            AND cat.id = inc.income_category_assigned_to_user_id
            AND cat.user_id = inc.user_id
            AND date_of_income BETWEEN :first_date AND :second_date
            ORDER BY inc.amount DESC';

    $db = static::getDB();

    $userIncomes = $db->prepare($sql);

    $userIncomes->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $userIncomes->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
    $userIncomes->bindValue(':second_date', $secondDate, PDO::PARAM_STR);

    $userIncomes->execute();

    return $userIncomes->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getUserExpenses($firstDate, $secondDate): array|bool
  {
    $sql = 'SELECT cat.name,ex.amount FROM
    expenses_category_assigned_to_users AS cat,expenses AS ex
    WHERE ex.user_id = :user_id
    AND cat.id = ex.expense_category_assigned_to_user_id
    AND cat.user_id = ex.user_id
    AND date_of_expense BETWEEN :first_date AND :second_date
    ORDER BY ex.amount DESC';

    $db = static::getDB();

    $userExpenses = $db->prepare($sql);

    $userExpenses->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $userExpenses->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
    $userExpenses->bindValue(':second_date', $secondDate, PDO::PARAM_STR);

    $userExpenses->execute();

    return $userExpenses->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getIncomesSum($firstDate, $secondDate)
  {
    $sql = 'SELECT SUM(inc.amount) FROM
    incomes_category_assigned_to_users AS cat,incomes AS inc
    WHERE inc.user_id = :user_id
    AND cat.id = inc.income_category_assigned_to_user_id
    AND cat.user_id = inc.user_id
    AND date_of_income BETWEEN :first_date AND :second_date';

    $db = static::getDB();

    $userIncomes = $db->prepare($sql);

    $userIncomes->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $userIncomes->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
    $userIncomes->bindValue(':second_date', $secondDate, PDO::PARAM_STR);

    $userIncomes->execute();

    $sumOfIncomes = $userIncomes->fetch();

    return $sumOfIncomes["SUM(inc.amount)"];
  }

  public static function getExpensesSum($firstDate,$secondDate)
  {
    $sql = 'SELECT SUM(ex.amount) FROM
    expenses_category_assigned_to_users AS cat,expenses AS ex
    WHERE ex.user_id = :user_id
    AND cat.id = ex.expense_category_assigned_to_user_id
    AND cat.user_id = ex.user_id
    AND date_of_expense BETWEEN :first_date AND :second_date';

    $db = static::getDB();

    $userExpenses = $db->prepare($sql);

    $userExpenses->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $userExpenses->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
    $userExpenses->bindValue(':second_date', $secondDate, PDO::PARAM_STR);

    $userExpenses->execute();

    $sumOfExpenses = $userExpenses->fetch();

    return $sumOfExpenses["SUM(ex.amount)"];
  }

  public static function getIncomesCategoriesSum($firstDate, $secondDate){
    $sql = 'SELECT cat.name,SUM(inc.amount) FROM
    incomes_category_assigned_to_users AS cat,incomes AS inc
    WHERE inc.user_id = :user_id
    AND cat.id = inc.income_category_assigned_to_user_id
    AND cat.user_id = inc.user_id
    AND date_of_income BETWEEN :first_date AND :second_date
    GROUP BY cat.name';

    $db = static::getDB();

    $userIncomes = $db->prepare($sql);

    $userIncomes->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $userIncomes->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
    $userIncomes->bindValue(':second_date', $secondDate, PDO::PARAM_STR);

    $userIncomes->execute();

    return $userIncomes->fetchAll(PDO::FETCH_ASSOC);

  }

  public static function getExpensesCategoriesSum($firstDate, $secondDate){

    $sql = 'SELECT cat.name,SUM(ex.amount) FROM
    expenses_category_assigned_to_users AS cat,expenses AS ex
    WHERE ex.user_id = :user_id
    AND cat.id = ex.expense_category_assigned_to_user_id
    AND cat.user_id = ex.user_id
    AND date_of_expense BETWEEN :first_date AND :second_date
    GROUP BY cat.name';

    $db = static::getDB();

    $expenseCategoriesSumQuery = $db->prepare($sql);

    $expenseCategoriesSumQuery->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $expenseCategoriesSumQuery->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
    $expenseCategoriesSumQuery->bindValue(':second_date', $secondDate, PDO::PARAM_STR);

    $expenseCategoriesSumQuery->execute();

    return $expenseCategoriesSumQuery->fetchAll(PDO::FETCH_ASSOC);
  }

    public static function getExpensesSpecifiedCategorySum(string $firstDate,string $secondDate,string $expenseCategoryId): array|bool
    {
        $sql = 'SELECT cat.name,SUM(ex.amount) FROM
    expenses_category_assigned_to_users AS cat,expenses AS ex
    WHERE ex.user_id = :user_id
    AND cat.id = ex.expense_category_assigned_to_user_id
    AND cat.user_id = ex.user_id AND cat.id = :expenseCategoryId
    AND date_of_expense BETWEEN :first_date AND :second_date
    GROUP BY cat.name';

        $db = static::getDB();

        $expenseCategoriesSumQuery = $db->prepare($sql);

        $expenseCategoriesSumQuery->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategoriesSumQuery->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $expenseCategoriesSumQuery->bindValue(':second_date', $secondDate, PDO::PARAM_STR);
        $expenseCategoriesSumQuery->bindValue(':expenseCategoryId', $expenseCategoryId, PDO::PARAM_INT);

        $expenseCategoriesSumQuery->execute();

        return $expenseCategoriesSumQuery->fetch(PDO::FETCH_ASSOC);
    }



}
