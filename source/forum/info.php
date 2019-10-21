<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  // Бешкенадзе А.Г. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  Error_Reporting(E_ALL & ~E_NOTICE); 

  // Подключаем SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Функции для работы с сообщениями
  require_once("../utils/utils.posts.php");
  // Функции для обработки времени
  require_once("../utils/utils.time.php");

  try
  {
    // Определяем название страницы
    $nameaction="Информация о пользователе";
    // Включаем "шапку страницы"
    require_once("../utils/topforumaction.php");

    // Извлекаем информацию из строки запроса
    $id_author = intval($_GET['id_author']);
    $id_forum = intval($_GET['id_forum']);
    // Извлекаем информацию о посетителе
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_author
              LIMIT 1";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при извлечении
                                параметров автора");
    }
    if(mysql_num_rows($ath))
    {
      $author = mysql_fetch_array($ath);
      echo "<p class=linkbackbig><a href=index.php?id_forum=$id_forum>Вернуться назад</a></p>";
      require_once("../utils/include.info.php");
    }
    // Завершаем страницу
    require_once("../utils/bottomforumaction.php");
  }
  catch(ExceptionObject $exc) 
  {
    require_once("exception_object_debug.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>
