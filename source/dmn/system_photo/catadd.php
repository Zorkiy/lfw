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

  if(empty($_POST)) $_REQUEST['hide'] = true;

  try
  {
    $name = new field_text("name",
                           "��������",
                           true,
                           $_POST['name']);
    $description = new field_textarea("description",
                               "��������",
                               false,
                               $_POST['description']);
    $hide = new field_checkbox("hide",
                               "����������",
                               $_REQUEST['hide']);
    $form = new form(array("name" => $name,
                           "description" => $description,
                           "hide" => $hide), 
                     "��������",
                     "field");

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ��������� ������� ������������ �������
        $query = "SELECT MAX(pos) FROM $tbl_photo_catalog";
        $pos = mysql_query($query);
        if(!$pos)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� 
                                   ������� �������");
        }
        $position = mysql_result($pos, 0) + 1;
        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // ��������� SQL-������ �� ���������� ��������
        $query = "INSERT INTO $tbl_photo_catalog
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[description]->value}',
                          '$showhide',
                          $position)";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� �����
                                   �������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: index.php");
        exit();
      }
    }
    // ������ ��������
    $title     = '���������� �������';
    $pageinfo  = '<p class=help></p>';
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