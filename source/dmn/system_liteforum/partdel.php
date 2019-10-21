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
  // Блок управления позициями (show(), hide(), up(), down())
  require_once("../utils/utils.position.php");

  // Скрываем позицию
  try
  {
    // Извлекаем значения параметров из строки запроса
    $id_forum = intval($_GET['id_forum']);
    // Запрашиваем все темы, принадлежащие данному форуму
    $query = "SELECT * FROM $tbl_themes 
              WHERE id_forum = $id_forum";
    $thm = mysql_query($query);
    if(!$thm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка запроса к таблице 
                               тем раздела");
    }
    $id_theme = array();
    if(mysql_num_rows($thm))
    {
      // Удаляем все сообщения из тем форума
      while($theme = mysql_fetch_array($thm))
      {
        $id_theme[] = $theme['id_theme'];
      }
      if(is_array($id_theme))
      {
        $query = "DELETE FROM $tbl_posts
                  WHERE id_theme IN (".implode(",", $id_theme).")";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка удаления раздела");
        }
      }
    }
    // Удаляем все темы форума
    $query = "DELETE FROM $tbl_themes
              WHERE id_forum = $id_forum";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка удаления раздела");
    }
    // Удаляем сам форум
    $query = "DELETE FROM $tbl_forums
              WHERE id_forum = $id_forum";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка удаления раздела");
    }
    // Редактируем таблицу новых сообщений $tbl_last_time
    $query = "ALTER TABLE $tbl_last_time 
              DROP now$id_forum, 
              DROP last_time$id_forum";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка удаления раздела");
    }
    // Осуществляем автоматический переход на страницу
    // "Разделы форума"
    header("Location: index.php");
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
?>