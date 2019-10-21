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
  // Настройки форума
  require_once("../../utils/utils.settings.php");
  // Обработка тем 
  require_once("../../utils/utils.posts.php");

  try
  {
    // Извлекаем информацию из строки запроса
    $id_forum = intval($_GET['id_forum']);
    if(empty($id_forum)) $id_forum = 1;

    // Извлекаем параметры форума
    $query = "SELECT name FROM $tbl_forums
              WHERE id_forum = $id_forum";
    $name = query_result($query);

    $title = 'Модерирование форума '.$name;  
    $pageinfo = '<p class=help>На данной странице можно 
    скрыть, отобразить, закрыть, отредактировать тему 
    или отдельное сообщение</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");

    // Формируем ссылку для перехода к другому разделу
    $query = "SELECT * FROM $tbl_forums 
              ORDER BY pos";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обращения к разделам форума");
    }
    if(mysql_num_rows($frm))
    {
      ?>
      <table width=100% 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">
      <tr class="header" align="center" valign="middle">
      <?php
      while($forum = mysql_fetch_array($frm))
      {
        echo "<td><a href=themes.php?id_forum=$forum[id_forum]>$forum[name]</a></td>";
      }
      echo "</tr></table><br><br>";
    }

    $settings = get_settings();
    // Помещаем число выводимых на странице тем
    // в переменную $pnumber
    $pnumber = $settings['number_themes'];
    // Извлекаем параметры из строки запроса
    $page = intval($_GET['page']);
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;

    $query = "SELECT * FROM $tbl_themes 
              WHERE id_forum = $id_forum 
              ORDER BY `time` DESC
              LIMIT $begin, $pnumber";
    $thm = mysql_query($query);
    if(!$thm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обращения к темам форума");
    }
    if(mysql_num_rows($thm))
    {
    // Начало таблица с темами
    ?>
    <table class="table" 
           width="100%" 
           border="0" 
           cellpadding="0" 
           cellspacing="0">
    <tr class="header">
      <td class=headtable align=center>Cообщений</td>
      <td class=headtable align=center>Название темы</td>
      <td width=70 class=headtable align=center>Автор</td>
      <td class=headtable align=center colspan=4>Действия</td>
    </tr>
    <?php
      while($themes = mysql_fetch_array($thm))
      {
        /////////////////////////////////////////////////////
        // Блок вывода списка тем
        /////////////////////////////////////////////////////
        // Считаем количество сообщений в данной теме.
        $query = "SELECT COUNT(*) FROM $tbl_posts
                  WHERE id_theme = $themes[id_theme]";
        $posts = query_result($query);
        // Предварительно обрабатываем угловые скобки и ентеры
        $name = theme_work_up($themes['name']);
        // Количество сообщений в теме
        echo "<tr><td align=center width=50>$posts</td>";
        // Название
        echo "<td><a href=posts.php?id_forum=$id_forum&id_theme=$themes[id_theme]&page=$page>$name</a></td>"; 
        // Автор
        $author = htmlspecialchars($themes['author']);
        if($themes['id_author'] != 0)
        {
          echo "<td><a href='author.php?id_forum=$id_forum&id_author=$themes[id_author]'>$author</a></td>";
        }
        else echo "<td>$author</td>"; 
        // Предоставляем возможность поправить название темы
        $edit_theme = "<a href=thmedit.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>Редактировать</a>";
        echo "<td width=100 align=center>$edit_theme</td>";
        // Предоставляем возможность скрыть, отобразить или закрыть тему
        $show_theme = "<a href=thmshow.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>Доступно</a>";
        $hide_theme = "<a href=thmhide.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>Скрыто</a>";
        $lock_theme = "<a href=thmlock.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>Закрыто</a>";
        // Проверяем статус темы
        if($themes['hide'] == 'show') $show = "class=header";
        else $show = "";
        if($themes['hide'] == 'hide') $hide = "class=header";
        else $hide = "";
        if($themes['hide'] == 'lock') $lock = "class=header";
        else $lock = "";
        echo "<td $show width=100 align=center 
                  title='Сделать тему доступной для просмотра'>$show_theme</td>"; // Тема доступна
        echo "<td $hide width=100 align=center 
                  title='Сделать тему недоступной для просмотра'>$hide_theme</td>"; // Тема скрыта
        echo "<td $lock width=100 align=center 
                  title='Запретить добавление новых сообщений'>$lock_theme</td></tr>"; // Тема закрыта
      }
      $page_link = 4;
      // Запрашиваем информацию об количестве всех тем
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE id_forum = $id_forum";
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
      echo "</table>";
    }

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