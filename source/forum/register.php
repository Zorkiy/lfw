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
    // Загружаем настройки форума
    $settings = get_settings();
    // Предотвращаем SQL-инъекцию
    $id_forum = intval($_GET['id_forum']);

    if(!empty($_POST))
    {
      // Обработчик HTML-формы
      define("REGISTER", 1);
      $error = array();
      require_once("../utils/include.register.handler.php");
      if($_REQUEST['sendmail'] == 'on') $_REQUEST['sendmail'] = "checked";
      else $_REQUEST['sendmail'] = "";
    }

    // Устанавливаем название страницы
    $nameaction = "Регистрация на форуме";
    // Выводим "шапку" страницы
    require_once("../utils/topforumaction.php");

    if(!isset($action)) $action = "register.php";
    if(!isset($button)) $button = "Зарегистрироваться";
    $id_forum = $_GET['id_forum'];
    ?>
    <p class=linkbackbig><a href=index.php?id_forum=<?php echo htmlspecialchars($id_forum, ENT_QUOTES); ?>>Вернуться к списку тем</a></p>
    <?php
      // Если имеются ошибки выводим их
      if(!empty($error))
      {
        echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
      }
    ?>
    <form enctype='multipart/form-data' action='<?php echo htmlspecialchars($action, ENT_QUOTES); ?>' method=post>
    <table border="0" width="100%" cellpadding="0" cellspacing="0"><tr valign="top"><td>
    <table>
    <tr>
      <td><p class="fieldname">Имя: *</td>
      <td><input size=25 class=input type=text name=author maxlength=100 size=61 value='<?php echo htmlspecialchars($_POST['author'], ENT_QUOTES); ?>'></td></tr>
    <tr>
      <td><p class="fieldname">Пароль: *</td>
      <td><input size=25 class=input type=password name=pswrd maxlength=100 size=61 value='<?php echo htmlspecialchars($_POST['pswrd'], ENT_QUOTES); ?>'></td></tr>
    <tr>
      <td><p class="fieldname">Повтор пароля: *</td>
      <td><input size=25 class=input type=password name=pswrd_again maxlength=100 size=61 value='<?php echo htmlspecialchars($_POST['pswrd_again'], ENT_QUOTES); ?>'></td></tr>
    </table>    
    </td>
    <td>
      <div class="blockremark">
      <p class=texthelp>Для регистрации заполните необходимые данные и нажмите кнопку "Зарегистрировать".
      Обязательные поля отмечены звездочкой (*).</p></div>
    </td>
    </tr>
    </table>        
    <?php
      require_once("../utils/include.register.php");
    ?>
    <input type=hidden name=id_author value='<?php echo htmlspecialchars($id_author, ENT_QUOTES); ?>'>
    <input type=hidden name=id_forum value='<?php echo htmlspecialchars($id_forum, ENT_QUOTES); ?>'>
    </form>
    <?php
    // Выводим завершение страницы
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
