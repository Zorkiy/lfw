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
  Error_Reporting(E_ALL & ~E_NOTICE); 
  // Помещаем всё в буффер
  ob_start();
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
  // Настройки форума
  require_once("../utils/utils.settings.php");

  try
  {
    // Извлекаем настройки форума
    $settings = get_settings();

    // Извлекаем параметры из строки запроса
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $id_author = 0;
    $page = intval($_GET['page']);

    // Проверяем значение $id_forum на допустимое значение
    if(!isset($id_forum)) $id_forum = 1;
    $query = "SELECT MIN(id_forum) AS min,
                     MAX(id_forum) AS max
              FROM $tbl_forums
              WHERE hide != 'hide'";
    $frm = mysql_query($query);
    if(!$frm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении 
                                к таблице форумов");
    }
    if(mysql_num_rows($frm))
    {
      $minmaxfrm = mysql_fetch_array($frm);
      if($id_forum < $formum['min']) $id_forum = $minmaxfrm['min'];
      if($id_forum > $formum['max']) $id_forum = $minmaxfrm['max'];
    }

    // Вывод линейки новых сообщений
    $showforumsline = true;
    // Включаем "шапку" страницы
    require_once("../utils/topforum.php");

    // Аутентификация
    if(empty($_COOKIE['current_author']))
    {
      header("Location: index.php?id_forum=$id_forum");
    }
    $current_author = $_COOKIE['current_author'];
    $wrdp = $_COOKIE['wrdp'];
    if (!get_magic_quotes_gpc())
    {
      $current_author = mysql_escape_string($current_author);
      $wrdp = mysql_escape_string($wrdp);
    }
    // Если включены личные сообщения, проверяем,
    // не включены ли они
    if($settings['show_personally'] == 'yes')
    {
      // Если личные сообщения включены - проверяем
      // имеется ли для данного посетителя новые сообщения
      // предварительно проводим авторизацию
      $query = "SELECT * FROM $tbl_authors 
                WHERE name = '$current_author' AND
                      passw = ".get_password($wrdp)." AND
                      statususer != 'wait'";
      $ath = mysql_query($query);
      if(!$ath)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка аутентификации");
      }
      // Если имеется запись, следовательно, посетитель зарегистрирован
      // и необходимо сверить пароли
      if(mysql_num_rows($ath)>0)
      {
        define("AUTHOR", 1);
        $auth = mysql_fetch_array($ath);
        $id_author = $auth['id_author'];
      }
    }

    if(defined("AUTHOR"))
    {
      // Отображаем темы по $pnumber штук
      $pnumber = $settings['number_themes'];
      if(empty($pnumber)) $pnumber = 30;
      // Если в строке запроса не передана страница
      // выводим первую страницу
      if(empty($page)) $page = 1;
      $begin = ($page - 1)*$pnumber;
      // Запрашиваем информацию об $pnumber темах
      $query = "SELECT $tbl_themes.id_theme AS id_theme,
                       $tbl_themes.time AS time,
                       $tbl_themes.name AS name,
                       $tbl_themes.author AS author,
                       $tbl_themes.id_author AS id_author,
                       $tbl_themes.last_author AS last_author,
                       $tbl_themes.id_last_author AS id_last_author
              FROM $tbl_personally, $tbl_themes
              WHERE ($tbl_personally.id_first = $id_author OR
                    $tbl_personally.id_second = $id_author) AND
                    $tbl_themes.id_theme = $tbl_personally.id_theme
              ORDER BY `time` DESC
              LIMIT $begin, $pnumber";
      $thm = mysql_query($query);
      if(!$thm)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при выборке тем форума");
      }
      // Начало таблица с темами
      ?>
      <table border=0 class=temamenu cellspacing="1" cellpadding="0" width=100% >
        <tr class="headertable" align="center">
          <td class="headertable" width=30px><p class=fieldnameindex>&nbsp;</p></td>
          <td class="headertable"><p class=fieldnameindex>Название темы</p></td>
          <td class="headertable"><p class=fieldnameindex>Автор</p></td>
          <td colspan=2 width=25% class="headertable" ><p class=fieldnameindex>Последнее сообщение и автор</p></td>
         </tr>
      <?php
      while($themes = mysql_fetch_array($thm))
      {
        ///////////////////////////////////////////////////////////
        // Блок вывода числа сообщений в теме
        ///////////////////////////////////////////////////////////
        // Подсчитываем количество сообщений в текущей теме,
        // результат помещаем в переменную $posts_in_topic
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
        if(mysql_num_rows($pst)) $posts_in_topic = mysql_result($pst, 0);
  
        // Подсчитываем количество новых сообщений в текущей
        // теме, результат помещаем в переменную $new_posts_in_topics
        $query = "SELECT COUNT(*) FROM $tbl_posts
                  WHERE id_theme='$themes[id_theme]' AND
                        '$lasttime'<time AND
                        hide != 'hide'";
        $tim = mysql_query($query);
        if(!$tim)
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "Ошибка при подсчёте количества сообщений");
        }
        if(mysql_num_rows($tim)) $new_posts_in_topics = mysql_result($tim, 0);
        else $new_posts_in_topics = 0;
          
        // Формируем стилевое оформление темы и строку с числом новых и
        // общим числом сообщений в теме
        if($new_posts_in_topics != 0)
        {
          // Если в системе имеются новые сообщения приводим их
          // в скобках
          $theme_count = "$posts_in_topic($new_posts_in_topics) ";
          $theme_style = "class=namenewtema";
        }        
        else
        {
          // Если новых сообщений нет, просто приводим общее число
          // сообщений в теме
          $theme_count = $posts_in_topic;
          $theme_style = "class=nametema";             
        }    
        echo "<tr class=trtema><td class=trtemaheight align=center><p $theme_style><nobr>$theme_count</nobr></p></td>";
        ///////////////////////////////////////////////////////////
        // Блок вывода названия темы
        ///////////////////////////////////////////////////////////
        // Предварительно обрабатываем угловые скобки и ентеры
        // Обрабатываем теги [b],[/b],[i] и [/i]
        $name = theme_work_up($themes['name']);
        if(isset($page)) $strpage = "&page=".$page;
        // Если тема закрыта выводим предупреждение
        $closetheme = "";
        $closetitle = "";
        if($themes['hide'] == 'lock')
        {
          if($posts_in_topic>1) $closetheme = "(тема закрыта)";
          else $closetheme = "(тема перенесена)";
          $closetitle = "Тема закрыта для обсуждения";
        }
        echo "<td>
                <p $theme_style><a $theme_style title='$closetitle' href=personallyread.php?id_forum=$id_forum&id_theme=$themes[id_theme]{$strpage}>$name $closetheme</a></p>
              </td>"; 
        ///////////////////////////////////////////////////////////
        // Блок вывода автора темы
        ///////////////////////////////////////////////////////////
        $author = htmlspecialchars($themes['author']);
        if($themes['id_author'] != 0)
          echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_author]>$author</a></td>";
        else
          echo "<td><p class=author>$author</td>";
        ///////////////////////////////////////////////////////////
        // Блок вывода последнего обновления темы
        ///////////////////////////////////////////////////////////
        $themes['last_author'] = htmlspecialchars($themes['last_author']);
        echo "<td ><p class=tddate><nobr>".convertdate($themes['time'], 0)."</nobr></p></td>";
        // Формируем ссылки на последнего автора в теме
        if($themes['id_last_author'] != 0)
        {
          $last_author = "<p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_last_author]><nobr>$themes[last_author]</nobr></a></p>";
        }   
        else
        {
           $last_author = "<p class=author><nobr>$themes[last_author]</nobr></p>";
        }  
        echo "<td>$last_author</td></tr>";      
        // Конец таблицы по выводу тем форума
      }
      ///////////////////////////////////////////////////////////
      // Блок вывода ссылок на другие темы форума
      ///////////////////////////////////////////////////////////
      $page_link = 4;
      // Запрашиваем информацию о количестве всех тем
      $query = "SELECT COUNT($tbl_themes.id_theme)
                FROM $tbl_personally, $tbl_themes
                WHERE ($tbl_personally.id_first = $id_author OR
                       $tbl_personally.id_second = $id_author) AND
                       $tbl_themes.id_theme = $tbl_personally.id_theme";
      $tot = mysql_query($query);
      if(!$tot)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при подсчёте количества сообщений");
      }
      if(mysql_num_rows($tot)) $total = mysql_result($tot,0);
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber) - $number != 0) $number++;

      // Выводим постраничную навигацию
      echo "<tr><td class=bottomtabletema colspan=5><div class=leftblock><p class=texthelp>Сообщения: ";
      pager($page, $total, $pnumber, $page_link, "&id_forum=$id_forum");
      echo "&nbsp;<a title='Архив форума' class=menuinfo href=archive.php?id_forum=$id_forum> <nobr>[архив]</nobr></a>&nbsp;";
      echo "</div><div align=right class=linksofttime>Форум разработан <nobr>IT-студией <a class=linksofttime href='http://www.softtime.ru'>SoftTime</a></nobr></div></td></tr>";
      // Конец вывода ссылок на другие темы форума
      echo "</table>";
    }
    // Выводим завершение страницы
    include "../utils/bottomforum.php";
    // Помещаем страницу из буффера в переменную $buffer
    $buffer = ob_get_contents();  
    // Очищаем буффер
    ob_end_clean();
    // Отправляем страницу клиенту
    echo $buffer;
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