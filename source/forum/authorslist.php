<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  // Бешкенадзе А.Г. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // Подключаем SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Функции для работы со временем
  require_once("../utils/utils.time.php");
  // Функции для работы с сообщениями
  require_once("../utils/utils.posts.php");
  // Подключаем постраничную навигацию
  require_once("../utils/utils.pager.php");

  try
  {
    // Включаем "шапку" страницы
    $nameaction = "Список участников форума";
    include "../utils/topforumaction.php";
    // Извлекаем параметры из строки запроса
    $id_forum = intval($_GET['id_forum']);
    $page = intval($_GET['page']);
    ?>
    <p class=linkbackbig><a href="index.php?id_forum=<?php echo $id_forum; ?>">Вернуться назад</a></p>         
    <table class="tablen" width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="silver">
    <tr><td class=tableheadern><p class="fieldname">Участник&nbsp;форума</td>
    <td class=tableheadern><p class="fieldname"><a href=authorslist.php?id_forum=<?php echo $id_forum; ?>&page=<?php echo $page; ?> title="Сортировать по количеству сообщений">Количество&nbsp;сообщений</a></td>
    <td class=tableheadern><p class="fieldname"><a href=authorslist.php?id_forum=<?php echo $id_forum; ?>&page=<?php echo $page; ?>&order=time title="Сортировать по дате последнего посещения">Последнее&nbsp;посещение</a></td>
    <td class=tableheadern><p class="fieldname">Статус</td></tr>
    <?php
    // Проверяем по какому полю производится 
    // сортировка
    $ord = "themes DESC";
    $orde = "";
    if($_GET['order'] == "time")
    {
      $ord = "time DESC";
      $orde = "time";
    }
    // Отображаем авторов по $pnumber штук
    $pnumber = 25;
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    $query = "SELECT * FROM $tbl_authors
              ORDER BY $ord 
              LIMIT $begin, $pnumber";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении к таблице авторов");
    }
    if(mysql_num_rows($ath) > 0)
    {
      // Выводим список первых $pnumber авторов
      while($author = mysql_fetch_array($ath))
      {
        // Проверяем статус автора
        $status = "";
        if($author['statususer'] == 'moderator') $status = "Модератор";
        if($author['statususer'] == 'admin') $status = "Администратор";
        echo "<tr class=trtablen><td><p class=authorreg><nobr><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$author[id_author]>".htmlspecialchars($author['name'])."</a></nobr></td>
            <td><p class=texthelp align=center>".$author['themes']."</td>
            <td><p class=texthelp align=center>".convertdate($author['time'])."</td><td><p align=center>$status</p></td></tr>";
      }
      // Общее количество позиций
      $query = "SELECT COUNT(*) FROM $tbl_authors";
      $tot = mysql_query($query);
      if(!$tot)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении к таблице авторов");
      }
      $total = mysql_result($tot, 0);

      // Выводим ссылки на других авторов
      $page_link = 1;
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber)-$number != 0) $number++;
      echo "<tr><td class=bottomtablen colspan=4><p class=texthelp>";
      pager($page, $total, $pnumber, $page_link, "&id_forum=$id_forum&order=$orde");
      echo "</td></tr>";
    }
    echo "</table>";
    // Выводим завершение страницы
    include "../utils/bottomforumaction.php"; 
  }
  catch(ExceptionObject $exc) 
  {
    require_once("exception_object_debug.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>