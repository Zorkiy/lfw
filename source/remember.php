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
  // ��������� �������������� enter(), user(), remember()
  require_once("utils.users.php");

  try
  {
    // ���� ������������ ��� ����������� - ��������
    // ��� �� ����� ������
    if(!empty($_SESSION['name']))
    {
      // ���������� ������ ������������
      remember($_SESSION['name']);
      // ��������� �� ��������, ���������� �� �������� �������� ������
      header("Location: remember_success.php");
      exit();
    }

    // �����������
    $text = "����, ���������� ��������� *, �������� ".
            "������������� � ����������";
    $form_comment = new field_paragraph($text);
  
    $name = new field_text("name",
                           msg("���"),
                           true,
                           $_REQUEST['name']);
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name),
                     "������� ������",
                     "main_txt",
                     "",
                     "in_input");
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
  
      // ��������� �� ��������������� �� ������������
      // � ����������� ������ �����
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� �������������� ������");
      }
      if(!mysql_result($usr, 0))
      {
        $error[] = msg("������������ � ����� ������ �� ����������");
      }
  
      if(empty($error))
      {
        // ���������� ������ ������������
        remember($form->fields['name']->value);
  
        // ��������� �� ��������, ���������� �� �������� �������� ������
        header("Location: remember_success.php");
        exit();
      }
    }

    // ���������� ������� ������
    $pagename = "��������� ������";
    $keywords = "��������� ������";
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

    // ���������� ������ ������
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