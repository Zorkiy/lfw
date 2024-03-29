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
  // ���������� ������ �����
  require_once("../../config/class.config.dmn.php");
  // ������������� ���������� � FTP-��������
  require_once("../../config/ftp_connect.php");
  // ���������� ������� ��� ������ � 
  // ������� .htaccess � .htpasswd
  require_once("../utils/uitls.htfiles.php");

  $dir = $_GET['dir'];
  if(is_htpasswd($ftp_handle, $dir))
  {
    // ������� ���� .htpasswd
    $ftp_htpasswd = str_replace("//","/",$dir."/.htpasswd");
    ftp_delete($ftp_handle, $ftp_htpasswd);
  }
  if(is_htaccess($ftp_handle, $dir))
  {
    // ������� ������ ����������� � .htaccess
    $content = get_htaccess($ftp_handle, $dir);
    $pattern = "#AuthType.*valid-user#is";
    $content = preg_replace($pattern, "", $content);
    put_htaccess($ftp_handle, $dir, $content);
  }

  $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
  @header("Location: $url");
?>