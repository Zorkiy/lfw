<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // Инициируем сессию
  session_start();
  $sid_add_theme = session_id();
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  if(!defined("ADD_PERSONALLY")) return;

  // Загружаем настройки форума
  $settings = get_settings();

  ///////////////////////////////////////////////////////////
  // Блок подготовки
  ///////////////////////////////////////////////////////////
  // Извлекаем значения переданные методом POST из
  // суперглобального массива $_POST
  $author       = trim($_POST['author']);
  $pswrd        = $_POST['pswrd'];
  $message      = trim($_POST['message']);
  $theme        = trim($_POST['theme']);
  $id_author    = intval($_POST['id_author']);
  $id_forum     = intval($_POST['id_forum']);
  $id_theme     = intval($_POST['id_theme']);
  $id_post      = intval($_POST['id_post']);
  $id_addresser = intval($_POST['id_addresser']);

  if(empty($author))  $error[] = "Не указано имя";
  if(empty($message)) $error[] = "Сообщение не введено";
  if($sid_add_theme != $_POST['sid_add_theme']) $error[] = "Ошибка добавления темы";
  // Подготавливаем переменные для добавления в SQL-запрос, экранируя
  // все спецсимволы при помощи функции mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $theme = mysql_escape_string($theme);
    $author  = mysql_escape_string($author);
    $pswrd   = mysql_escape_string($pswrd);
    $message = mysql_escape_string($message);
  }
  ///////////////////////////////////////////////////////////
  // Блок идентификации
  ///////////////////////////////////////////////////////////
  define("ADDMESSAGE",1);
  require_once("../utils/autreg.php");

  // Антиспам
  if($id_author == 0)
  {
    // Для незарегистрированных пользователей включаем
    // антиспам
    if(strpos($message,".at"))  exit();
    if(strpos($message,".be"))  exit();
    if(strpos($message,".biz"))  exit();
    if(strpos($message,".com"))  exit();
    if(strpos($message,".es"))  exit();
    if(strpos($message,".ee"))  exit();
    if(strpos($message,".edu"))  exit();
    if(strpos($message,".de"))  exit();
    if(strpos($message,".info"))  exit();
    if(strpos($message,".it"))  exit();
    if(strpos($message,".in"))  exit();
    if(strpos($message,".net"))  exit();
    if(strpos($message,".no"))  exit();
    if(strpos($message,".org"))  exit();
    if(strpos($message,".pl"))  exit();
    if(strpos($message,".ru"))  exit();
    if(strpos($message,".sk"))  exit();
    if(strpos($message,".su"))  exit();
    if(strpos($message,".ws"))  exit();
    if(strpos($message,".us"))  exit();
    if(strpos($message,".name"))  exit();

    if(strpos($message,".gen.in"))  exit();
    if(strpos($message,"porno"))  exit();
    if(strpos($message,"narod.ru"))  exit();

    $number = preg_match_all("|<a[\s]+href=[^>]+>[^<]+<|is",$message,$out);
    if($number > 25) exit();
    $number = preg_match_all("#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU",$message,$out);
    if($number > 25) exit();
  }
  // Если ошибок нет - добавляем сообщение
  if(empty($error))
  {
    ///////////////////////////////////////////////////////////
    // Блок добавления сообщения
    ///////////////////////////////////////////////////////////
    // Формируем SQL-запрос на добавление темы
    $query = "INSERT INTO $tbl_themes VALUES(
           NULL,
           '$theme',
           '$author',
           $id_author,
           '$author',
           $id_author,         
           'hide',
           NOW(),
           $id_forum)";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка добавления нового сообщения");
    }
    // Выясняем первичный ключ только что добавленной записи
    // это понадобится для добавления сообщения и файла
    $id_theme = mysql_insert_id();
    ///////////////////////////////////////////////////////////
    // Блок загрузки файла на сервер
    ///////////////////////////////////////////////////////////
    require_once("../utils/loadfile.php");

    ///////////////////////////////////////////////////////////
    // Блок формирования и выполнения SQL-запроса
    ///////////////////////////////////////////////////////////
    // Формируем SQL-запрос на добавление сообщения
    $query = "INSERT INTO $tbl_posts VALUES(
             NULL,
             '$message',
             '',
             '$path',
             '$author',
             $id_author,
             'show',
             NOW(),
             0,
             $id_theme)";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка добавления нового сообщения");
    }
    // Добавляем тему в список личных тем, автора и адресата
    $query = "INSERT INTO $tbl_personally VALUES(
           NULL,
           $id_theme,
           $id_addresser,
           $id_author)";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка добавления нового сообщения");
    }

    // А так же количество оставленных автором сообщений
    $query = "UPDATE $tbl_authors
              SET themes = themes + 1
              WHERE id_author = $id_author";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обновления параметров автора");
    }
  
    @header("Location: personally.php?id_forum=$id_forum");
    exit();
  }
?>