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
    // Защищаемся от SQL-инъекции
    $id_author = intval($_GET['id_author']);
    $id_forum  = intval($_GET['id_forum']);
    $page      = intval($_GET['page']);
    // Задаём название страницы
    $nameaction = "Список тем";
    // Выводим "шапку" страницы
    require_once("../utils/topforumaction.php");
    echo "<p class=linkbackbig><a href=index.php?id_forum=$_GET[id_forum]>Вернуться</a></p>";
    // Отображаем темы по $pnumber штук
    $pnumber = 25;
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    // Извлекаем общее количество тем, которые человек создал на форуме
    if(!empty($_GET['arch'])) $tbl = $tbl_archive_themes;
    else $tbl = $tbl_themes;
    $query = "SELECT * FROM $tbl 
              WHERE id_author = $id_author AND hide != 'hide'
              ORDER BY time DESC
              LIMIT $begin, $pnumber";
    $thm = mysql_query($query);
    if(!$thm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении к таблице тем");
    }
    // Если имеется хотя бы одна тема - выводим список
    if(mysql_num_rows($thm))
    {
      // Начало таблица с темами
      ?>
       <p class="zagtext">Результаты:</p>
       <table class=srchtable border="0" width="100%" cellpadding="4" cellspacing="1" >
          <tr class="tableheadern" align="center">
            <td class="tableheadern"><p class=fieldnameindex><nobr>Кол-во</nobr> сообщ.</p></td>
            <td class="tableheadern"><p class=fieldnameindex>Название темы</p></td>
            <td class="tableheadern"><p class=fieldnameindex>Автор</p></td>
            <td class="tableheadern"><p class=fieldnameindex>Последнее сообщение</p></td>
          </tr>
      <?php

      // Загружаем первичный ключ темы, которая последняя в 
      // архивной таблице
      $query = "SELECT id_theme FROM $tbl_archive_number LIMIT 1";
      $arh = mysql_query($query);
      if(!$arh)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при извлечении последней архивной темы");
      }
      if(mysql_num_rows($arh)) $id_theme_archive = mysql_result($arh, 0);
      // Все темы, которые имеют первичный ключ ниже $id_theme_archive
      // находятся в архиве, все, что выше - в "живом форуме"

      while($themes = mysql_fetch_array($thm))
      {
        if($themes['id_theme'] > $id_theme_archive)
        {
          // Подсчитываем количество сообщений для темы
          // в живом форуме
          $query = "SELECT COUNT(*) FROM $tbl_posts
                    WHERE id_theme = $themes[id_theme] AND 
                          hide != 'hide'";
          $pst = mysql_query($query);
          if(!$pst)
          {
             throw new ExceptionMySQL(mysql_error(), 
                                      $query,
                                     "Ошибка при подсчёте количества сообщений темы");
          }
          $theme_count = mysql_result($pst,0);
        }
        else
        {
          // Извлекаем количество сообщений в теме
          // в архивном форуме
          $theme_count = $themes['number'];
        }
      
        // Количество сообщений в теме
        echo "<tr class=trtablen><td class=trtemaheight align=center><p class=nametema><nobr>$theme_count</nobr></p></td>";
        // Выводим тему
        // Предварительно обрабатываем угловые скобки и ентеры
        // Обрабатываем теги [b],[/b],[i] и [/i]
        $namet = theme_work_up($themes['name']);
        // Выводим список тем
        if(!empty($page)) $strpage = "&page=".$page;
        // Название
        echo "<td><p><a target='_blank' href=read.php?id_forum=$themes[id_forum]&id_theme=$themes[id_theme]{$strpage}>$namet</a></td>";
        // Автор
        if($themes['id_author'] != 0) echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$themes[id_forum]&id_author=$themes[id_author]>".htmlspecialchars($themes['author'])."</a></td>";
        else echo "<td><p class=author>".htmlspecialchars($themes['author'])."</td>";
        // Время последнего обновления темы
        echo "<td><p class=texthelp>".convertdate($themes['time'])."</p></td></tr>";
      }
      // Конец таблицы по выводу тем форума
      // Выводим ссылки на другие темы форума
      $page_link = 1;
    
      // Извлекаем общее число тем, которые человек создал на форуме
      $query = "SELECT COUNT(*) FROM $tbl 
                WHERE id_author = $id_author AND 
                      hide != 'hide'";
      $tot = mysql_query($query);
      if(!$tot)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при подсчёте количества тем");
      }
      $total = mysql_result($tot, 0);
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber)-$number != 0) $number++;

      echo "<tr><td class=bottomtablen colspan=4><p class=texthelp>";
      pager($page, $total, $pnumber, $page_link, "&id_forum=$id_forum&id_author=$id_author&arch=$_GET[arch]");
      echo "</td></tr>";
      echo "</table>";
    }
    else
    {
      echo "<p class=result>Данный посетитель не инициировал ни одной темы.</p>";
    }
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