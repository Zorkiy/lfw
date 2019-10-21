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

  // Извлекаем запись из таблицы forums для текущего форума
  $_GET['id_forum'] = intval($_GET['id_forum']);

  $query = "SELECT * FROM $tbl_forums 
            WHERE hide = 'show' AND
                  id_forum = $_GET[id_forum]
            ORDER BY pos";
  $frm = mysql_query($query);
  if(!$frm)
  {
     throw new ExceptionMySQL(mysql_error(), 
                              $query,
                             "Ошибка при выборке 
                              названия форума");
  }
  if(mysql_num_rows($frm))
  {
    $forum = mysql_fetch_array($frm);
    $nameforum = $forum['name'];
    $logo = $forum['logo'];
    echo "<h1 class=nameforum><nobr>$nameforum</nobr></h1>";
  }
?>