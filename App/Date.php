<?php

namespace App;

class Date
{

  const minDate = '2000-01-01';

  public static function endMonthDate () {
    return date("Y-m-t");
  }
  
  public static function startMonthDate () {
    return date("Y-m-01");
  }
  
  public static function previousMonthStartDate () {
    return date('Y-m-01', strtotime('-1 month'));
  }
  
  public static function previousMonthEndDate () {
    return date('Y-m-t', strtotime('-1 month'));
  }

  public static function currentDate(){
    return date('Y-m-d');
  }
}
