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

  // Настройки форума
  require_once("../utils/utils.settings.php");
  // Функции для работы с пользователями
  require_once("../utils/utils.users.php");

  // Извлекаем имя посетителя из cookie
  $new = $author_themes ="";
  if(isset($_COOKIE['current_author']))
  {
    $current_author = $_COOKIE['current_author'];
    $wrdp = $_COOKIE['wrdp'];
    if (!get_magic_quotes_gpc())
    {
      $current_author = mysql_escape_string($current_author);
      $wrdp = mysql_escape_string($wrdp);
    }

    // Если личные сообщения включены - проверяем
    // имеется ли для данного посетителя новые сообщения
    // предварительно проводим авторизацию
    $query = "SELECT * FROM $tbl_authors 
              WHERE name = '$current_author' AND
                    passw = ".get_password($wrdp)." AND
                    statususer != 'wait'";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка аутентификации");
    }
    // Если имеется запись, следовательно, посетитель зарегистрирован
    if(mysql_num_rows($ath))
    {
      // Если включены личные сообщения - выводим ссылку на них
      if($settings['show_personally'] == 'yes')
      {
          $new = "<a href=personally.php>(личные сообщения)</a>";
      }
      $author = mysql_fetch_array($ath);
      $author_themes = "(<a title=\"Инициированные вами темы\" href=authorthmes.php?id_author=$author[id_author]&id_forum=$_GET[id_forum]>мои темы</a>, ".
                       "<a title=\"Последние темы, в которых вы принимали участие\" href=authorlstthm.php?id_author=$author[id_author]&id_forum=$_GET[id_forum]>последние темы</a>)";
    }
  }
  else
  {
    $current_author = "Посетитель";
  }
?>
<p class=salutation><?php echo $settings['hello']." ".htmlspecialchars($current_author, ENT_QUOTES)."! $new $author_themes"; ?></p>