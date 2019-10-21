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
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $id_post  = intval($_GET['id_post']);

    if(!empty($_POST))
    {
      // Обработчик HTML-формы
      define("EDIT_POST", 1);
      $error = array();
      if($_REQUEST['delete_file'] == 'on') $_REQUEST['delete_file'] = "checked";
      else $_REQUEST['delete_file'] = "";
      require_once("../utils/include.edit_post.handler.php");
    }
    // Устанавливаем название страницы
    $nameaction = "Исправить сообщение";
    // Включаем "шапку" страницы
    include "../utils/topforumaction.php";  

    // Востановим текст сообщения, которое мы правим
    $query = "SELECT $tbl_posts.name AS name, 
                     $tbl_posts.url AS url,
                     $tbl_posts.putfile AS putfile 
              FROM $tbl_posts, $tbl_themes
              WHERE $tbl_themes.id_theme = $tbl_posts.id_theme AND
                    $tbl_themes.hide != 'hide' AND
                    $tbl_posts.hide != 'hide' AND 
                    $tbl_posts.id_post = $id_post";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при извлечении
                               сообщения");
    }
    if(mysql_num_rows($pst))
    {
      $posts = mysql_fetch_array($pst);
    }
    if(empty($_POST))
    {
      $_REQUEST = $posts;
      $message = $posts['name'];
    }
    ?>
    <p class=linkbackbig><a href=read.php?id_forum=<?php echo $id_forum; ?>&id_theme=<?php echo $id_theme; ?>>Вернуться к теме</a></p>
    <?php
    // Если имеются ошибки выводим их
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
    <form enctype='multipart/form-data' method=post name=form>
    <input type=hidden name=personally value='<?php echo htmlspecialchars($_GET['personally'], ENT_QUOTES); ?>'>
    <input type=hidden name=sid_add_theme value='<?php echo $sid_add_theme; ?>'>
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
    <td >
        <div class="blockremark">
        <p class=texthelp>
        <a href=# onClick="javascript:click_link()" href=#>Цитировать</a><br><br>       
        Используйте тэги для выделения текста:<br>
        Код: <a href=# onClick="javascript:tag('[code]', '[/code]')" href=#>[code][/code]</a><br>
        Жирный: <a href=# onClick="javascript:tag('[b]', '[/b]')" href=#>[b][/b]</a><br>
        Наклонный: <a href=# onClick="javascript:tag('[i]', '[/i]')" href=#>[i][/i]</a><br>
        URL: <a href=# onClick="javascript:tag('[url]', '[/url]')" href=#>[url][/url]</a><br>                
    </td></tr>
    </table>    
    <table>
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
