<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) Кузнецов М.В., Симдянов И.В.
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
  require_once("config.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");

  try
  {
    // Извлекаем значения параметров из строки запроса
    $id_post  = intval($_GET['id_post']);
    $id_theme = intval($_GET['id_theme']);
    $id_forum = intval($_GET['id_forum']);
    $page     = intval($_GET['page']);
    // Скрываем сообщение
    hide($id_post);
    $query = "UPDATE $tbl_themes 
              SET `time` = NOW(), 
                   last_author = 'Модератор',
                   id_last_author = 0
              WHERE id_theme = $id_theme";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при сокрытии сообщения");
    }
    header("Location: posts.php?id_forum=$id_forum&id_theme=$id_theme&page=$page");
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }
  function hide($id_post)
  {
    // Объявляем название таблиц глобальными
    global $tbl_posts;
    // Извлекаем подчинённые сообщения
    $query = "SELECT * FROM $tbl_posts
              WHERE parent_post = $id_post";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при сокрытии сообщения");
    }
    if(mysql_num_rows($pst))
    {
      while($posts = mysql_fetch_array($pst))
      {
        hide($posts['id_post']);
      }
    }
    // Скрываем текущее сообщение
    $query = "UPDATE $tbl_posts SET hide='hide' 
              WHERE id_post = $id_post";
    
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при сокрытии сообщения");
    }
  }
?>