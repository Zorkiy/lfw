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
  require_once("config.php");
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ������������ ���������
  require_once("../utils/utils.pager.php");
  // ���������� SQL-�������
  require_once("utils.query_result.php");
  // ���������� ������� ��� ������ �� ��������
  require_once("../../utils/utils.time.php");


  try
  {
    // ��������� �������� ���������� �� ������ �������
    $id_author = intval($_GET['id_author']);
    $page = intval($_GET['page']);
    // ��������� ��������� ������������
    $query = "SELECT * FROM $tbl_authors
              WHERE id_author = $id_author
              LIMIT 1";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ����������
                               �� ������������");
    }
    if(mysql_num_rows($ath))
    {
       $author = mysql_fetch_array($ath);
       if(!empty($author['photo'])) @unlink('../../forum/$author[photo]');
    }
    // ������� ������������
    $query = "DELETE FROM $tbl_authors
              WHERE id_author = $id_author
              LIMIT 1";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� �������� ������������");
    }
    @header("Location: authorslist.php?page=$page");
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }
?>