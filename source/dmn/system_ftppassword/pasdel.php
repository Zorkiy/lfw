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
  // Подключаем классы формы
  require_once("../../config/class.config.dmn.php");
  // Устанавливаем соединение с FTP-сервером
  require_once("../../config/ftp_connect.php");
  // Подключаем функции для работы с 
  // файлами .htaccess и .htpasswd
  require_once("../utils/uitls.htfiles.php");

  $dir = $_GET['dir'];
  if(is_htpasswd($ftp_handle, $dir))
  {
    // Удаляем файл .htpasswd
    $ftp_htpasswd = str_replace("//","/",$dir."/.htpasswd");
    ftp_delete($ftp_handle, $ftp_htpasswd);
  }
  if(is_htaccess($ftp_handle, $dir))
  {
    // Удаляем строки авторизации в .htaccess
    $content = get_htaccess($ftp_handle, $dir);
    $pattern = "#AuthType.*valid-user#is";
    $content = preg_replace($pattern, "", $content);
    put_htaccess($ftp_handle, $dir, $content);
  }

  $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
  @header("Location: $url");
?>