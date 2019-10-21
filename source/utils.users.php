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

  function enter($name, $password)
  {
    // Объявляем название таблицы глобальным
    global $tbl_users;
    // Проверяем, соответствует ли логин паролю
    // и если соответствует - осуществляем авторизацию
    $query = "SELECT * FROM $tbl_users
              WHERE name = '$name' AND
                    pass = '$password' AND
                    block = 'unblock'
              LIMIT 1";
    $usr = mysql_query($query);
    if(!$usr)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                               "Ошибка аутентификации");
    }
    if(mysql_num_rows($usr))
    {
      $user = mysql_fetch_array($usr);
      // Вход на сайт
      $_SESSION['name'] = $user['name'];
      $_SESSION['id_user_position'] = $user['id_position'];
      // Обновляем дату последнего посещения пользователя
      $query = "UPDATE $tbl_users
                SET lastvisit = NOW()
                WHERE id_position = $user[id_position]";
      if(!mysql_query($query))
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                 "Ошибка аутентификации");
      }
      // Возвращаем признак успешной аутентификации
      return true;
    }
    // Возвращаем признак неудачной аутентификации
    else return false;
  }
  function user($id_position)
  {
    // Объявляем имя таблицы $tbl_users глобальным
    global $tbl_users;
    // Предотвращаем SQL-инъекцию
    $id_position = intval($id_position);
    // Извлекаем параметры пользователя
    $query = "SELECT * FROM $tbl_users
              WHERE id_position = $id_position AND 
                    block = 'unblock'
              LIMIT 1";
    $usr = mysql_query($query);
    if(!$usr) 
    {
      throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "Ошибка извлечения параметров пользователя");
    }
    if(mysql_num_rows($usr)) return mysql_fetch_array($usr);
    else return 0;
  }
  function remember($name)
  {
    // Объявляем имя таблицы $tbl_users глобальным
    global $tbl_users;
    // Формируем SQL-запрос на извлечение пользовательских данных
    $query = "SELECT * FROM $tbl_users 
              WHERE name = '$name'";
    $usr = mysql_query($query);
    if(!$usr)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                               "Ошибка при восстановлении пароля");
    }
    // Извлекаем e-mail пользователя
    $user = mysql_fetch_array($usr);

    $thm =  convert_cyr_string("Восстановление пароля",'w','k'); 
    $msg = "Восстановление пароля на сайте\r\n".
           "Логин - $user[name] \r\n".
           "Пароль - $user[pass] \r\n";
    $msg =  convert_cyr_string(stripslashes($msg),'w','k'); 
    $header = "Content-Type: text/plain; charset=KOI8-R\r\n\r\n";
    // Если на странице администрирования указан
    // адрес отсылки сообщения - отправляем письмо
    @mail($user['email'], $thm, $msg, $header);
  }
?>