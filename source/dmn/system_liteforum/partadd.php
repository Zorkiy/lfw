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

  try
  {
    if(empty($_POST))
    {
      $_REQUEST['hide']   = true;
      // ��������� ������������ �������
      $query = "SELECT MAX(pos) FROM $tbl_forums";
      $pos = mysql_query($query);
      if(!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ���������� 
                                 ������������ �������");
      }
      $_REQUEST['pos'] = mysql_result($pos, 0) + 1;
    }
    $name = new field_text("name",
                           "��������",
                            true,
                            $_POST['name']);
    $rule = new field_textarea("rule",
                           "������� ������",
                            true,
                            $_POST['rule']);
    $logo = new field_textarea("logo",
                           "������� ��������",
                            true,
                            $_POST['logo']);
    $pos = new field_text_int("pos",
                           "�������",
                            true,
                            $_REQUEST['pos']);
    $hide = new field_checkbox("hide",
                           "����������",
                            $_REQUEST['hide']);
  
    $form = new form(array("name" => $name, 
                           "rule" => $rule,
                           "logo" => $logo,
                           "pos"  => $pos,
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
        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "INSERT INTO $tbl_forums
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[rule]->value}',
                          '{$form->fields[logo]->value}',
                          {$form->fields[pos]->value},
                          '$showhide')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� ������ �������");
        }
        // ��������� ��������� ���� ������ ��� ������������ �������
        $id_forum = mysql_insert_id();

        // ���������� �������� ��� ����� ������� � ������� $tbl_last_time
        // ��� ����, ����� ����� ���������� ��������� �������������
        // ���������
        $query = "ALTER TABLE $tbl_last_time 
                  ADD now$id_forum datetime NOT NULL ,
                  ADD last_time$id_forum datetime NOT NULL";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������������� �������");
        }
        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: index.php");
        exit();
      }
    }
    // ������ ��������
    $title     = '�������� ������';
    $pageinfo  = '<p class=help>��� ���� ����� �������� ������, 
    ������� ���������� � ��������� ���� � ������� ������ "��������"</p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� �������, ���� ��� �������
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".implode("<br>", $error)."</span><br>";
    }
    // ������� HTML-����� 
    $form->print_form();

    // �������� ���������� ��������
    require_once("../utils/bottom.php");
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