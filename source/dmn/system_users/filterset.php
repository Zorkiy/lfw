<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) Кузнецов М.В., Симдянов И.В.
  // PHP. Практика создания Web-сайтов
  // IT-студия SoftTime 
  // http://www.softtime.ru   - портал по Web-программированию
  // http://www.softtime.biz  - коммерческие услуги
  // http://www.softtime.mobi - мобильные проекты
  // http://www.softtime.org  - некоммерческие проекты
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  // Устанавливаем временной фильтр
  $begin = "";
  if(!empty($_POST['chk_begin']))
  {
    $begin = mktime($_POST['b_date_hour'], 
                    $_POST['b_date_minute'], 
                    0, 
                    $_POST['b_date_month'], 
                    $_POST['b_date_day'], 
                    $_POST['b_date_year']);
  }
  $end = "";
  if(!empty($_POST['chk_end']))
  {
    $end = mktime($_POST['e_date_hour'], 
                  $_POST['e_date_minute'], 
                  0, 
                  $_POST['e_date_month'], 
                  $_POST['e_date_day'], 
                  $_POST['e_date_year']);
  }
  $url = "index.php?page=$_GET[page]&begin_date=$begin&end_date=$end";
  header("Location: $url");
?>