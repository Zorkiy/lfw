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
  // ���������� ������ �����
  require_once("../../config/class.config.dmn.php");

  try
  {
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_photo_settings
                LIMIT 1";
      $set = mysql_query($query);
      if(!$set)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� 
                                 ��������");
      }
      $_REQUEST = mysql_fetch_array($set);
    }
  
    $width = new field_text_int("width",
                       "������",
                        true,
                        $_REQUEST['width'],
                        50,
                        300,
                        10,
                        10,
                       "",
                       "������ ����������� ����� �����������
                        (�� 50 �� 300 ��������)");
    $height = new field_text_int("height",
                       "������",
                        true,
                        $_REQUEST['height'],
                        50,
                        300,
                        10,
                        10,
                       "",
                       "����� ����������� ����� �����������
                        (�� 50 �� 300 ��������)");
    $row = new field_text_int("row",
                       "���� � ����",
                        true,
                        $_REQUEST['row'],
                        1,
                        10,
                        3,
                        10,
                       "",
                       "���������� ���������� � ���� (�� 1 �� 10 ����)");
    $form = new form(array("width" => $width,
                           "height" => $height,
                           "row" => $row), 
                     "���������",
                     "field");
  
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ��������� SQL-������ �� ���������� ��������
        $query = "UPDATE $tbl_photo_settings
                  SET width = '{$form->fields[width]->value}',
                      height = '{$form->fields[height]->value}',
                      row = '{$form->fields[row]->value}'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������������� 
                                   �������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: index.php");
        exit(); 
      }
    }
    // ������ ��������
    $title     = '��������� �����������';
    $pageinfo  = '<p class=help>����� ����� ���������� ��������� �������</p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� ������� ���� ��� �������
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\">$err</span><br>";
      }
    }
    // ������� HTML-����� 
    $form->print_form();
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

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>