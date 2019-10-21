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
    if(empty($_GET['part'])) $_GET['part'] = 1;
    $_GET['part'] = intval($_GET['part']);
    $_GET['id_link'] = intval($_GET['id_link']);

    $query = "UPDATE $tbl_links 
              SET hide='show' 
              WHERE id_links=$_GET[id_link]";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка изменения статуса ссылки");
    }
    // Осуществляем автоматический переход на страницу администрирования
    header("Location: links.php?part=$_GET[part]&page=$_GET[page]");
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