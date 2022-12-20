<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Balance;
use Core\Controller;
use \Core\View;
use \App\Date;
use \App\Models\Expense;
use \App\Flash;

class Expenses extends Controller
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

  public function limitAction():void
  {
      if(empty($_SESSION['user_id'])){
          http_response_code(400);
          echo json_encode(["message" => "You are not authorized!"]);
      }
      else if($_SERVER['REQUEST_METHOD'] !== "GET") {
          $this->respondMethodNotAllowed("GET");
      } else {
          $errors = $this->getValidationErrors(true);
          if(! empty($errors))
          {
              $this->respondUnprocessableEntity($errors);
              return;
          }
          else {
              $result = Expense::getLimitForSelectedExpenseCategory($this->route_params['id']);
              if(empty($result)){
                  $this->respondNotFound($this->route_params['id']);
              }
              else {
                  echo json_encode($result, JSON_NUMERIC_CHECK );
              }
          }
      }
  }
    public function expensesAction():void
    {
        $date = $_GET['date'];
        if(empty($_SESSION['user_id'])){
            http_response_code(400);
            echo json_encode(["message" => "You are not authorized!"]);
        }
        else if($_SERVER['REQUEST_METHOD'] !== "GET") {
            $this->respondMethodNotAllowed("GET");
        } else {
            $errors = $this->getValidationErrors();
            if(! empty($errors))
            {
                $this->respondUnprocessableEntity($errors);
                return;
            }
            else {
                $startMonthDate = date('Y-m-01',strtotime($date));
                $endMonthDate = date('Y-m-t',strtotime($date));
                $result = Balance::getExpensesSpecifiedCategorySum($startMonthDate,$endMonthDate,$this->route_params['id']);
                if(empty($result)){
                    $this->respondNotFound($this->route_params['id']);
                }
                else {
                    echo json_encode($result, JSON_NUMERIC_CHECK );
                }
            }
        }
    }
    private function respondMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }
    private function getValidationErrors(bool $limit = false): array
    {
        $errors = [];
        if (empty($_GET['date']) and $limit === false) {
            $errors[] = "Date is required!";
        }
        if(empty($this->route_params['id'])){
            $errors[] = "Category Id is required!";
        }
        return $errors;
    }
    private function respondUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }
    private function respondNotFound($id): void
    {
        echo json_encode(0);
    }




}
