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
  // ������� POST �� HTML-����� uploadform.php
  $dir = $_POST['dir'];
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
  // ��������� ������� �� ��� ��� �����

  if(empty($_POST['dir'])) $directory = "/";
  else $directory = $_POST['dir'];

  if (!empty($_FILES['name']['tmp_name']))
  {
    $name = str_replace("//","/",$directory."/".$_FILES['name']['name']);
    $name = str_replace("..", "", $name);
    // �������� ��������
    $ret = @ftp_nb_put($ftp_handle, 
                       $name, 
                       $_FILES['name']['tmp_name'], 
                       FTP_BINARY);
    while ($ret == FTP_MOREDATA)
    {
      // ���������� ��������
      $ret = @ftp_nb_continue($ftp_handle);
    }
    if ($ret != FTP_FINISHED)
    {
      exit("<br>�� ����� �������� ����� ��������� ������...");
    }
    else
    {
      @unlink($_FILES['name']['tmp_name']);
      // ������ ������������ ���������� $mode
      // � ������� ������� � ����������
       eval("\$mode=0$user$group$other;");    
      // �������� ����� ������� ��� ������ ���
      // ��������� ����������
      @ftp_chmod($ftp_handle, $mode, $name);
    }
  }
  header("Location: index.php?dir=".urlencode($dir));
?>