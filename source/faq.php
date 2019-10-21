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
    $obj = new pager_mysql($tbl_faq,
                           "",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link);

    // Подключаем верхний шаблон
    $pagename = "Вопросы и Ответы";
    $keywords = "Вопросы и Ответы";
    require_once ("templates/top.php");

    // Получаем содержимое текущей страницы
    $faq = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим
    if(!empty($faq))
    {
      echo title($pagename);

      for($i = 0; $i < count($faq); $i++)
      {
        echo "<div class=main_txt><b>".nl2br(print_page($faq[$i]['question']))."</b></div>";
        echo "<div class=main_txt>".nl2br(print_page($faq[$i]['answer']))."</div>";
      }
      // Выводим ссылки на другие страницы
      echo "<div class=main_txt>";
      echo $obj;
      echo "</div>";
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
