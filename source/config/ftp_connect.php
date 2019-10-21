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

  // Имя пользователя
  $ftp_user = "root";
  // Пароль
  $ftp_password = "password";
  // Сервер
  $ftp_server = "ftp.site.ru";
  // Абсолютный путь к виртуальному хосту
  $ftp_absolute_path = "/www/";
  // Устанавливаем время исполнения скрипта 120 с
  @set_time_limit(120);
  // Устанавливаем соединение с FTP-сервером
  $ftp_handle = ftp_connect($ftp_server);
  if (!$ftp_handle)
  {
    exit("Невозможно установить соединение с FTP-сервером");
  }
  if(!@ftp_login($ftp_handle, $ftp_user, $ftp_password))
  {
    exit("Ошибка авторизации на FTP-сервере");
  }
?>