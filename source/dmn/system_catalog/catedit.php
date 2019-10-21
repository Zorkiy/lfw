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

  $_GET['id_catalog'] = intval($_GET['id_catalog']);
  try
  {
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_cat_catalog
                WHERE id_catalog=$_GET[id_catalog]
                LIMIT 1";
      $cat = mysql_query($query);
      if(!$cat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� 
                                 ��������");
      }
      $_REQUEST = mysql_fetch_array($cat);
      $_REQUEST['page'] = $_GET['page'];
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }

    $name = new field_text("name",
                           "��������",
                           true,
                           $_REQUEST['name']);
    $description = new field_textarea("description",
                                 "��������",
                                 false,
                                 $_REQUEST['description']);
    $keywords = new field_text("keywords",
                               "�������� �����",
                               false,
                               $_REQUEST['keywords']);
    $modrewrite = new field_text_english("modrewrite",
                               "�������� ���<br>ReWrite",
                               false,
                               $_REQUEST['modrewrite']);
    $hide = new field_checkbox("hide",
                               "����������",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $id_parent = new field_hidden_int("id_parent",
                                 true,
                                 $_REQUEST['id_parent']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name" => $name,
                            "description" => $description, 
                            "keywords" => $keywords, 
                            "modrewrite" => $modrewrite, 
                            "hide" => $hide,
                            "modrewrite" => $modrewrite,
                            "id_catalog" => $id_catalog,
                            "id_parent" => $id_parent,
                            "page" => $page), 
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
        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // ��������� SQL-������ �� ���������� ��������
        $query = "UPDATE $tbl_cat_catalog
                  SET name        = '{$form->fields[name]->value}',
                      description = '{$form->fields[description]->value}',
                      keywords    = '{$form->fields[keywords]->value}',
                      modrewrite  = '{$form->fields[modrewrite]->value}',
                      hide        = '$showhide'
                  WHERE id_catalog = {$form->fields[id_catalog]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������������� 
                                   �����������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: index.php?".
               "id_parent={$form->fields[id_parent]->value}&".
               "page={$form->fields[page]->value}");
        exit(); 
      }
    }

    // ������ ��������
    $title     = '�������������� �����������';
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
  catch(ExceptionMySQL $exc)
  {
    require_once("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc) 
  {
    require_once("../utils/exception_member.php");
  }
  catch(ExceptionObject $exc) 
  {
    require_once("../utils/exception_object.php");
  }

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>