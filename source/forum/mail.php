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
    // Извлекаем значения параметров из строки запроса
    $id_forum  = intval($_GET['id_forum']);
    $id_theme  = intval($_GET['id_theme']);
    $id_author = intval($_GET['id_author']);
    // Извлекаем данные автора из базы данных
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_author";
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
      if(trim($author['email']) == "" || $author['email'] == "-") 
      {
        $error[] = "У данного автора отсутствует электронный адрес";
      }
    }

    if(!empty($_POST))
    {
      // Обработчик HTML-формы
      define("MAIL", 1);
      $error = array();
      require_once("../utils/include.mail.handler.php");
    }

    // Устанавливаем название страницы  
    $nameaction="Написать письмо";
    // Выводим "шапку" страницы
    include "../utils/topforumaction.php"; 

    if (empty($return)) $return = $_SERVER["HTTP_REFERER"];  
    ?>
    <p class=linkbackbig><a href='<? echo $return ?>'>Вернуться в форум</a></p>
    <?php
    // Если имеются ошибки выводим их
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
<form action=mail.php method=post>
<table>
<tr><td><p class="fieldname">Тема:</td><td><input class=input type=text name=theme maxlength=200 size=61></td></tr>
<tr><td><p class="fieldname">Сообщение:</td><td><textarea class=input cols=63 rows=10 name=message></textarea></td></tr>
<tr><td>&nbsp;</td><td><input class=button type=submit name=send value=Отправить></td></tr>
<input type=hidden name=id_author value=<? echo $id_author; ?>>
<input type=hidden name=id_theme value=<? echo $id_theme; ?>>
<input type=hidden name=id_forum value=<? echo $id_forum; ?>>
</table>
</form>
<?php
    // Выводим завершение страницы
    include "../utils/bottomforumaction.php"; 
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
