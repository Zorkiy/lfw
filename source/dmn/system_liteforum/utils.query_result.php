<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) Кузнецов М.В., Симдянов И.В.
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

  function query_result($query)
  {
    $tot = mysql_query($query);
    if(!$tot)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка выполнения запроса");
    }
    if(mysql_num_rows($tot))
    {
      return @mysql_result($tot, 0);
    }
    else
    {
      return false;
    }
  }
?>