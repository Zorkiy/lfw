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
  // Инициируем сессию
  session_start();

  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Подключаем заголовок 
  require_once("utils.title.php");

  try
  {
    // Подключаем верхний шаблон
    $pagename = "Регистрация на сайте";
    $keywords = "Регистрация на сайте";
    require_once ("templates/top.php");

    // Название страницы
    echo title($pagename);
    echo "<div class=main_txt>Поздравляем с успешной 
          регистрацией на сайте</div>";

    // Подключаем завершение страницы
    require_once("templates/bottom.php");
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