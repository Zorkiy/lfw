<table class=toplinks border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr align="center">
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
              WHERE part = 2 
              ORDER BY pos";
    $res = mysql_query($query);
    if(!$res)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при ссылок");
    }
    if (mysql_num_rows($res))
    {
      while($link = mysql_fetch_array($res))
      {
        echo "<td><a class=menuinfo href='$link[url]'>$link[name]</a></td>";
      }
    }
  ?>  
  </tr>
</table>