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

  // Функция для корректного шифрования пароля
  function get_password($pass)
  {
    $settings = get_settings();

    switch($settings['type_crypt'])
    {
      case 'PASSWORD':
        return "PASSWORD('$pass')";
      case 'OLD_PASSWORD':
        return "OLD_PASSWORD('$pass')";
      case 'MD5':
        return "MD5('$pass')";
      case 'PLAIN':
        return "'$pass'";
    }
  }

  // Проверяем не существует ли уже пользователя
  // с таким именем
  function check_user($author)
  {
    // Объявляем переменные с именами таблиц глобальными
    global $tbl_authors;
    ///////////////////////////////////////////////////////////
    // Блок проверки регистрации имени
    ///////////////////////////////////////////////////////////
    // Выясняем не зарегистрировано ли уже это имя
    // Возможно три ситуации, которые необходимо предотвратить:
    // 1. Вводится ник, полностью совпадающий с уже существующим
    // 2. Вводится уже существующий кирилический ник, в котором
    //    одна или несколько букв заменены на латинские
    // 3. Вводится уже существующий латинский ник, в котором
    //    одна или несколько букв заменениы на кирилические
    // Массив кирилических букв
    $rus = array("А","а","В","Е","е","К","М","Н","О","о","Р","р","С","с","Т","Х","х");
    // Массив латинских букв
    $eng = array("A","a","B","E","e","K","M","H","O","o","P","p","C","c","T","X","x");
    // Заменяем русские буквы латинскими
    $eng_author = str_replace($rus, $eng, $author); 
    // Заменяем латинские буквы русскими
    $rus_author = str_replace($eng, $rus, $author); 
    // Формируем SQL-запрос
    $query = "SELECT * FROM $tbl_authors 
              WHERE name LIKE '$author' OR
                    name LIKE '$eng_author' OR
                    name LIKE '$rus_author'";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при регистрации 
                                посетителя");
    }
    if(mysql_num_rows($ath)) return "К сожалению, данное имя уже зарегистрировано. Поробуйте другое.";
    else return "";
  }

  // Получить данные пользователя
  function get_user($author, $pswrd)
  {
    // Объявляем переменные с именами таблиц глобальными
    global $tbl_authors;

    // Извлекаем данные 
    $query = "SELECT * FROM $tbl_authors 
              WHERE name = '$author' AND 
                  passw = ".get_password($pswrd)." AND
                  statususer != 'wait'";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении 
                                к таблице авторов");
    }
    if(mysql_num_rows($ath)) return mysql_fetch_array($ath);
    else return false;
  }

  // Устанавливаем cookie для входа на форум
  function setallcookie($author, $wrdp)
  {
    $settings = get_settings();
    $tmppos = strrpos($_SERVER['PHP_SELF'],"/") + 1;
    $path = substr($_SERVER['PHP_SELF'], 0, $tmppos);
    setcookie("current_author", $author, time() + 3600*24*$settings['cooktime'],$path);
    setcookie("wrdp", $wrdp, time() + 3600*24*$settings['cooktime'],$path);
    if(isset($_COOKIE['lineforum']))
    {
      setcookie("lineforum", "set_line_forum", time() + 3600*24*$settings['cooktime'], $path);
    }
    if(isset($_COOKIE['lineforumdown']))
    {
      setcookie("lineforumdown", "set_line_forum_down", time() + 3600*24*$settings['cooktime'], $path);
    }
  }

  // Эта функция сбрасывает куки для выхода с форума
  function cleanallcookie()
  {
    $tmppos = strrpos($_SERVER['PHP_SELF'],"/") + 1;
    $path = substr($_SERVER['PHP_SELF'], 0, $tmppos);
    setcookie("current_author", "", 0, $path);
    setcookie("wrdp", "", 0, $path);
    setcookie("lineforum", "", 0);
    setcookie("lineforumdown", "", 0);
  }
?>