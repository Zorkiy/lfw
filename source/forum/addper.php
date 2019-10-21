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
  session_start();
  $sid_add_theme = session_id();
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // Подключаем SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Функции для работы со временем
  require_once("../utils/utils.time.php");
  // Функции для работы с сообщениями
  require_once("../utils/utils.posts.php");
  // Настройки форума
  require_once("../utils/utils.settings.php");
  // Функции для работы с пользователями
  require_once("../utils/utils.users.php");
  // Функция для работы с файлами
  require_once("../utils/utils.files.php");

  try
  {
    // Извлекаем параметры из строки запроса
    $id_forum  = intval($_GET['id_forum']);
    $id_theme  = intval($_GET['id_theme']);
    $id_post   = intval($_GET['id_post']);
    $id_addresser = intval($_GET['id_author']);
  
    if(!empty($_POST))
    {
      // Обработчик HTML-формы
      define("ADD_PERSONALLY", 1);
      $error = array();
      require_once("../utils/include.add_personally.handler.php");
    }

    // Извлекаем имя автора, которому адресовано сообщение
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_addresser";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибочный запрос к 
                               таблице авторов");
    }
    if(mysql_num_rows($ath) <= 0)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Автор отсутствует в 
                               списках зарегистрированных авторов");
    }
    $author = mysql_fetch_array($ath);

    // Задаём название
    $nameaction = "Новое личное сообщение для ".htmlspecialchars($author['name']);
    // Выводим "шапку" страницы
    include "../utils/topforumaction.php";
  ?>
  <p class=linkbackbig><a href=# onClick='history.back()'>Вернуться</a></p>
    <?php
    // Если имеются ошибки выводим их
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
  <form enctype='multipart/form-data' name='form' action=addper.php method=post>
  <input type=hidden name=sid_add_theme value='<?php echo $sid_add_theme; ?>'>
  <input type=hidden name=id_addresser value='<?php echo $id_addresser; ?>'>
  <table border="0" width="100%"><tr valign="top"><td>
  <table border="0" >
    <tr valign="top">
      <td><p class="fieldname">Имя:</td>
      <td><input size=25 class=input type=text name=author size=61 maxlength=100 value='<?php echo htmlspecialchars($_COOKIE['current_author'], ENT_QUOTES); ?>'></td></tr>
    <tr>
      <td><p class="fieldname">Пароль:</td>
      <td><input size=25 class=input type=password name=pswrd size=61 maxlength=100 value='<?php echo htmlspecialchars($_COOKIE['wrdp'], ENT_QUOTES); ?>'></td></tr>
        </table>
    </td>
    <td>
        <div class="blockremark">
        <p class=texthelp>
        <a href=# onClick="javascript:click_link()" href=#>Цитировать</a><br><br>       
        Используйте тэги для выделения текста:<br>
        Код: <a href=# onClick="javascript:tag('[code]', '[/code]'); return false;">[code][/code]</a><br>
        Жирный: <a href=# onClick="javascript:tag('[b]', '[/b]'); return false;" >[b][/b]</a><br>
        Наклонный: <a href=# onClick="javascript:tag('[i]', '[/i]'); return false;">[i][/i]</a><br>
        URL: <a href=# onClick="javascript:tag('[url]', '[/url]'); return false;" >[url][/url]</a><br>                
       </div>
    </td></tr>
  </table>    
  <table border="0" width="100%">
  <tr><td><p class="fieldname">Тема:</td><td><input class=input type=text name=theme size=74 maxlength=150 value='<?php echo htmlspecialchars($theme, ENT_QUOTES); ?>'></td></tr>
  <?php
     include "../utils/include.add_message.php";
  ?>
  </table>
  </form>
  <?php
    // Выводим завершение страницы
    include "../utils/bottomforumaction.php";
    include "../utils/forum.js";
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