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
  // Функции для работы со временем
  require_once("../utils/utils.time.php");

  // Устанавливаем кодировку windows-1251
  header("Content-Type: text/html; charset=windows-1251");
  // Извлекаем имя посетителя из cookie
  $current_author = $_COOKIE['current_author'];
  // Если отключён режим магических кавычек - 
  // экранируем спец-символы
  if (!get_magic_quotes_gpc())
  {
    $current_author = mysql_escape_string($current_author);
  }
  // Загружаем настройки форума
  $settings = get_settings();

  // Устанавливаем название форума. Если странице не будет
  // передаваться название - будет подставляться это значение
  $titleall = $settings['name_forum'];
  if(empty($title))
  {
    // Предотвращаем SQL-инъекцию
    $id_forum = intval($_GET['id_forum']);
    // Извлекаем название форума 
    $query = "SELECT name FROM $tbl_forums
              WHERE id_forum = $id_forum AND
                    hide != 'hide'";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при выборке 
                               названия форума");
    }
    if(mysql_num_rows($frm)) $title = @mysql_result($frm, 0);
  }

  if (!isset($title)) $title = $titleall;
  // Выясняем название директории, где хранится скин
  if(empty($_COOKIE['skin']))
  {
  	$skin = "../skins/".$settings['skin']."/";
  }
  else
  {
    $_COOKIE['skin'] = str_replace(".","",$_COOKIE['skin']);
    $_COOKIE['skin'] = str_replace("/","",$_COOKIE['skin']);
    $_COOKIE['skin'] = htmlspecialchars($_COOKIE['skin']);
  	$skin = "../skins/".$_COOKIE['skin']."/";
  }
  // Выясняем дату последнего вхождения и выводим приветствие
  if(!empty($current_author))
  {
    settime($current_author, false, $id_forum);
  }
  else $current_author = " Посетитель";
  
  if (($showforumsline || $readforumline) && $settings['show_forum_switch'] == 'yes') $shownewpost=true;  
  else $shownewpost=false;
  
  if($showforumsline && $settings['show_forum_switch'] == 'yes') $show_switch_forum=true;
  else $show_switch_forum=false;

?>
<html>
<head>
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv="imagetoolbar" content="no">
<meta name="description" content="<? echo htmlspecialchars($title, ENT_QUOTES); ?>">
<meta name="keywords" content="<? echo htmlspecialchars($title, ENT_QUOTES); ?>">
<title><? echo str_replace("\"","",$title); ?></title>
<link rel="StyleSheet" type="text/css" href="<?php echo $skin; ?>forum.css">
<link rel="StyleSheet" type="text/css" href="<?php echo $skin; ?>mainstyles.css">
<link href="xml.xml" rel="alternate" type="application/rss+xml" title="RSS-канал новых тем" />
<?php
 if (basename($_SERVER['PHP_SELF']) == "read.php" ||
     basename($_SERVER['PHP_SELF']) == "personallyread.php") { ?>
<link rel="StyleSheet" type="text/css" href="<?php echo $skin; ?>read.css">
<?php } ?>
</head>  
<?php
  include $skin."diztop.php";
?>