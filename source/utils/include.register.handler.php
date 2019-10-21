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

  if(!defined("REGISTER")) return;

  ///////////////////////////////////////////////////////////
  // Блок подготовки и проверки
  ///////////////////////////////////////////////////////////
  // Получаем данные отправленные методом POST
  $author      = trim($_POST['author']);
  $pswrd       = $_POST['pswrd'];
  $pswrd_again = $_POST['pswrd_again'];
  $email       = trim($_POST['email']);
  $icq         = trim($_POST['icq']);
  $url         = trim($_POST['url']);
  $about       = trim($_POST['about']);
  $sendmail    = $_POST['sendmail'];
  $id_forum    = intval($_POST['id_forum']);
  // Подготавливаем переменные для добавления в SQL-запрос, экранируя
  // все спецсимволы при помощи функции mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $email       = mysql_escape_string($email);
    $author      = mysql_escape_string($author);
    $pswrd       = mysql_escape_string($pswrd);
    $pswrd_again = mysql_escape_string($pswrd_again);
    $about       = mysql_escape_string($about);
    $message     = mysql_escape_string($message);
    $url         = mysql_escape_string($url);
    $sendmail    = mysql_escape_string($sendmail);
  }
  // Проверяем правильность ввода данных
  if(empty($author)) $error[] = "Не указано имя";
  if(strlen($author) > 20) $error[] = "Слишком длинное имя";
  if(empty($pswrd) || empty($pswrd_again) || $pswrd != $pswrd_again) $error[] = "Ошибка в паролях";

  // Проверяем кооректность ввода e-mail
  if($settings['user_email_required'] == 'yes')
  {
    if (!preg_match("/^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$/i", $email))
    {
      $error[] = "Введите e-mail в виде <i>something@server.com</i>";
    }
  }
  else if(!empty($email))
  {
    if (!preg_match("/^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$/i", $email))
    {
      $error[] = "Введите e-mail в виде <i>something@server.com</i>";
    }
  }

  if(!empty($icq))
  {
    if(!preg_match("|^[\d]+$|",$icq)) $error[] = "Введите номер ICQ в виде числа";
  }

  // Проверяем нужно ли отправлять письмо автору при добавлении
  // новой темы
  if(!empty($email))
  {
    if($sendmail == "on") $sendmail = 'yes';
    else $sendmail = 'no';
  } else $sendmail = 'no';

  // Проверяем не зарегистрировано ли уже имя в базе данных
  $err = check_user($author);
  if(!empty($err)) $error[] = $err;

  ///////////////////////////////////////////////////////////
  // Блок загрузки файла на сервер
  ///////////////////////////////////////////////////////////
  $url_photo = "";
  // Если поле выбора фотографии не пустое,
  // закачиваем её на сервер и переименовываем
  if (!empty($_FILES['photo']['tmp_name']))
  {
    // Проверяем не больше ли файл 512 Кб
    if($_FILES['photo']['size'] > $settings['size_photo'])
    {
      $error[] = "Слишком большая фотография (более ".valuesize($settings['size_photo']).")";
    }
    else 
    {
      // Извлекаем из имени файла расширение
      $ext = strrchr($_FILES['photo']['name'], "."); 
      // Разрешаем загружать файлы только определённого форматм
      $extentions = array(".jpg",".gif");
      // Формируем путь к файлу    
      if(in_array($ext, $extentions))
      {
        $path = "photo/".date("YmdHis",time()).$ext; 
        // Перемещаем файл из временной директории сервера в
        // директорию /photo Web-приложения
        if (copy($_FILES['photo']['tmp_name'], $path))
        {
          // Уничтожаем файл во временной директории
          unlink($_FILES['photo']['tmp_name']);
          // Изменяем права доступа к файлу
          chmod($path, 0644);
          $url_photo = $path;
        }
      }
    }
  }
  
  // Если ошибок нет - регистрируем пользователя
  if(empty($error))
  {
    // Формируем запрос SQL-оператор INSERT для
    // добавления нового зарегистрированного посетителя
    $query = "INSERT INTO $tbl_authors VALUES(
             NULL,
             '$author',
             ".get_password($pswrd).",
             '$email',
             '$sendmail',
             '$url',
             '$icq',
             '$about',
             '$url_photo',
             NOW(),
             NOW(),
             0,
             '$status')";
    
    if(!mysql_query($query))
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при регистрации 
                                посетителя");
    }
  
    // Выставляем текущее время, в качестве времени последнего
    // посещения
    $query = "SELECT * FROM $tbl_last_time LIMIT 1";
    $lst = mysql_query($query);
    if(!$lst)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при извлечении 
                                списка форумов");
    }
    $num = mysql_num_fields($lst);
    $num = $num - 1;
    for($i = 0; $i<$num; $i++) $arr[] = 'NOW()';
  
    $query = "INSERT INTO $tbl_last_time 
              VALUES(".mysql_insert_id().",".implode(',',$arr).")";
    if(!mysql_query($query))
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при регистрации 
                                посетителя");
    }
    // Осуществляем автоматический "вход" на форум
    setallcookie($author, $pswrd);
    // Обновляем дату последнего вхождения
    settime($author, false, $id_forum);
    // Осуществляем автоматический переход на главную страницу форума
    @header("Location: index.php?id_forum=$id_forum");
    exit();
  }
?>