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

  // ��� ������������
  $ftp_user = "root";
  // ������
  $ftp_password = "password";
  // ������
  $ftp_server = "ftp.site.ru";
  // ���������� ���� � ������������ �����
  $ftp_absolute_path = "/www/";
  // ������������� ����� ���������� ������� 120 �
  @set_time_limit(120);
  // ������������� ���������� � FTP-��������
  $ftp_handle = ftp_connect($ftp_server);
  if (!$ftp_handle)
  {
    exit("���������� ���������� ���������� � FTP-��������");
  }
  if(!@ftp_login($ftp_handle, $ftp_user, $ftp_password))
  {
    exit("������ ����������� �� FTP-�������");
  }
?>