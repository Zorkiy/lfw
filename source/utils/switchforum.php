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

  $query = "SELECT * FROM $tbl_forums 
            WHERE hide = 'show'
            ORDER BY pos";
  $frm = mysql_query($query);
  if(!$frm)
  {
     throw new ExceptionMySQL(mysql_error(), 
                              $query,
                             "Ошибка при выборке 
                              списка форумов форума");
  }
  if(mysql_num_rows($frm))
  {
    // Если выбран список тем
    if(basename($_SERVER['PHP_SELF']) == 'read.php')
    { 
      // Переменные $id_theme и $id_theme_archive определяются в utils/newpostslist.php
      if($id_theme > $id_theme_archive)
      {
        $action = "index.php";
      }
      else
      {
        $action = "archive.php";
      }
    }
    else
    {
      $action = basename($_SERVER['PHP_SELF']);
    }
  ?>
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class=switchforum>
                <form style="margin: 0px" action=<?= $action; ?> method=get>          
                <nobr><p class=texthelp>Выбрать другой форум<br>
                <select type=text name='id_forum'>
    <?php
    while($forum = mysql_fetch_array($frm))
    {
      // Отображаем выбранный форум
      if($forum['id_forum'] == $id_forum)
      {
         $chk = "selected";
         // Если форум выбран, запоминаем его краткое описание.
         $nameforum = $forum['name'];
         $logo = $forum['logo'];
      }
      else $chk = "";
      echo "<option $chk value=$forum[id_forum]>$forum[name]";
    }
  ?>
  </select>
  <input class=button type=submit value="Перейти">
  <?php
  }
  ?></nobr>
  </td></tr>
  </table>
  </form>