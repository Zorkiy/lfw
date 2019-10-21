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

  ///////////////////////////////////////////////////////
  // .htaccess
  ///////////////////////////////////////////////////////
  $dir = $_GET['dir'];
  if(is_htpasswd($ftp_handle, $dir))
  {
    // ������� ���� .htpasswd
    $ftp_htpasswd = str_replace("//","/",$dir."/.htpasswd");
    @ftp_delete($ftp_handle, $ftp_htpasswd);
  }
  if(!is_htaccess($ftp_handle, $dir))
  {
    // ����� .htaccess � ���������� ���, ������ ���
    // � ���������� files � ��������� �� FTP
    $content = "AuthType Basic\n".
               "AuthName \"Fill name and password\"\n".
               "AuthUserFile $ftp_absolute_path.htpasswd\n".
               "require valid-user";
    put_htaccess($ftp_handle, $dir, $content);
  }
  else
  {
    // ���� .htpasswd � ���������� ������������
    // ��������� ���������� �����
    $content = get_htaccess($ftp_handle, $dir);
    // ���������, ������� �� � ����� ����� require valid-user, 
    // ���� ������� - ������ �� ���������, ���� ��������, ���������
    // ���������� ������
    $flag = (strpos($content, "require") !== false) && 
            (strpos($content, "valid-user") !== false);
    if(!$flag)
    {
      $content .= "\nAuthType Basic\n".
                  "AuthName \"Fill name and password\"\n".
                  "AuthUserFile $ftp_absolute_path.htpasswd\n".
                  "require valid-user";
      put_htaccess($ftp_handle, $dir, $content);
    }
    else
    {
      // ������� ������ ������
      $pattern = "#AuthType.*valid-user#is";
      $content = preg_replace($pattern, "", $content);
      // ������ �����
      $content .= "\nAuthType Basic\n".
                  "AuthName \"Fill name and password\"\n".
                  "AuthUserFile $ftp_absolute_path.htpasswd\n".
                  "require valid-user";
      put_htaccess($ftp_handle, $dir, $content);
    }
  }

  // �������� � ������� �����������������
  $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
  @header("Location: $url");
?>