<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  // ���������� �.�. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // ���� ��������� DEBUG ����������, �������� ����������
  // �������, � ��������� ��������� ��������� ��������� ��
  // �������������� ���������, ��������� � MySQL � ���
  define("DEBUG", 1);
  // ������ ��������� ������ ��������� ������
  $dblocation = "localhost";
  // ��� ���� ������, �� �������� ��� ��������� ������
  $dbname = "oop_site";
  // ��� ������������ ���� ������
  $dbuser = "root";
  // � ��� ������
  $dbpasswd = "";

  // ������� ������
  $tbl_settings   = "liteforum_settings";
  $tbl_authors    = "liteforum_authors";
  $tbl_forums     = "liteforum_forums";
  $tbl_last_time  = "liteforum_last_time";
  $tbl_links      = "liteforum_links";
  $tbl_personally = "liteforum_personally";
  $tbl_posts      = "liteforum_posts";
  $tbl_themes     = "liteforum_themes";
  // �������� �������
  $tbl_archive_number = "liteforum_archive_number";
  $tbl_archive_posts  = "liteforum_archive_posts";
  $tbl_archive_themes = "liteforum_archive_themes";

  // ������������� ���������� � �������� ���� ������
  $dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
  if (!$dbcnx)
  {
    exit("� ��������� ������ ������ ���� ������ �� ��������,
          ������� ���������� ����������� �������� ����������.");
  }
  // �������� ���� ������
  if (! @mysql_select_db($dbname,$dbcnx))
  {
    exit("� ��������� ������ ���� ������ �� ��������, �������
          ���������� ����������� �������� ����������.");
  }
  @mysql_query("SET NAMES 'cp1251'");
?>