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
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");

  // ��������� GET-���������, ������������ SQL-��������
  $_GET['id_catalog']  = intval($_GET['id_catalog']);

  try
  {
    // ������� �������� �������
    $query = "DELETE FROM $tbl_poll_answer
              WHERE id_catalog = $_GET[id_catalog]";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������
                               ��������� �������");
    }
    // ������� ������ 
    $query = "DELETE FROM $tbl_poll 
              WHERE id_catalog = $_GET[id_catalog]";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������
                               �������");
    }
    // ������������ ������������� �� ������� ��������
    header("Location: index.php?page=$_GET[page]");
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
?>