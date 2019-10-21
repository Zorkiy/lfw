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

  if(!defined("EDIT_POST")) return;

  // Загружаем настройки форума
  $settings = get_settings();

  ///////////////////////////////////////////////////////////
  // Блок подготовки
  ///////////////////////////////////////////////////////////
  // Извлекаем значения переданные методом POST из
  // суперглобального массива $_POST
  $author    = trim($_POST['author']);
  $pswrd     = $_POST['pswrd'];
  $message   = trim($_POST['message']);
  $id_author = intval($_POST['id_author']);
  $id_forum  = intval($_POST['id_forum']);
  $id_theme  = intval($_POST['id_theme']);
  $id_post   = intval($_POST['id_post']);

  if(empty($author))  $error[] = "Не указано имя";
  if(empty($message)) $error[] = "Сообщение не введено";
  if($sid_add_theme != $_POST['sid_add_theme']) $error[] = "Ошибка редактирования сообщения";
  // Подготавливаем переменные для добавления в SQL-запрос, экранируя
  // все спецсимволы при помощи функции mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $author  = mysql_escape_string($author);
    $pswrd   = mysql_escape_string($pswrd);
    $message = mysql_escape_string($message);
  }
  ///////////////////////////////////////////////////////////
  // Блок идентификации
  ///////////////////////////////////////////////////////////
  define("ADDMESSAGE",1);
  require_once("../utils/autreg.php");

  ///////////////////////////////////////////////////////////
  // Блок проверки корректности редактирования сообщения
  ///////////////////////////////////////////////////////////
  $query = "SELECT hide FROM $tbl_themes 
            WHERE id_theme = $id_theme";
  $idn = mysql_query($query);
  if(!$idn)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "Ошибка извлечения параметров темы");
  }
  if(mysql_num_rows($idn))
  {
    $hide =  mysql_result($idn,0);
    if($hide == 'lock') $error[] = "Тема закрыта, сообщения редактировать нельзя";
  } else $error[] = "Тема закрыта, сообщения редактировать нельзя";
  // Этого для защиты не достаточно, так как можно ответить в не закрытой теме
  // подставив пост закрытой - обрабатываем эту ситуацию
  $query = "SELECT * FROM $tbl_posts 
            WHERE id_post = $id_post";
  $idn = mysql_query($query);
  if(!$idn)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "Ошибка извлечения параметров темы");
  } 
  if(mysql_num_rows($idn))
  {
    $post = mysql_fetch_array($idn);
    $id_theme_check = $post['id_theme'];
    if($id_theme_check != $id_theme) $error[] = "Попытка редактирования ответа из другой темы";
    if($id_author != $post['id_author']) $error[] = "Нельзя редактировать чужую тему";
  } else $error[] = "Сообщение которое вы хотите редактировать не обнаружено";
  // Если ошибок нет - редактируем сообщение
  if(empty($error))
  {
    ///////////////////////////////////////////////////////////
    // Блок удаления старого изображения
    ///////////////////////////////////////////////////////////
    $update_path = "";
    // Если редактор в поле для изображения передаёт символ
    // "-" или другую картинку следует уничтожить предыдущую
    if (!empty($_FILES['attach']['name']) || !empty($_POST['delete_file']))
    {
      $query = "SELECT putfile, id_post 
                FROM $tbl_posts
                WHERE id_post = $id_post";
      $pct = mysql_query($query);
      if(!$pct)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении к таблице сообщений");
      } 
      if(mysql_num_rows($pct))
      {
        $file = mysql_result($pct,0);
        if(file_exists($file) && $file != "-") @unlink($file);
      }
      $update_path = " putfile = '', ";
    }
    ///////////////////////////////////////////////////////////
    // Блок загрузки файла на сервер
    ///////////////////////////////////////////////////////////
    require_once("../utils/loadfile.php");
    if(!empty($path)) $update_path = " putfile = '$path', ";
    ///////////////////////////////////////////////////////////
    // Блок обновления сообщения
    ///////////////////////////////////////////////////////////
    $query = "UPDATE $tbl_posts 
              SET $update_path
                  name = '$message'
              WHERE id_post = $id_post";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обновления сообщения");
    }
    // В случае успеха осуществляем автоматический переход
    // к теме     
    if($_POST['personally'] == 'set') $url = "personallyread.php?id_forum=$id_forum&id_theme=$id_theme";
    else $url = "read.php?id_forum=$id_forum&id_theme=$id_theme";
  
    @header("Location: $url");
    exit();
  }
?>