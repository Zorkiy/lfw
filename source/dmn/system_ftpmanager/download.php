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

  // ���������� ��������� ��� �����
  $localfile = tempnam("files","down");
  $ret = @ftp_nb_get($ftp_handle, $localfile, $_GET['dir'], FTP_BINARY);
  while ($ret == FTP_MOREDATA)
  {
    // ���������� ��������
    $ret = @ftp_nb_continue($ftp_handle);
  }
  @chmod($localfile, 0644);
  // ���� ���������� ������ ��� �������� �����
  // ���������� �� ���� ������������
  if ($ret != FTP_FINISHED)
  {
    exit("<br>�� ����� �������� ����� ��������� ������...");
  }
  else
  {
    // �������� ������������ ����
    header("Content-Disposition: attachment;".
           " filename=".basename($_GET['dir'])); 
    header("Content-Length: ".filesize($localfile)); 
    header("Content-Type: application/x-force-download;".
           " name=\"".basename($_GET['dir'])."\"");
    echo @file_get_contents($localfile);
  }
  @unlink($localfile);
?>