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
    $nameaction = "Список участников форума в \"OnLine\"";
    // Включаем "шапку" страницы
    include "../utils/topforumaction.php"; 

    // Извлекаем из строки запроса первичный ключ
    // форума $id_forum
    $id_forum = intval($_GET['id_forum']);
    ?>
     <p class=linkbackbig><a href="javascript: history.back()">Вернуться назад</a></p>         
     <table class="tablen" width="100%" border="0" cellspacing="1" cellpadding="3">
     <tr>
        <td class=tableheadern><p class="fieldname">Автор</td>
        <td class=tableheadern><p class="fieldname">Время посещения форума</td>
     </tr>
     <?php
     // Выводим участников, которые зашли на форум менее 10 минут назад
     $query = "SELECT * FROM $tbl_authors
               WHERE `time` > NOW() - INTERVAL '10' minute
               ORDER BY time DESC";
     $ath = mysql_query($query);
     $count = 0;
     if(!$ath)
     {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при выборке посетителей OnLine");
     }
     if(mysql_num_rows($ath))
     {
       $count += mysql_num_rows($ath);
       while($authors = mysql_fetch_array($ath))
       {
         echo "<tr class=trtablen>
               <td><p class=authorreg><nobr><a class=authorreg href=info.php?id_forum=$id_forum&id_author=".$authors['id_author'].">".htmlspecialchars($authors['name'])."</a></nobr></p></td>
               <td><p class=texthelp align=center>".convertdate($authors['time'],0)."</p></td>
               </tr>";
       }
     }
     // Выводим участников, которые зашли на форум в интервале
     // от 10 до 20 минут назад (уходящие)
     $query = "SELECT * FROM $tbl_authors
               WHERE `time` > NOW() - INTERVAL '20' minute AND
                     `time` < NOW() - INTERVAL '10' minute
               ORDER BY time DESC";
     $ath = mysql_query($query);
     if(!$ath)
     {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при выборке посетителей OnLine");
     }
     if(mysql_num_rows($ath))
     {
       $count += mysql_num_rows($ath);
       while($authors = mysql_fetch_array($ath))
       {
         echo "<tr class=trtablen>
               <td><p class=authorreg><nobr><a class=authorhide href=info.php?id_forum=$id_forum&id_author=".$authors['id_author'].">".htmlspecialchars($authors['name'])."</a></nobr></p></td>
               <td><p class=texthelp align=center>".convertdate($authors['time'],0)."</p></td>
               </tr>";
       }
     }
     echo "<tr class=trtablen>
           <td><p class=texthelp><nobr>Всего человек OnLine</nobr></p></td>
           <td><p class=texthelp align=center>$count</p></td>
           </tr>";

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