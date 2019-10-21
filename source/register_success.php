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
  // ���������� ������
  session_start();

  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ������������� ���������� � ����� ������
  require_once("config/config.php");
  // ���������� ��������� 
  require_once("utils.title.php");

  try
  {
    // ���������� ������� ������
    $pagename = "����������� �� �����";
    $keywords = "����������� �� �����";
    require_once ("templates/top.php");

    // �������� ��������
    echo title($pagename);
    echo "<div class=main_txt>����������� � �������� 
          ������������ �� �����</div>";

    // ���������� ���������� ��������
    require_once("templates/bottom.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>