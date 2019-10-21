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

  // Если константа DEBUG определена, работает отладочный
  // вариант, в частности выводится подробные сообщения об
  // исключительных ситуациях, связанных с MySQL и ООП
  define("DEBUG", 1);
  // сейчас выставлен сервер локальной машины
  $dblocation = "localhost";
  // Имя базы данных, на хостинге или локальной машине
  $dbname = "oop_site";
  // Имя пользователя базы данных
  $dbuser = "root";
  // и его пароль
  $dbpasswd = "";

  // Таблицы форума
  $tbl_settings   = "liteforum_settings";
  $tbl_authors    = "liteforum_authors";
  $tbl_forums     = "liteforum_forums";
  $tbl_last_time  = "liteforum_last_time";
  $tbl_links      = "liteforum_links";
  $tbl_personally = "liteforum_personally";
  $tbl_posts      = "liteforum_posts";
  $tbl_themes     = "liteforum_themes";
  // Архивные таблицы
  $tbl_archive_number = "liteforum_archive_number";
  $tbl_archive_posts  = "liteforum_archive_posts";
  $tbl_archive_themes = "liteforum_archive_themes";

  // Устанавливаем соединение с сервером базы данных
  $dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
  if (!$dbcnx)
  {
    exit("В настоящий момент сервер базы данных не доступен,
          поэтому корректное отображение страницы невозможно.");
  }
  // Выбираем базу данных
  if (! @mysql_select_db($dbname,$dbcnx))
  {
    exit("В настоящий момент база данных не доступна, поэтому
          корректное отображение страницы невозможно.");
  }
  @mysql_query("SET NAMES 'cp1251'");
?>