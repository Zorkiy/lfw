<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) Кузнецов М.В., Симдянов И.В.
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
  require_once("config/config.php");
  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Подключаем функцию вывода текста с bbCode
  require_once("dmn/utils/utils.print_page.php");
  // Подключаем заголовок 
  require_once("utils.title.php");

  try
  {
    // Число сообщений на странице
    $pnumber = 10;
    // Число ссылок в постраничной навигации
    $page_link = 3;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_guestbook,
                           "WHERE hide = 'show'",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);

    // Подключаем верхний шаблон
    $pagename = "Гостевая книга";
    $keywords = "Гостевая книга";
    require_once ("templates/top.php");

    echo title($pagename);

    echo "<p class=main_txt>
           <a href=guestbook_add.php 
              class=main_txt_lnk>Добавить сообщение</a></p>";

    // Получаем содержимое текущей страницы
    $guest = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим
    if(!empty($guest))
    {
      echo '<table border="0" 
                   cellpadding="0" 
                   cellspacing="0" 
                   width="100%" 
                   align="left">';
      for($i = 0; $i < count($guest); $i++)
      {
        // Если указан город - выводим его
        if(!empty($guest[$i]['city']))
        {
          $city = "&nbsp;(".print_page($guest[$i]['city']).")";
        }
        else $city = "";
        // Формируем дату в привычном для пользователя формате
        list($date, $time) = explode(" ", $guest[$i]['putdate']);
        list($year, $month, $day) = explode("-", $date);
        $date = "$day.$month.$year ".substr($time, 0, 5);
        // Формируем один из ответов
        echo '<tr bgcolor="#C5D7DB" class=main_txt>
                <td rowspan="1" height="20">
                  <nobr><p class=ptdg><b>'.
                    print_page($guest[$i]['name']).
                  '</b>'.$city.'</nobr></td>
                <td width="100%" align="right">
                  <nobr><p class=help>от: <b>'.$date.'</b>&nbsp;</nobr>
                </td>
              </tr>';
        echo '<tr>
               <td colspan=2 bgcolor="gray" height="1"><img 
                 src="images/pic.gif" 
                 border="0" 
                 width="1" 
                 height="1" 
                 alt=""></td>
             </tr>';
        echo '<tr valign="top" class=main_txt>
                <td colspan="2"><p class=textgbook>'.
                  nl2br(print_page($guest[$i]['msg'])).'</p>';
        if(!empty($guest[$i]['answer']) && $guest[$i]['answer'] != '-')
        {
          // Если имеется ответ администратора - выводим его
          echo '<p class=panswer style="color: grey">
                  <b>Администратор: '.nl2br(print_page($guest[$i]['answer'])).'</b>
                </p>';
        }
        echo "</td></tr>";
      }
      echo "</table>";
      // Выводим ссылки на другие страницы
      echo '<br clear="all">';
      echo "<p class=main_txt>";
      echo $obj;
      echo "</p>";
    }

    //Подключаем нижний шаблон
    require_once ("templates/bottom.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php");
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
