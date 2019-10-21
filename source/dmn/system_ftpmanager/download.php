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

  // Генерируем ункальное имя файла
  $localfile = tempnam("files","down");
  $ret = @ftp_nb_get($ftp_handle, $localfile, $_GET['dir'], FTP_BINARY);
  while ($ret == FTP_MOREDATA)
  {
    // Продолжаем загрузку
    $ret = @ftp_nb_continue($ftp_handle);
  }
  @chmod($localfile, 0644);
  // Если происходит ошибка при загрузке файла
  // уведомляем об этом пользователя
  if ($ret != FTP_FINISHED)
  {
    exit("<br>Во время загрузки файла произошла ошибка...");
  }
  else
  {
    // Отсылаем пользователю файл
    header("Content-Disposition: attachment;".
           " filename=".basename($_GET['dir'])); 
    header("Content-Length: ".filesize($localfile)); 
    header("Content-Type: application/x-force-download;".
           " name=\"".basename($_GET['dir'])."\"");
    echo @file_get_contents($localfile);
  }
  @unlink($localfile);
?>