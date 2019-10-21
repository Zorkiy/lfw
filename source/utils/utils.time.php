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

  function get_last_time($current_author, $id_forum)
  {
    // Объявляем переменные с именами таблиц глобальными
    global $tbl_last_time, $tbl_authors;

    // Предотвращаем SQL-инъекцию
    $id_forum = intval($id_forum);

    // Ищем время последнего посещения раздела, 
    // по умолчанию, для неавторизованных 
    // пользователей - выводим новые сообщения
    // за последние два часа
    $forum_lasttime = date("Y-m-d H:i:s",time()-3600*2);
    // Если пользователь авторизован - извлекаем 
    // дату последнего посещения им текущего раздела
    if(!empty($current_author) && trim($current_author) != 'Посетитель')
    {
      $query = "SELECT $tbl_last_time.last_time$id_forum AS last_time,
                       UNIX_TIMESTAMP($tbl_last_time.now$id_forum) AS now_time
                FROM $tbl_last_time, $tbl_authors
                WHERE $tbl_authors.name='$current_author' AND
                      $tbl_authors.id_author = $tbl_last_time.id_author";
      $ath = mysql_query($query);
      if(!$ath)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при выборке 
                                  тем форума");
      }
      if(mysql_num_rows($ath))
      {
        $lsttime = mysql_fetch_array($ath);
        $forum_lasttime = $lsttime['last_time'];
        $forum_nowtime = $lsttime['now_time'];
        // Если с момента последнего посещения прошло больше 20 минут
        if((time() - $forum_nowtime)/60>20)
        {
          // Назначаем более новое время
          $forum_lasttime = date("Y-m-d H:i:s",$forum_nowtime);
        }
      }
    }

    return $forum_lasttime;
  }
  // Эта функция производит обновление времени последнего посещения
  // пользователя и устанавливает куки для определения является ли
  // сообщение новым. При каждом просмотре страницы форума происходит
  // обновление времени последнего посещения текущего автора, при этом 
  // текущее время сравнивается с предыдущим значением - если оно больше
  // 20 минут, кукису last_time присваиваем это старое значение, а в базу
  // заносим новое время. Это работает.
  // $author - имя пользователя
  // $enter - если true - принудительное обновление куков
  function settime($author, $enter, $id_forum)
  {
    // Объявляем переменные с именами таблиц глобальными
    global $tbl_last_time, $tbl_authors;

    // Предотвращаем SQL-инъекцию
    if(empty($id_forum)) $id_forum = 1;
    $id_forum = intval($id_forum);
    
    $query = "SELECT UNIX_TIMESTAMP($tbl_last_time.now$id_forum) AS now_time 
              FROM $tbl_authors, $tbl_last_time 
              WHERE $tbl_authors.name='$author' AND 
                    $tbl_authors.id_author = $tbl_last_time.id_author";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении к 
                                таблице авторов (settime)");
    }
    if(mysql_num_rows($ath))
    {
      $authr = mysql_fetch_array($ath);
      $temptime = (int)$authr['now_time'];

      // Если с момента последнего посещения прошло больше 20 минут  
      if((time() - $temptime)/60>20 || $enter)
      {
        // Устанавливаем новое время
        $query = "UPDATE $tbl_authors, $tbl_last_time 
                  SET $tbl_last_time.last_time$id_forum = '".date("Y-m-d H:i:s",$temptime)."'
                  WHERE $tbl_authors.name='$author' AND 
                        $tbl_authors.id_author = $tbl_last_time.id_author";
        if(!mysql_query($query))
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "Ошибка при обновлении времени
                                    последнего посещения (settime)");
        }
      }
      // И в любом случае обновляем 
      // время последнего посещения посетителя
      $query = "UPDATE $tbl_last_time, $tbl_authors 
                SET $tbl_last_time.now$id_forum = NOW() 
                WHERE $tbl_authors.name='$author' AND 
                      $tbl_authors.id_author = $tbl_last_time.id_author";
      if(!mysql_query($query))
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при обновлении времени
                                  последнего посещения (settime)");
      }
      $query = "UPDATE $tbl_authors 
                SET `time` = NOW() 
                WHERE name = '$author'";
      if(!mysql_query($query))
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "Ошибка при обновлении времени
                                  последнего посещения (settime)");
      }
    }
  }
  // $date - дата в текстовом формате (год, месяц, день, часы, минуты, недели)
  // $type - определяет в каком формате нужно вернуть дату
  // $type = 0 формат: день.месяц.год в часы:минуты
  function convertdate($date)
  {
    return strftime("%d.%m.%Y в %H:%M", strtotime($date));
  }
?>