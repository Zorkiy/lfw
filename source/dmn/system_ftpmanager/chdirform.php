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

  // Данные переменные определяют название страницы и подсказку.
  if(empty($_GET['file']))
  {
    $title = 'Редактирование директории';
    $pageinfo = '<p class=help>Редактирование имени 
    директории и прав доступа ('.htmlspecialchars($_GET['dir']).').
    Права доступа позволяют задать права на чтение, запись и 
    просмотр директории для владельца файла, его группы и всех 
    остальных пользователей. Назначение каждого флажка можно 
    узнать из всплывающей подсказки. Для её вызова необходимо 
    навести и задержать над флажком курсор мыши.</p>';
    $name_position = "директории";
    $ur_hint = 'Чтение файлов директории для владельца';
    $uw_hint = 'Создание и редактирование файлов в директории 
                для владельца';
    $ux_hint = 'Чтение содержимого директории для владельца';
    $gr_hint = 'Чтение файлов директории для группы';
    $gw_hint = 'Создание и редактирование файлов в директории 
                для группы';
    $gx_hint = 'Чтение содержимого директории для группы';
    $or_hint = 'Чтение файлов директории для пользователей не 
                входящих в группу владельца';
    $ow_hint = 'Создание и редактирование файлов в директории 
                для пользователей не входящих в группу владельца';
    $ox_hint = 'Чтение содержимого директории для пользователей 
                не входящих в группу владельца';
  }
  else
  {
    $title = 'Редактирование файла';
    $pageinfo = '<p class=help>Редактирование имени файла 
    и прав доступа ('.htmlspecialchars($_GET['dir']).')</p>
    Права доступа позволяют задать права на чтение, запись 
    и выполнение директории для владельца файла,
    его группы и всех остальных пользователей. Назначение 
    каждого флажка можно узнать из всплывающей подсказки. 
    Для её вызова необходимо навести и задержать 
    над флажком курсор мыши.';
    $name_position = "файла";
    $ur_hint = 'Чтение файла для владельца';
    $uw_hint = 'Редактирование файла для владельца';
    $ux_hint = 'Выполнение файла для владельца';
    $gr_hint = 'Чтение файла для группы';
    $gw_hint = 'Редактирование файла для группы';
    $gx_hint = 'Выполнение файла для группы';
    $or_hint = 'Чтение файла для пользователей не входящих 
                в группу владельца';
    $ow_hint = 'Редактирование файла для пользователей не 
                входящих в группу владельца';
    $ox_hint = 'Выполнение файла для пользователей не входящих 
                в группу владельца';
  }

  // Устанавливаем соединение с базой данных
  require_once("../../config/config.php");
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Включаем заголовок страницы
  require_once("../utils/top.php");
  // Устанавливаем соединение с FTP-сервером
  require_once("../../config/ftp_connect.php");

  // Извлекаем из строки запроса имя изменяемой директории
  // и права доступа
  $dir = $_GET['dir'];
  $acc = $_GET['acc'];
  // Осуществляем разбор прав доступа пользователя
  if(substr($acc, 1, 1) == 'r') $ur = "checked";
  else $ur = "";
  if(substr($acc, 2, 1) == 'w') $uw = "checked";
  else $uw = "";
  if(substr($acc, 3, 1) == 'x') $ux = "checked";
  else $ux = "";
  // Осуществляем разбор прав доступа группы
  if(substr($acc, 4, 1) == 'r') $gr = "checked";
  else $gr = "";
  if(substr($acc, 5, 1) == 'w') $gw = "checked";
  else $gw = "";
  if(substr($acc, 6, 1) == 'x') $gx = "checked";
  else $gx = "";
  // Осуществляем разбор прав доступа остальных пользователей
  if(substr($acc, 7, 1) == 'r') $or = "checked";
  else $or = "";
  if(substr($acc, 8, 1) == 'w') $ow = "checked";
  else $ow = "";
  if(substr($acc, 9, 1) == 'x') $ox = "checked";
  else $ox = "";
  // Если не переданы параметры - настраиваем
  // форму на добавление директории
  $action = "chdir.php";
  $button = "Редактировать";
  // Включаем HTML-форму
?>
<form action=<?= $action; ?> method=post>
<table>
<tr>
  <td class=field>Название 
    <? echo htmlspecialchars($name_position); ?>:</td>
  <td><input size=31 type=text name=name 
  value='<? echo htmlspecialchars(basename($dir)); ?>'></td>
</tr>
<tr>
  <td class=field>Права доступа:</td>
  <td>
    <input type=checkbox 
           title='<?php echo $ur_hint; ?>' 
           name=ur <?php echo $ur; ?>>
    <input type=checkbox 
           title='<?php echo $uw_hint; ?>' 
           name=uw <?php echo $uw; ?>>
    <input type=checkbox 
           title='<?php echo $ux_hint; ?>' 
           name=ux <?php echo $ux; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $gr_hint; ?>' 
           name=gr <?php echo $gr; ?>>
    <input type=checkbox 
           title='<?php echo $gw_hint; ?>' 
           name=gw <?php echo $gw; ?>>
    <input type=checkbox 
           title='<?php echo $gx_hint; ?>' 
           name=gx <?php echo $gx; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $or_hint; ?>' 
           name=or <?php echo $or; ?>>
    <input type=checkbox 
           title='<?php echo $ow_hint; ?>' 
           name=ow <?php echo $ow; ?>>
    <input type=checkbox 
           title='<?php echo $ox_hint; ?>' 
           name=ox <?php echo $ox; ?>>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><input class=button 
             type=submit 
             value=<?php echo htmlspecialchars($button);?>></td></tr>
  <input type=hidden 
         name=dir 
         value=<?php echo htmlspecialchars($dir);?>>
</table>
</form>
<?php
  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>