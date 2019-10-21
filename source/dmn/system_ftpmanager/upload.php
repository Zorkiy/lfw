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
  // методом POST из HTML-формы uploadform.php
  $dir = $_POST['dir'];
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
  // Проверяем введено ли имя для файла

  if(empty($_POST['dir'])) $directory = "/";
  else $directory = $_POST['dir'];

  if (!empty($_FILES['name']['tmp_name']))
  {
    $name = str_replace("//","/",$directory."/".$_FILES['name']['name']);
    $name = str_replace("..", "", $name);
    // Начинаем загрузку
    $ret = @ftp_nb_put($ftp_handle, 
                       $name, 
                       $_FILES['name']['tmp_name'], 
                       FTP_BINARY);
    while ($ret == FTP_MOREDATA)
    {
      // Продолжаем загрузку
      $ret = @ftp_nb_continue($ftp_handle);
    }
    if ($ret != FTP_FINISHED)
    {
      exit("<br>Во время загрузки файла произошла ошибка...");
    }
    else
    {
      @unlink($_FILES['name']['tmp_name']);
      // Создаём восьмеричную переменную $mode
      // с правами доступа к директории
       eval("\$mode=0$user$group$other;");    
      // Изменяем права доступа для только что
      // созданной директории
      @ftp_chmod($ftp_handle, $mode, $name);
    }
  }
  header("Location: index.php?dir=".urlencode($dir));
?>