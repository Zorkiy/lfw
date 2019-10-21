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
  // ������������� ���������� � ����� ������
  require_once("config/config.php");
  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ���������� ������� ������ ������ � bbCode
  require_once("dmn/utils/utils.print_page.php");
  // ���������� ��������� 
  require_once("utils.title.php");

  try
  {
    $text = "����, ���������� ��������� *, �������� 
             ������������� � ����������";
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
  
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass, 
                           "passagain"    => $passagain,
                           "email"        => $email),
                     "������������������",
                     "main_txt",
                     "",
                     "in_input");
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      // ��������� ����� �� ������
      if($form->fields['pass']->value != $form->fields['passagain']->value)
      {
        $error[] = "������ �� �����";
      }
  
      // ��������� �� ��������������� �� ������������
      // � ����������� ������ �����
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ������ ������������");
      }
      if(mysql_result($usr, 0))
      {
        $error[] = "������������ � ����� ������ ��� ����������";
      }
  
      if(empty($error))
      {
        // ��������� SQL-������ �� ���������� �������
        $query = "INSERT INTO $tbl_users
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[pass]->value}',
                          '{$form->fields[email]->value}',
                          'unblock',
                          NOW(),
                          NOW())";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                   "������ ���������� 
                                   ������ ������������");
        }
        // ���� �� ����
        $_SESSION['name'] = $form->fields['name']->value;
        $_SESSION['id_user'] = mysql_insert_id();
        // ������������ �������� �� ��������, ����������
        // �� �������� �����������
        header("Location: register_success.php");
        exit();
      }
    }

    // ���������� ������� ������
    $pagename = "����������� �� �����";
    $keywords = "����������� �� �����";
    require_once ("templates/top.php");

    // �������� ��������
    echo title($pagename);

    // ������� ��������� �� ������� ���� ��� �������
    if(!empty($error))
    {
      echo "<br>";
      foreach($error as $err)
      {
        echo "<span style=\"color:red\" class=main_txt>$err</span><br>";
      }
    }
    // ������� HTML-����� 
    $form->print_form();

    //���������� ������ ������
    require_once ("templates/bottom.php");
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