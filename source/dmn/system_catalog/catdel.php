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
    // ������� �������, �� ����� ���������� �������������
    del_catalog($_GET['id_catalog'], 
                $tbl_cat_catalog, 
                $tbl_cat_position);
    // ������������ ������������� �� ������� ��������
    header("Location: index.php?".
           "page=$_GET[page]");
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // ����������� ������� �������� �������� � ��������� ������ $id_catalog
  function del_catalog($id_catalog, 
                       $tbl_cat_catalog, 
                       $tbl_cat_position)
  {
    // ����������� �������� $id_catalog � ������ ��������
    $id_catalog = intval($id_catalog);
    // ������������ ����������� �����, ��� ����,
    // ����� ������� ��� ��������� �����������
    $query = "SELECT * FROM $tbl_cat_catalog
              WHERE id_parent = $id_catalog"
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������
                               �����������");
    }
    if(mysql_num_rows($cat))
    {
      while($catalog = mysql_fetch_array($cat))
      {
        del_catalog($catalog['id_catalog'],
                    $tbl_cat_catalog,
                    $tbl_cat_position);
      }
    }
    // ������� �������� ������� ������������� ��������
    $query = "DELETE FROM $tbl_cat_position
              WHERE id_catalog=$id_catalog";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������
                               �����������");
    }
    // ������� ������� � ��������� ������ $id_catalog
    $query = "DELETE FROM $tbl_cat_catalog
              WHERE id_catalog=$id_catalog";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������
                               �����������");
    }
  }   
?>