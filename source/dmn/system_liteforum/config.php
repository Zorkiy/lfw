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
  include "../../config/config.php";

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
?>