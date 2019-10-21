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
    // �������� GET-�������� �� SQL-��������
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_poll
                WHERE id_catalog=$_GET[id_catalog]
                LIMIT 1";
      $cat = mysql_query($query);
      if(!$cat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� 
                                 � �����������");
      }
      $_REQUEST = mysql_fetch_array($cat);
      $_REQUEST['page'] = $_GET['page'];
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
      if($_REQUEST['archive'] == 'active') $_REQUEST['archive'] = true;
      else $_REQUEST['archive'] = false;
    }
    $name = new field_textarea("name",
                               "������",
                                true,
                                $_REQUEST['name']);
    $hide = new field_checkbox("hide",
                               "����������",
                                $_REQUEST['hide']);
    $archive = new field_checkbox("archive",
                                  "������� ��������",
                                  $_REQUEST['archive']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
  
    $form = new form(array("name"       => $name, 
                           "hide"       => $hide,
                           "archive"    => $archive,
                           "page"       => $page,
                           "id_catalog" => $id_catalog), 
                     "�������������",
                     "field");
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ������� ��� �������� ��������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // �������� ����������� ��� ��������
        if($form->fields['archive']->value)
        {
          // ����������� �������� - ��� ������������
          // ����������� ��������� � ��������� �����
          $query = "UPDATE $tbl_poll SET archive = 'archive'";
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ���������� 
                                     ������� �������");
          }
          $status = "active";
        }
        else $status = "archive";
        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "UPDATE $tbl_poll
                  SET name = '{$form->fields[name]->value}',
                      archive = '$status',
                      hide = '$showhide'
                  WHERE id_catalog = {$form->fields[id_catalog]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ��������������
                                   �������");
        }
        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: index.php?page={$form->fields[page]->value}");
        exit();
      }
    }
    // ������ ��������
    $title     = '�������������� �������';
    $pageinfo  = '<p class=help></p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� �������, ���� ��� �������
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
