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

  if(!defined("MAIL")) return;

  ///////////////////////////////////////////////////////////
  // Блок подготовки и проверки
  ///////////////////////////////////////////////////////////
  // Получаем данные отправленные методом POST
  $id_forum  = intval($_POST['id_forum']);
  $id_theme  = intval($_POST['id_theme']);
  $id_post   = intval($_POST['id_post']);
  $id_author = intval($_POST['id_author']);

  if (!get_magic_quotes_gpc())
  {
    $_COOKIE['current_author'] = mysql_escape_string($_COOKIE['current_author']);
  }
  $theme = trim($_POST['theme']);
  $message = trim($_POST['message']);
  $message = "От ".$_COOKIE['current_author']."<br>\n".nl2br($message);

  // Проверяем введённые данные на соответствие
  if(empty($theme)) $error[] = "Тема сообщения не введена";
  if(empty($message)) $error[] = "Сообщение не введено";
  $query = "SELECT * FROM $tbl_authors 
            WHERE id_author = $id_author";
  $ath = mysql_query($query);
  if(!$ath)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "Ошибочный запрос к таблице авторов");
  }
  if(mysql_num_rows($ath))
  {
    $author = mysql_fetch_array($ath);
    if(trim($author['email']) == "" || $author['email'] == "-")
    {
      $error[] = "Электронный адрес отсутствует";  
    }
    $theme =  convert_cyr_string(stripslashes($theme),'w','k'); 
    $message =  convert_cyr_string(stripslashes($message),'w','k'); 
    $header = "Content-Type: text/html; charset=KOI8-R\r\n";
    if(!empty($_COOKIE['current_author']))
    {
      $query = "SELECT email FROM $tbl_authors
                WHERE name = '$_COOKIE[current_author]'";
      $ath = mysql_query($query);
      if (!$ath)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка извлечения параметров пользователя");
      }
      if(mysql_num_rows($ath)) $email = mysql_result($ath,0);
      if(!empty($email)) $email = "<$email>";
      $header .= "From: $_COOKIE[current_author] $email\r\n";
      $header .= "\r\n";
      if(@mail($author['email'], $theme, $message, $header))
      {
        if(!empty($id_theme) && !empty($id_post))
        {
          @header("Location: read.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post");
          exit();
        }
        else
        {
          @header("Location: index.php?id_forum=$id_forum");
          exit();
        }
      } else $error[] = "К сожалению, письмо не было отправлено";  
    } else $error[] = "Письмо может отправить только зарегистрированный посетитель";  
  }
?>