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

  if(empty($_POST))
  {
    // �������� ������
    $_REQUEST['hits'] = 0;
    // ������������� SQL-��������
    $_REQUEST['id_catalog'] = intval($_REQUEST['id_catalog']);
    // ��������� ������� ������������ �������
    $query = "SELECT MAX(pos) FROM $tbl_poll_answer
              WHERE id_catalog = $_REQUEST[id_catalog]";
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
  try
  {
    $name = new field_textarea("name",
                               "�����",
                                true,
                                $_POST['name']);
    $hits = new field_text_int("hits",
                               "����",
                                true,
                                $_REQUEST['hits']);
    $pos = new field_text_int("pos",
                               "�������",
                                true,
                                $_REQUEST['pos']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
  
    $form = new form(array("name"       => $name, 
                           "hits"       => $hits,
                           "pos"        => $pos,
                           "page"       => $page,
                           "id_catalog" => $id_catalog), 
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
        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "INSERT INTO $tbl_poll_answer
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          {$form->fields[pos]->value},
                          {$form->fields[hits]->value},
                          {$form->fields[id_catalog]->value})";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� �������");
        }
        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: answers.php?".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // ������ ��������
    $title     = '���������� �������';
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
