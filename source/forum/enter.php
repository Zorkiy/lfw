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
    // Устанавливаем управляющие переменные HTML-формы  
    $nameaction = "Вход на форум (авторизация)";
    $auth = $_COOKIE['current_author'];
    $pass = $_COOKIE['wrdp'];
    if(empty($_GET['id_forum'])) $_GET['id_forum'] = 1;
    $id_forum = intval($_GET['id_forum']);

    if(!empty($_POST))
    {
      // Обработчик HTML-формы
      define("ENTER", 1);
      $error = array();
      require_once("../utils/include.enter.handler.php");
    }

    $nameaction = "Вход";
    // Выводим "шапку" страницы
    include "../utils/topforumaction.php";  
    echo "<p class=linkbackbig><a href='index.php?id_forum?id_forum=$id_forum'>Вернуться в форум</a></p>";
    // Если имеются ошибки выводим их
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
      <div class="blockremark">
      <p class=texthelp>Для авторизации Вам необходимо ввести ваше имя и пароль.
      Пройдите <a href="register.php?id_forum=<?php echo $id_forum; ?>">регистрацию</a>, если вы еще не зарегистрированы на форуме.
      </p></div>

      <table>
      <form method=post>
      <tr><td><p class="fieldname">Имя:</td><td><input class=input type=text name=author maxlength=100 size=50 value='<?php echo htmlspecialchars($auth); ?>'></td></tr>
      <tr><td><p class="fieldname">Пароль:</td><td><input class=input type=password name=pswrd maxlength=100 size=50 value='<?php echo htmlspecialchars($pass); ?>'></td></tr>
      <tr><td>&nbsp;</td><td><input class=button type=submit value="Войти"></td></tr>
      <input type=hidden name=id_forum value='<?php echo $id_forum; ?>'>
      </form>
      </table>
    <?php
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
