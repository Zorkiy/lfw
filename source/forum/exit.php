<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  // Бешкенадзе А.Г. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Функции для работы с пользователями
  require_once("../utils/utils.users.php");

  // Очищаем cookie
  cleanallcookie();
  // Осуществляем автоматический переход назад
  if(empty($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER'] = "index.php";
  header("Location: $_SERVER[HTTP_REFERER]");
?>