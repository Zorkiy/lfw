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

  // Количество сообщений в теме
  function get_archiv_id()
  {
    // Объявляем переменные с именами таблиц глобальными
    global $tbl_archive_number;

    // Загружаем первичный ключ темы, которая последняя в 
    // архивной таблице
    $query = "SELECT id_theme FROM $tbl_archive_number LIMIT 1";
    $arh = mysql_query($query);
    if(!$arh)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при выборки последней
                                архивной темы");
    }
    if(mysql_num_rows($arh)) return mysql_result($arh, 0);
    else return 0;
  }
?>