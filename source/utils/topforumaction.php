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

  // ���������� SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ��������� ������
  require_once("../utils/utils.settings.php");
  // ������� ��� ������ � �������
  require_once("../utils/utils.files.php");

  // ��������� ��������� ������
  $settings = get_settings();

  // �������� �������� ����������, ��� �������� ����
  if(empty($_COOKIE['skin'])){
  	$skin = "../skins/".$settings['skin']."/";
  }else{
    $_COOKIE['skin'] = str_replace(".","",$_COOKIE['skin']);
    $_COOKIE['skin'] = str_replace("/","",$_COOKIE['skin']);
    $_COOKIE['skin'] = htmlspecialchars($_COOKIE['skin']);
  	$skin = "../skins/".$_COOKIE['skin']."/";
  }
?>
<html>
<head>
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv="imagetoolbar" content="no">
<META NAME="Robots" CONTENT="NOINDEX,NOFOLLOW"> 
<title><? echo $nameaction ?></title>
<link rel="StyleSheet" type="text/css" href="<?php echo $skin; ?>forum.css">
<link rel="StyleSheet" type="text/css" href="<?php echo $skin; ?>action.css">
<script language="JavaScript" src="../utils/liteforum.js"></script>

</head>
<body class="bodyaction" topmargin="0" marginheight="0" marginwidth="0" rightmargin="0" bottommargin="0" leftmargin="0">
<table class="toplineaction" border="0" height="150" width="100%" cellpadding="0" cellspacing="0">
    <tr valign="top">
        <td height="125"><div style="position: relative; left: 8%; width: 400px"><h1 class="namepageaction"><?php echo $settings['name_forum']; ?></h1></div></td>
    </tr>
    <tr><td class="lineaction" height="25"><p class=pic>&nbsp;</td></tr>
</table>
<div style="position: absolute; top: 40px; left: 0px; width: 100%; padding: 0px" >
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="50%" >
        <tr>
            <td class=headerform>
                <p class="nameaction"><? echo $nameaction; ?></p>   
            </td>
        </tr>  
        <tr valign="top"><td class=bodyform>