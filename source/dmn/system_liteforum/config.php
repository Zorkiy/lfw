<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) �������� �.�., �������� �.�.
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
  include "../../config/config.php";

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
?>