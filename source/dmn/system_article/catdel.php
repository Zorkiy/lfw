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
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");

  // Защита от SQL-инъекции
  $_GET['id_catalog'] = intval($_GET['id_catalog']);

  try
  {
    // Извлекаем все изображения, принадлежащие каталогу и удаляем их
    $query = "SELECT * FROM $tbl_paragraph_image
              WHERE id_catalog=$_GET[id_catalog]";
    $img = mysql_query($query);
    if(!$img)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при извлечении 
                               параметров изображения");
    }
    if(mysql_num_rows($img))
    {
      while($image = mysql_fetch_array($img))
      {
        if(file_exists("../../".$image['big']))
          @unlink("../../".$image['big']);
        if(file_exists("../../".$image['small']))
          @unlink("../../".$image['small']);
      }
    }
  
    // Формируем и выполняем SQL-запрос на удаление изображений
    $query = "DELETE FROM $tbl_paragraph_image
              WHERE id_catalog=$_GET[id_catalog]";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при удалении позиции");
    }
    // Формируем и выполняем SQL-запрос на удаление параграфов
    $query = "DELETE FROM $tbl_paragraph
              WHERE id_catalog=$_GET[id_catalog]";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при удалении позиции");
    }
    // Формируем и выполняем SQL-запрос на удаление позиции каталога
    $query = "DELETE FROM $tbl_position
              WHERE id_catalog=$_GET[id_catalog]";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при удалении позиции");
    }
    // Формируем и выполняем SQL-запрос на удаление каталога
    $query = "DELETE FROM $tbl_catalog
              WHERE id_catalog=$_GET[id_catalog]
              LIMIT 1";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при удалении каталога");
    }
  
    header("Location: index.php?id_parent=$_GET[id_parent]&page=$_GET[page]");
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
?>