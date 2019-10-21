<ul style="margin: 0px 0px 0px 20px; padding: 0px 0px 0px 0px">
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

  $query = "SELECT * FROM $tbl_links 
            WHERE part = 1 AND
                  hide = 'show'
            ORDER BY pos DESC";
  $res = mysql_query($query);
  if(!$res)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "Ошибка при выборке 
                             названия форума");
  }
  if (mysql_num_rows($res))
  {
    while($link = mysql_fetch_array($res))
    {
      echo "<li><a class=menuinfo href='".$link["url"]."'>".$link["name"]."</a></li>";
    }
    echo "<li><a class=menuinfo href='newslist.php?id_forum=$id_forum'>Далее...</a></li>";
  }
?>  
</ul>