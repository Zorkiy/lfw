<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  function get_settings()
  {
    // Объявляем переменные с именами таблиц глобальными
    global $tbl_settings;

    // Извлекам настройки форума выставленные администратором
    $query = "SELECT * FROM $tbl_settings";
    $set = mysql_query($query);
    if(!$set)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при выборке 
                                настроек форума");
    }
    if(mysql_num_rows($set)) return mysql_fetch_array($set);
    else false;
  }
?>
