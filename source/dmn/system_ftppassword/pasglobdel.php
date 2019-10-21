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
  // ���������� ������� ��� ������ � 
  // ������� .htaccess � .htpasswd
  require_once("../utils/uitls.htfiles.php");

  $dir = $_GET['dir'];
  $name = $_GET['name'];
  if(is_htpasswd($ftp_handle, "/"))
  {
    // ���� .htpasswd � ���������� �������, ������� ������������
    $content = get_htpasswd($ftp_handle, "/");
    $pattern = "#".preg_quote($name).":[^\n]+\n#is";
    $content = preg_replace($pattern, "", $content);
    // �������������� ���� .htpasswd
    put_htpasswd($ftp_handle, "/", $content);
  }

  $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
  @header("Location: $url");
?>