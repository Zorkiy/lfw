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
  // Удаляем файл
  @ftp_delete($ftp_handle, $_GET['dir']);
  // Осуществляем автоматический переход
  // на страницу администрирования ftp-каталога
  $dir = urlencode(substr($_GET['dir'], 0, strrpos($_GET['dir'], "/")));
  header("Location: index.php?dir=$dir");
?>