<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // ��������� ������
  require_once("../utils/utils.settings.php");
  // ������� ��� ������ �� ��������
  require_once("../utils/utils.time.php");

  // ������������� ��������� windows-1251
  header("Content-Type: text/html; charset=windows-1251");
  // ��������� ��� ���������� �� cookie
  $current_author = $_COOKIE['current_author'];
  // ���� �������� ����� ���������� ������� - 
  // ���������� ����-�������
  if (!get_magic_quotes_gpc())
  {
    $current_author = mysql_escape_string($current_author);
  }
  // ��������� ��������� ������
  $settings = get_settings();

  // ������������� �������� ������. ���� �������� �� �����
  // ������������ �������� - ����� ������������� ��� ��������
  $titleall = $settings['name_forum'];
  if(empty($title))
  {
    // ������������� SQL-��������
    $id_forum = intval($_GET['id_forum']);
    // ��������� �������� ������ 
    $query = "SELECT name FROM $tbl_forums
              WHERE id_forum = $id_forum AND
                    hide != 'hide'";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ������� 
                               �������� ������");
    }
    if(mysql_num_rows($frm)) $title = @mysql_result($frm, 0);
  }

  if (!isset($title)) $title = $titleall;
  // �������� �������� ����������, ��� �������� ����
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
  // �������� ���� ���������� ��������� � ������� �����������
  if(!empty($current_author))
  {
    settime($current_author, false, $id_forum);
  }
  else $current_author = " ����������";
  
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
<link href="xml.xml" rel="alternate" type="application/rss+xml" title="RSS-����� ����� ���" />
<?php
 if (basename($_SERVER['PHP_SELF']) == "read.php" ||
     basename($_SERVER['PHP_SELF']) == "personallyread.php") { ?>
<link rel="StyleSheet" type="text/css" href="<?php echo $skin; ?>read.css">
<?php } ?>
</head>  
<?php
  include $skin."diztop.php";
?>