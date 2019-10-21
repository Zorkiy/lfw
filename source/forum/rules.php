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
    // Задаём название страницы
    $nameaction = "Правила форума";
    // Выводим "шапку" страницы
    include "../utils/topforumaction.php"; 
    $_GET['id_forum'] = intval($_GET['id_forum']);
    // Извлекаем параметры форума
    $query = "SELECT * FROM $tbl_forums 
              WHERE id_forum=$_GET[id_forum]";
    $rls = mysql_query($query);
    if(!$rls)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при извлечении 
                               параметров форума");
    }
    $rules = mysql_fetch_array($rls);
    echo "<p class=linkbackbig><a href='javascript: history.back()'>Вернуться назад</a></p>";
    echo "<p class=remark>$rules[rule]</p>";
    // Выводим завершение страницы
    require_once("../utils/bottomforumaction.php");
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
