<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) �������� �.�., �������� �.�.
  // PHP. �������� �������� Web-������
  // IT-������ SoftTime 
  // http://www.softtime.ru   - ������ �� Web-����������������
  // http://www.softtime.biz  - ������������ ������
  // http://www.softtime.mobi - ��������� �������
  // http://www.softtime.org  - �������������� �������
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  // ���������� ������
  session_start();
  // ������������� ���������� � ����� ������
  require_once("config/config.php");

  // ������������� SQL-��������
  $_GET['id_position'] = intval($_GET['id_position']);
  // ��������� ��������� �����������
  if(!empty($_GET['id_position']))
  {
    // ��������� ��������� �����������
    $query = "SELECT * FROM $tbl_photo_position
              WHERE id_position = $_GET[id_position] AND
                    hide = 'show'
              LIMIT 1";
    $img = mysql_query($query);
    if(!$img) exit("������ ���������� �����������");
    if(mysql_num_rows($img))
    {
      $image = mysql_fetch_array($img);
      $filename = $image['big'];
    }
    // ����������� ���������� ���������� ���
    // �����������
    $query = "UPDATE $tbl_photo_position
              SET countwatch = countwatch + 1
              WHERE id_position = $_GET[id_position]";
    @mysql_query($query);
  }
  else if(!empty($_GET['img']))
  {
    // �������� �� ������� �����������������
    // ��� ����� � ���� ������
    $filename = $_GET['img'];
  }
  else
  {
    exit();
  }
  list($width, $height) = @getimagesize($filename);
?>
<html>
<head>
<title>�����������</title>
<meta http-equiv="imagetoolbar" content="no">
<style>
 table{font-size: 12px; font-family: Arial, Helvetica, sans-serif; background-color: #F3F3F3;}
</style>
</head>
<body marginheight="0" marginwidth="0" rightmargin="0" bottommargin="0" leftmargin="0" topmargin="0">
<table height="100%" cellpadding="0" cellspacing="0" width="100%" border="1">
  <tr>
    <td height="100%" valign="middle" align="center">
    ����������, ��������� �������� �����������
     <div  style="position: absolute; top: 0px; left: 0px"
         ><img src="<? echo $filename;?>" 
               border="0" 
               width="<?= $width ?>"
               height="<?= $height ?>"
         ></div>
    </td>
  </tr>
</table>    
<div style="position: absolute; z-index: 2; width: 100%; bottom: 5px" align="center">
<input class=button type="submit" value="�������" onclick="window.close();"></div>
</body>
</html>