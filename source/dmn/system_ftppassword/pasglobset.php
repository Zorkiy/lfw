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

  ///////////////////////////////////////////////////////
  // .htaccess
  ///////////////////////////////////////////////////////
  $dir = $_GET['dir'];
  if(is_htpasswd($ftp_handle, $dir))
  {
    // Удаляем файл .htpasswd
    $ftp_htpasswd = str_replace("//","/",$dir."/.htpasswd");
    @ftp_delete($ftp_handle, $ftp_htpasswd);
  }
  if(!is_htaccess($ftp_handle, $dir))
  {
    // Файла .htaccess в директории нет, создаём его
    // в директории files и загружаем по FTP
    $content = "AuthType Basic\n".
               "AuthName \"Fill name and password\"\n".
               "AuthUserFile $ftp_absolute_path.htpasswd\n".
               "require valid-user";
    put_htaccess($ftp_handle, $dir, $content);
  }
  else
  {
    // Файл .htpasswd в директории присутствует
    // Загружаем содержимое файла
    $content = get_htaccess($ftp_handle, $dir);
    // Проверяем, имеется ли в файле фраза require valid-user, 
    // если имеется - ничего не добавляем, если неимется, добавляем
    // требование защиты
    $flag = (strpos($content, "require") !== false) && 
            (strpos($content, "valid-user") !== false);
    if(!$flag)
    {
      $content .= "\nAuthType Basic\n".
                  "AuthName \"Fill name and password\"\n".
                  "AuthUserFile $ftp_absolute_path.htpasswd\n".
                  "require valid-user";
      put_htaccess($ftp_handle, $dir, $content);
    }
    else
    {
      // Удаляем старую защиту
      $pattern = "#AuthType.*valid-user#is";
      $content = preg_replace($pattern, "", $content);
      // Ставим новую
      $content .= "\nAuthType Basic\n".
                  "AuthName \"Fill name and password\"\n".
                  "AuthUserFile $ftp_absolute_path.htpasswd\n".
                  "require valid-user";
      put_htaccess($ftp_handle, $dir, $content);
    }
  }

  // Редирект в систему администрирования
  $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
  @header("Location: $url");
?>