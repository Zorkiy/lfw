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

  // Подключаем SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Функции для работы со временем
  require_once("../utils/utils.time.php");
  // Функции для работы с сообщениями
  require_once("../utils/utils.posts.php");
  // Настройки форума
  require_once("../utils/utils.settings.php");
  // Функции для работы с пользователями
  require_once("../utils/utils.users.php");
  // Функция для работы с файлами
  require_once("../utils/utils.files.php");

  try
  {
    // Извлекаем значения параметров из строки запроса
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $page     = intval($_GET['page']);
    $struct   = $_GET['struct'];
    $down     = $_GET['down'];
    // Определяем путь, где расположен форум
    $tmppos = strrpos($_SERVER['PHP_SELF'],"/") + 1;
    $path = substr($_SERVER['PHP_SELF'], 0, $tmppos);
    if($struct)
    {
      $settings = get_settings();
      @setcookie("lineforum", "set_line_forum", time() + 3600*24*$settings['cooktime'], $path);
      // Если определена переменная $down новые сортируем сообщения от старых дат к новым
      if(!empty($down)) 
      {
        setcookie("lineforumdown", "set_line_forum_down", time() + 3600*24*$settings['cooktime'], $path);
      }
      else setcookie("lineforumdown", "", 0, $path);
    } else setcookie("lineforum", "", 0, $path);
    // Осуществляем автоматический переход к теме
    header("Location: read.php?id_forum=$id_forum&id_theme=$id_theme&page=$page");
  }
  catch(ExceptionObject $exc) 
  {
    require_once("exception_object_debug.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>