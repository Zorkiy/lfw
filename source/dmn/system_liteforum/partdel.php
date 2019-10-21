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
  // ���� ���������� ��������� (show(), hide(), up(), down())
  require_once("../utils/utils.position.php");

  // �������� �������
  try
  {
    // ��������� �������� ���������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    // ����������� ��� ����, ������������� ������� ������
    $query = "SELECT * FROM $tbl_themes 
              WHERE id_forum = $id_forum";
    $thm = mysql_query($query);
    if(!$thm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ������� � ������� 
                               ��� �������");
    }
    $id_theme = array();
    if(mysql_num_rows($thm))
    {
      // ������� ��� ��������� �� ��� ������
      while($theme = mysql_fetch_array($thm))
      {
        $id_theme[] = $theme['id_theme'];
      }
      if(is_array($id_theme))
      {
        $query = "DELETE FROM $tbl_posts
                  WHERE id_theme IN (".implode(",", $id_theme).")";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������� �������");
        }
      }
    }
    // ������� ��� ���� ������
    $query = "DELETE FROM $tbl_themes
              WHERE id_forum = $id_forum";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ �������� �������");
    }
    // ������� ��� �����
    $query = "DELETE FROM $tbl_forums
              WHERE id_forum = $id_forum";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ �������� �������");
    }
    // ����������� ������� ����� ��������� $tbl_last_time
    $query = "ALTER TABLE $tbl_last_time 
              DROP now$id_forum, 
              DROP last_time$id_forum";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ �������� �������");
    }
    // ������������ �������������� ������� �� ��������
    // "������� ������"
    header("Location: index.php");
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