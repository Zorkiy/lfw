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

  // Получаем значения переменных переданных
  // методом POST из HTML-формы mkdirform.php
  $dir = $_POST['dir'];
  $name = $_POST['name'];
  // Преобразуем права доступа пользователя
  // в числовую форму
  $user = 0;
  if($_POST['ur'] == 'on') $user += 4;
  if($_POST['uw'] == 'on') $user += 2;
  if($_POST['ux'] == 'on') $user += 1;
  // Преобразуем права доступа для группы
  // в числовую форму
  $group = 0;
  if($_POST['gr'] == 'on') $group += 4;
  if($_POST['gw'] == 'on') $group += 2;
  if($_POST['gx'] == 'on') $group += 1;
  // Права доступа по умолчанию для
  // остальных пользователей (не входящих в группу)
  $other = 0;
  if($_POST['or'] == 'on') $other += 4;
  if($_POST['ow'] == 'on') $other += 2;
  if($_POST['ox'] == 'on') $other += 1;
  // Проверяем введено ли имя для директории
  if(!preg_match("|^[-\w\d_\"]+$|",$name))
  {
    exit("Недопустимое имя для директории");
  }

  $new_dir = str_replace("//","/",$dir."/".$name);

  // Создаём каталог с именем $name
  @ftp_mkdir($ftp_handle, $new_dir);
  // Создаём восьмеричную переменную $mode
  // с правами доступа к директории
  eval("\$mode=0$user$group$other;");    
  // Изменяем права доступа для только что
  // созданной директории
  @ftp_chmod($ftp_handle, $mode, $new_dir);

  header("Location: index.php?dir=".urlencode($dir));
?>