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
    // ��������� �������� ������ �� ������ �������
    $id_forum = intval($_GET['id_forum']);

    // ��������� �� ������� $tbl_forums
    // ��� ������� ������� ������
    $query = "SELECT * FROM $tbl_forums 
              ORDER BY pos";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ��������");
    }
    $arr = array();
    if(mysql_num_rows($frm))
    {
      while($forum = mysql_fetch_array($frm))
      {
        if($forum['id_forum'] != $id_forum)
        {
          $arr[$forum['id_forum']] = $forum['name'];
        }
      }
    }

    $forum = new field_select("forum",
                           "����������� ������ � ",
                            $arr,
                            $_POST['forum']);
    $id_forum = new field_hidden_int("id_forum",
                            true,
                            $_REQUEST['id_forum']);
  
    $form = new form(array("forum" => $forum, 
                           "id_forum" => $id_forum), 
                     "����������",
                     "field");
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ��������� SQL-������ ����������� ��� ������
        $query = "UPDATE $tbl_themes 
                  SET id_forum = {$form->fields[forum]->value} 
                  WHERE id_forum = {$form->fields[id_forum]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������� ���");
        }
        // ��������� SQL-������ ��� �������� ������ �� ������� forums
        $query = "DELETE FROM $tbl_forums 
                  WHERE id_forum = {$form->fields[id_forum]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������� ������");
        }
        // ����������� ������� ����� ��������� last_time
        $query = "ALTER TABLE $tbl_last_time 
                  DROP now{$form->fields[id_forum]->value}, DROP last_time{$form->fields[id_forum]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������������� ���� ������");
        }

        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: index.php");
        exit();
      }
    }

    // ������ ��������
    $title     = '����������� ��������';
    $pageinfo  = '<p class=help>�������� ������, � ������� 
    ������� ����������� ������� ������, � ������� ������ "�����������".</p>';
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