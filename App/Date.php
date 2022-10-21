<?php

namespace App;

class Date
{

  public static function endMonthDate () {
    $date = date("Y-m-t");
    return $date;
  }
  
  public static function startMonthDate () {
    $date = date("Y-m-01");
    return $date;
  }
  
  public static function previousMonthStartDate () {
    $date = date('Y-m-01', strtotime('-1 month'));
    return $date;
  }
  
  public static function previousMonthEndDate () {
    $date = date('Y-m-t', strtotime('-1 month'));
    return $date;
  }
}
