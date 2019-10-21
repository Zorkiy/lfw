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
  require_once("../../config/config.php");
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Устанавливаем соединение с FTP-сервером
  require_once("../../config/ftp_connect.php");
  // Подключаем функции для работы с 
  // файлами .htaccess и .htpasswd
  require_once("../utils/uitls.htfiles.php");

  $dir = $_GET['dir'];
  $name = $_GET['name'];
  if(is_htpasswd($ftp_handle, "/"))
  {
    // Файл .htpasswd в директории имеется, удаляем пользователя
    $content = get_htpasswd($ftp_handle, "/");
    $pattern = "#".preg_quote($name).":[^\n]+\n#is";
    $content = preg_replace($pattern, "", $content);
    // Перезаписываем файл .htpasswd
    put_htpasswd($ftp_handle, "/", $content);
  }

  $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
  @header("Location: $url");
?>