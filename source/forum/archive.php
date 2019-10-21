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

  try
  {
    // Извлекаем параметры из строки запроса
    if(!isset($_GET['id_forum'])) $_GET['id_forum'] = 1;
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $page = intval($_GET['page']);

    // Проверяем значение $id_forum на допустимое значение
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

    // Отображаем темы по $pnumber штук
    $page = intval($_GET['page']);
    $pnumber = $settings['number_themes'];
    if(empty($pnumber)) $pnumber = 30;
    // Если в строке запроса не передана страница
    // выводим первую страницу
    if(empty($page)) $page = 1;
    $begin = ($page - 1)*$pnumber;
    // Запрашиваем информацию об $pnumber темах
    $query = "SELECT * FROM $tbl_archive_themes 
              WHERE id_forum = $id_forum AND
                    hide != 'hide'
              ORDER BY time DESC
              LIMIT $begin, $pnumber";
    $thm = mysql_query($query);
    if(!$thm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при извлечении
                                тем форума");
    }
    if(mysql_num_rows($thm))
    {
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
        // Если новых сообщений нет, просто приводим общее число
        // сообщений в теме
        $theme_count = $themes['number'];
        $theme_style = "class=nametema";             

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
        echo "<td>
                <p $theme_style><a $theme_style title='$closetitle' href=read.php?id_forum=$id_forum&id_theme=$themes[id_theme]{$strpage}>$name $closetheme</a></p>
              </td>"; 
        ///////////////////////////////////////////////////////////
        // Блок вывода автора темы
        ///////////////////////////////////////////////////////////
        $author = htmlspecialchars($themes['author']);
        if($themes['id_author'] != 0)
        {
          echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_author]>$author</a></td>";
        }   
        else
        {
          echo "<td><p class=author>$author</td>";
        }  
        ///////////////////////////////////////////////////////////
        // Блок вывода последнего обновления темы
        ///////////////////////////////////////////////////////////
        $themes['last_author'] = htmlspecialchars($themes['last_author']);
        echo "<td ><p class=tddate><nobr>".convertdate($themes['time'], 0)."</nobr></p></td>";
        // Формируем ссылки на последнего автора в теме
        if($themes['id_last_author'] != 0)
        {
          $last_author="<p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_last_author]><nobr>$themes[last_author]</nobr></a></p>";
        }   
        else
        {
          $last_author="<p class=author><nobr>".$themes['last_author']."</nobr></p>";
        }  
        echo "<td>$last_author</td></tr>";      
        // Конец таблицы по выводу тем форума
      }
      ///////////////////////////////////////////////////////////
      // Блок вывода ссылок на другие темы форума
      ///////////////////////////////////////////////////////////
      $page_link = 4;
      // Запрашиваем информацию о количестве всех тем
      $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                WHERE id_forum = $id_forum AND
                      hide != 'hide'";
      $tot = mysql_query($query);
      if(!$tot)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при выборке 
                                  общего количества тем форума");
      }
      if(mysql_num_rows($tot)) $total = mysql_result($tot,0);
      // Постраничная навигация
      echo "<tr><td class=bottomtabletema colspan=5><div class=leftblock><p class=texthelp>Сообщения: ";
      pager($page, $total, $pnumber, $page_link, "&id_forum=".$id_forum);
      echo "&nbsp;<a title='Архив форума' class=menuinfo href=index.php?id_forum=$id_forum> <nobr>[живой форум]</nobr></a>&nbsp;";
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
    mysql_close();
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