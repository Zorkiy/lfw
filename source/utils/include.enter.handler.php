<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  if(!defined("ENTER")) return;

  ///////////////////////////////////////////////////////////
  // Блок подготовки и проверки
  ///////////////////////////////////////////////////////////
  // Получаем данные отправленные методом POST
  $pswrd  = $_POST['pswrd'];
  $author = $_POST['author'];
  $id_forum = intval($_POST['id_forum']);
  // Подготавливаем переменные для добавления в SQL-запрос, экранируя
  // все спецсимволы при помощи функции mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $author      = mysql_escape_string($author);
    $pswrd       = mysql_escape_string($pswrd);
  }

  // Блок идентификации
  $auth = get_user($author, $pswrd);
  if(!$auth) $error[] = "Пароль не соответствует логину";

  // Если ошибок нет - осуществляем вход пользователя
  if(empty($error))
  {
    // Обновляем количество сообщений автора в таблице авторов
    $query = "SELECT COUNT(*) FROM $tbl_posts 
              WHERE id_author = $auth[id_author]";
    $pst = mysql_query($query);
    if(!$pst)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении к 
                                базе авторов");
    }
    $count = mysql_result($pst, 0);
    $query = "SELECT COUNT(*) FROM $tbl_archive_posts 
              WHERE id_author = $auth[id_author]";
    $pst = mysql_query($query);
    if(!$pst)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении к 
                                базе авторов");
    }
    $count += mysql_result($pst,0);

    $query_author = "UPDATE $tbl_authors 
                     SET themes = $count 
                     WHERE id_author = $auth[id_author]";
    if(!mysql_query($query_author))
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка обновления данных");
    }
    // Устанавливаем в кукисах автора и его пароль
    setallcookie($author, $pswrd);
    // Обновляем дату последнего вхождения
    settime($author, true, $id_forum);
    // Переходим обратно
    @header("Location: index.php?id_forum=$id_forum");
    exit();
  }
?>