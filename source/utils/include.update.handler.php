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

  if(!defined("UPDATE")) return;

  ///////////////////////////////////////////////////////////
  // Блок подготовки и проверки
  ///////////////////////////////////////////////////////////
  // Получаем данные отправленные методом POST
  $pswrd       = $_POST['pswrd'];
  $pswrd_new   = $_POST['pswrd_new'];
  $pswrd_again = $_POST['pswrd_again'];
  $email       = trim($_POST['email']);
  $icq         = trim($_POST['icq']);
  $url         = trim($_POST['url']);
  $about       = trim($_POST['about']);
  $sendmail    = $_POST['sendmail'];
  $id_author   = intval($_POST['id_author']);
  $id_forum    = intval($_POST['id_forum']);
  // Подготавливаем переменные для добавления в SQL-запрос, экранируя
  // все спецсимволы при помощи функции mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $email       = mysql_escape_string($email);
    $author      = mysql_escape_string($author);
    $pswrd       = mysql_escape_string($pswrd);
    $pswrd_new   = mysql_escape_string($pswrd_new);
    $pswrd_again = mysql_escape_string($pswrd_again);
    $about       = mysql_escape_string($about);
    $message     = mysql_escape_string($message);
    $url         = mysql_escape_string($url);
    $sendmail    = mysql_escape_string($sendmail);
  }
  // Проверяем правильность ввода данных
  if(empty($author)) $error[] = "Не указано имя";
  if(strlen($author) > 20) $error[] = "Слишком длинное имя";
  if($pswrd_new != $pswrd_again) $error[] = "Ошибка в паролях";

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

  // Блок идентификации
  $auth = get_user($author, $pswrd);
  if(!$auth) $error[] = "Пароль не соответствует логину";

  $url_photo = "";
  // Проверяем не удаляется ли изображение
  if(!empty($_REQUEST['delete_photo']) || !empty($_FILES['photo']['tmp_name']))
  {
    @unlink($auth['photo']);
    $url_photo = "photo = '',";
  }
  
  // Блок загрузки файла на сервер
  // Если поле выбора фотографии не пустое,
  // закачиваем её на сервер и переименовываем
  if(!empty($_FILES['photo']['tmp_name']))
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
        $path="photo/".date("YmdHis",time()).$ext; 
        // Перемещаем файл из временной директории сервера в
        // директорию /photo Web-приложения
        if (copy($_FILES['photo']['tmp_name'], $path))
        {
          // Уничтожаем файл во временной директории
          unlink($_FILES['photo']['tmp_name']);
          // Изменяем права доступа к файлу
          chmod($path, 0644);
          $url_photo = " photo = '$path',";
        }
      }
    }
  }
  // Если ошибок нет - обновляем регистрационные данные пользователя
  if(empty($error))
  {
    if(!empty($pswrd_new))
    {
      $password = "passw = ".get_password($pswrd_new).",";
      $pswrd = $pswrd_new;
    }
    else $password = "";
    ///////////////////////////////////////////////////////////
    // Блок формирования и выполнения SQL-запроса
    ///////////////////////////////////////////////////////////
    // Формируем SQL-запрос на добавление информации
    $query = "UPDATE $tbl_authors
              SET $password
                  email = '$email',
                  sendmail = '$sendmail',
                  url = '$url',
                  icq = '$icq',
                  $url_photo
                  about = '$about'
              WHERE id_author = $auth[id_author]";
    if(!mysql_query($query))
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обновлении
                                регистрационных данных
                                посетителя");
    }
    // Заходим на форум
    setallcookie($author, $pswrd);
    // Перенаправляем пользователя в его профиль
    @header("Location: info.php?id_author=$auth[id_author]&id_forum=1");
    exit();
  }
?>