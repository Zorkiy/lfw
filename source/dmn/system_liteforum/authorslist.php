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

  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");
  // Постраничная навигация
  require_once("../utils/utils.pager.php");
  // Выполнение SQL-запроса
  require_once("utils.query_result.php");
  // Подключаем функции для работы со временем
  require_once("../../utils/utils.time.php");


  try
  {
    $title = 'Пользователи';  
    $pageinfo = '<p class=help>Информация о пользователях форума</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center>Участник&nbsp;форума</td>
      <td align=center>Количество&nbsp;сообщений</td>
      <td align=center>Последнее&nbsp;посещение</td>
      <td colspan=2 align=center>Действия</td>
      <td colspan=3 align=center>Статус</td>
    </tr>
    <?php
    // Отображаем авторов по $pnumber штук
    $pnumber = 25;
    // Извлекаем параметры из строки запроса
    $page = intval($_GET['page']);
    $id_forum = intval($_GET['id_forum']);

    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    $query = "SELECT * FROM $tbl_authors
              ORDER BY themes DESC 
              LIMIT $begin, $pnumber";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обращения к пользователям");
    }
    if(mysql_num_rows($ath))
    {
      // Выводим список первых $pnumber авторов
      while($author = mysql_fetch_array($ath))
      {
        $user  = "<a href=athsetuser.php?id_author=$author[id_author]&page=$page
                     title='Назначить посетителю статус обычного участника форума'>Посетитель</a>";
        $moder = "<a href=athsetmoder.php?id_author=$author[id_author]&page=$page
                     title='Назначить посетителю статус модератора форума'>Модератор</a>";
        $admin = "<a href=athsetadmin.php?id_author=$author[id_author]&page=$page
                     title='Назначить посетителю статус администратора форума'>Администратор</a>";
        $userhead = "";
        $moderhead = "";
        $adminhead = "";
        if($author['statususer'] == '') $userhead = "class=header";
        if($author['statususer'] == 'moderator') $moderhead = "class=header";
        if($author['statususer'] == 'admin') $adminhead = "class=header";
        echo "<tr>
                <td><a href=author.php?id_author=$author[id_author]>".htmlspecialchars($author['name'], ENT_QUOTES)."</a></td>
                <td align=center>$author[themes]</td>
                <td align=center>".convertdate($author['time'])."</td>
                <td align=center><a href=athedit.php?id_author=$author[id_author]&page=$page>Редактировать</a></td>
                <td align=center><a href=# onClick=\"delete_position('athdel.php?id_author=$author[id_author]&page=$page','Вы действительно хотите удалить пользователя?');\">Удалить</a></td>
                <td align=center $userhead>$user</td>
                <td align=center $moderhead>$moder</td>
                <td align=center $adminhead>$admin</td>
             </tr>";
      }
      // Выводим ссылки на других авторов
      $query = "SELECT COUNT(*) FROM $tbl_authors";
      $total = query_result($query);
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber)-$number != 0) $number++;

      echo "<tr><td class=bottomtablen colspan=7>";
      // Выводим ссылки на другие страницы
      pager($page, 
            $total, 
            $pnumber, 
            3, 
            "");
      echo "</td></tr>";
    }
    echo "</table>";
    // Выводим завершение страницы
    require_once("../utils/bottom.php");
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }
?>