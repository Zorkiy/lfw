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
    // Задаём название страницы
    $nameaction = "Список последних тем";
    // Выводим "шапку" страницы
    require_once("../utils/topforumaction.php");
    echo "<p class=linkbackbig><a href=index.php?id_forum=$_GET[id_forum]>Вернуться</a></p>";
    // Отображаем позиции по $pnumber штук
    $page = intval($_GET['page']);
    $id_forum = intval($_GET['id_forum']);
    $_GET['id_author'] = intval($_GET['id_author']);
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    $query = "SELECT id_theme FROM $tbl_posts
              WHERE id_author = $_GET[id_author] AND hide = 'show'
              GROUP BY id_theme
              ORDER BY `time` DESC
              LIMIT 30";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении к сообщениям");
    }
    // Если имеется хотя бы одна тема - выводим список
    if(mysql_num_rows($pst))
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
      while($id_theme = mysql_fetch_array($pst))
      {
        // Считаем количество сообщений в данной теме.
        $query = "SELECT COUNT(*) FROM $tbl_posts
                  WHERE id_theme = $id_theme[id_theme] AND 
                        hide != 'hide'";
        $cnt = mysql_query($query);
        if(!$cnt) 
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при подсчёте количества сообщений темы");
        }
        if(mysql_num_rows($cnt)) $theme_count = mysql_result($cnt,0);

        // Извлекаем параметры темы
        $query = "SELECT * FROM $tbl_themes 
                  WHERE id_theme = $id_theme[id_theme] AND 
                        hide = 'show'";
        $thm = mysql_query($query);
        if(!$thm)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при извлечении параметров темы");
        }
        if(mysql_num_rows($thm))
        {
          $themes = mysql_fetch_array($thm);
      
          // Количество сообщений в теме
          echo "<tr class=trtablen><td class=trtemaheight align=center><p class=nametema><nobr>$theme_count</nobr></p></td>";
          // Выводим тему
          // Предварительно обрабатываем угловые скобки и ентеры
          // Обрабатываем теги [b],[/b],[i] и [/i]
          $namet = theme_work_up($themes['name']);
          // Выводим список тем
          if(!empty($page)) $strpage = "&page=".$page;
          // Название
          echo "<td><p><a target='_blank' href=read.php?id_forum={$themes[id_forum]}&id_theme={$themes[id_theme]}$strpage>$namet</a></td>";
          // Автор
          if($themes['id_author'] != 0) echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$themes[id_forum]&id_author=$themes[id_author]>".htmlspecialchars($themes['author'])."</a></td>";
          else echo "<td><p class=author>".htmlspecialchars($themes['author'])."</td>";
          // Время последнего обновления темы
          echo "<td><p class=texthelp>".convertdate($themes['time'])."</p></td></tr>";
        }
      }
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