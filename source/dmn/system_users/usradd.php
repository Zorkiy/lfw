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
    // ������� ������ block
    if(empty($_POST)) $_REQUEST['block'] = false;

    $text = "����, ���������� ��������� *, �������� ������������� � ����������";
    $form_comment = new field_paragraph($text);
  
    $name = new field_text("name",
                           "���",
                           true,
                           $_REQUEST['name']);
    $pass = new field_password("pass",
                               "������",
                               true,
                               $_REQUEST['pass']);
    $passagain = new field_password("passagain",
                               "������",
                               true,
                               $_REQUEST['passagain']);
    $email = new field_text_email("email",
                                 "E-mail",
                                  true,
                                  $_REQUEST['email']);
    $block = new field_checkbox("block",
                               "�����������",
                               $_REQUEST['block']);
    $dateregister  = new field_datetime("dateregister",
                                  "���� �����������",
                                  $_REQUEST['dateregister']);
    $lastvisit  = new field_datetime("lastvisit",
                                  "���� ���������� ������",
                                  $_REQUEST['lastvisit']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass, 
                           "passagain"    => $passagain,
                           "email"        => $email,
                           "block"        => $block,
                           "dateregister" => $dateregister,
                           "lastvisit"    => $lastvisit,
                           "page"         => $page),
                     "��������",
                     "field");

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      // ��������� ����� �� ������
      if($form->fields['pass']->value != 
         $form->fields['passagain']->value)
      {
        $error[] = "������ �� �����";
      }

      // ��������� �� ��������������� �� ������������
      // � ����������� ����� �����
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� 
                               ������ ������������");
      }
      if(mysql_result($usr, 0))
      {
        $error[] = "������������ � ����� ������ ��� 
                    ����������";
      }

      if(empty($error))
      {
        // ������������ ������������ ��� ���
        if($form->fields['block']->value) $block = "block";
        else $block = "unblock";
        // ��������� SQL-������ �� ���������� �������
        $query = "INSERT INTO $tbl_users
                  VALUES (NULL,
                         '{$form->fields[name]->value}',
                         '{$form->fields[pass]->value}',
                         '{$form->fields[email]->value}',
                         '$block',
                         '{$form->fields[dateregister]->get_mysql_format()}',
                         '{$form->fields[lastvisit]->get_mysql_format()}')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� ������ ������������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: index.php?page={$form->fields[page]->value}");
        exit();
      }
    }

    // ������ ��������
    $title     = '���������� ������������';
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