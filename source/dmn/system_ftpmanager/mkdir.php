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

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ������������� ���������� � FTP-��������
  require_once("../../config/ftp_connect.php");

  // �������� �������� ���������� ����������
  // ������� POST �� HTML-����� mkdirform.php
  $dir = $_POST['dir'];
  $name = $_POST['name'];
  // ����������� ����� ������� ������������
  // � �������� �����
  $user = 0;
  if($_POST['ur'] == 'on') $user += 4;
  if($_POST['uw'] == 'on') $user += 2;
  if($_POST['ux'] == 'on') $user += 1;
  // ����������� ����� ������� ��� ������
  // � �������� �����
  $group = 0;
  if($_POST['gr'] == 'on') $group += 4;
  if($_POST['gw'] == 'on') $group += 2;
  if($_POST['gx'] == 'on') $group += 1;
  // ����� ������� �� ��������� ���
  // ��������� ������������� (�� �������� � ������)
  $other = 0;
  if($_POST['or'] == 'on') $other += 4;
  if($_POST['ow'] == 'on') $other += 2;
  if($_POST['ox'] == 'on') $other += 1;
  // ��������� ������� �� ��� ��� ����������
  if(!preg_match("|^[-\w\d_\"]+$|",$name))
  {
    exit("������������ ��� ��� ����������");
  }

  $new_dir = str_replace("//","/",$dir."/".$name);

  // ������ ������� � ������ $name
  @ftp_mkdir($ftp_handle, $new_dir);
  // ������ ������������ ���������� $mode
  // � ������� ������� � ����������
  eval("\$mode=0$user$group$other;");    
  // �������� ����� ������� ��� ������ ���
  // ��������� ����������
  @ftp_chmod($ftp_handle, $mode, $new_dir);

  header("Location: index.php?dir=".urlencode($dir));
?>