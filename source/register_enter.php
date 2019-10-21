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
    // ���� ������������ ��� ����������� - ��������� 
    // �������� HTML-�����
    if(!empty($_SESSION['id_user_position']))
    {
      // ���������� ������ ��� ������������ � ���������
      // ������ id_user_position
      $user = user($_SESSION['id_user_position']);
      // ���������� �������� HTML-�����
      $_REQUEST['name'] = $user['name'];
      $_REQUEST['pass'] = $user['pass'];
    }
    // ���� ������ � cookie �� ����� - ��������� ��
    if(!empty($_COOKIE['name']) && !empty($_COOKIE['pass']))
    {
      // ���������� ������� ��� ��������������
      // SQL-��������
      if (!get_magic_quotes_gpc())
      {
        $_COOKIE['name'] = mysql_escape_string($_COOKIE['name']);
        $_COOKIE['pass'] = mysql_escape_string($_COOKIE['pass']);
      }
      // ������������ ������� ����������� � �������
      // �������������� � cookie
      if(enter($_COOKIE['name'], $_COOKIE['pass']))
      {
        // ����������� �������� ������� - ����������
        // �������� HTML-�����
        $_REQUEST['name'] = $_COOKIE['name'];
        $_REQUEST['pass'] = $_COOKIE['pass'];
      }
    }

    // ��������� HTML-�����
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
    $remember = new field_checkbox("remember", 
                                   "���������", 
                                   $_REQUEST['remember']);

  
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass,
                           "remember"     => $remember),
                     "�����",
                     "main_txt",
                     "",
                     "in_input");
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      
      // ��������� ������� �� � ���� ������ ������������
      // � ��������� ������
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ����������
                               ������������");
      }
      if(!mysql_result($usr, 0))
      {
        $error[] = "������������ � ����� ������ �� ����������";
      }

      if(empty($error))
      {
        // ��������� ������������� �� ����� ������
        if(enter($form->fields['name']->value, 
                 $form->fields['pass']->value))
        {
          if($form->fields['remember']->value)
          {
            // ���� ������� ������ "���������", ������������� 
            // cookie �� ���� ������ � ������� �������� ���
            // ������������ � ��� ������
            @setcookie("name", 
                       urlencode($form->fields['name']->value),
                       time() + 7*24*3600);
            @setcookie("pass", 
                       urlencode($form->fields['pass']->value),
                       time() + 7*24*3600);
          }
        }
        // ����������� ��������
        header("Location: $_SERVER[PHP_SELF]");
        exit();
      }
    }

    // ���������� ������� ������
    $pagename = "���� �� ����";
    $keywords = "���� �� ����";
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