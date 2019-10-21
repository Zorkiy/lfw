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
  ///////////////////////////////////////////////////
  // Проверка правильности логина и пароля
  ///////////////////////////////////////////////////
  if(!defined("ADDMESSAGE")) return;

  // По умолчанию считаем, что посетитель не зарегистрирован
  $id_author = 0;
  // Если идентификация проходит успешно, "входим" на форум
  // а переменной $id_author присваиваем значение первичного
  // ключа записи таблицы authors
  if(!empty($pswrd))
  {
    $auth = get_user($author, $pswrd);
    if(!$auth)
    {
      $error[] = "Ошибка идентификации, пароль не 
                     соответствует логину";
    }
    else
    {
      // За одно осуществляем вход на форум этого пользователя
      setallcookie($auth['name'], $pswrd);
      $id_author = $auth['id_author'];
    }
  }
  else
  {
    if($settings['registration_required'] == 'yes')
    {
      $error[] = "Добавлять темы и сообщения могут только 
                  зарегистрированные посетители, пожалуйста 
                  зарегистрируйтесь";
    }
    $err = check_user($author);
    if(!empty($err)) $error[] = $err;
  }
?>