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
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");

  // Проверяем GET-параметры, предотвращая SQL-инъекцию
  $_GET['id_catalog']  = intval($_GET['id_catalog']);

  try
  {
    // Удаляем каталог, со всеми вложенными подкаталогами
    del_catalog($_GET['id_catalog'], 
                $tbl_cat_catalog, 
                $tbl_cat_position);
    // Осуществляем переадресацию на главную страницу
    header("Location: index.php?".
           "page=$_GET[page]");
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // Рекурсивная функция удаления каталога с первичным ключом $id_catalog
  function del_catalog($id_catalog, 
                       $tbl_cat_catalog, 
                       $tbl_cat_position)
  {
    // Преобразуем параметр $id_catalog к целому значению
    $id_catalog = intval($id_catalog);
    // Осуществляем рекурсивный спуск, для того,
    // чтобы удалить все вложенные подкаталоги
    $query = "SELECT * FROM $tbl_cat_catalog
              WHERE id_parent = $id_catalog"
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка удаления
                               подкаталога");
    }
    if(mysql_num_rows($cat))
    {
      while($catalog = mysql_fetch_array($cat))
      {
        del_catalog($catalog['id_catalog'],
                    $tbl_cat_catalog,
                    $tbl_cat_position);
      }
    }
    // Удаляем товарные позиции принадлежащие каталогу
    $query = "DELETE FROM $tbl_cat_position
              WHERE id_catalog=$id_catalog";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка удаления
                               подкаталога");
    }
    // Удаляем каталог с первичным ключом $id_catalog
    $query = "DELETE FROM $tbl_cat_catalog
              WHERE id_catalog=$id_catalog";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка удаления
                               подкаталога");
    }
  }   
?>