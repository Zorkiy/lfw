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
  // ������� ����
  @ftp_delete($ftp_handle, $_GET['dir']);
  // ������������ �������������� �������
  // �� �������� ����������������� ftp-��������
  $dir = urlencode(substr($_GET['dir'], 0, strrpos($_GET['dir'], "/")));
  header("Location: index.php?dir=$dir");
?>