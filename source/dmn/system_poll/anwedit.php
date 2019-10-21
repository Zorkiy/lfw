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
    if(empty($_POST))
    {
      // ������������� SQL-��������
      $_GET['id_catalog']  = intval($_GET['id_catalog']);
      $_GET['id_position'] = intval($_GET['id_position']);
      // ��������� ��������� ������������� �������
      $query = "SELECT * FROM $tbl_poll_answer
                WHERE id_position = $_GET[id_position] AND
                      id_catalog = $_GET[id_catalog]
                LIMIT 1";
      $ans = mysql_query($query);
      if(!$ans)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� 
                                 � �������");
      }
      $_REQUEST = mysql_fetch_array($ans);
      $_REQUEST['page'] = $_GET['page'];
    }
    $name = new field_textarea("name",
                               "�����",
                                true,
                                $_REQUEST['name']);
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
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
  
    $form = new form(array("name"        => $name, 
                           "hits"        => $hits,
                           "pos"         => $pos,
                           "page"        => $page,
                           "id_catalog"  => $id_catalog,
                           "id_position" => $id_position), 
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
        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "UPDATE $tbl_poll_answer
                  SET name = '{$form->fields[name]->value}',
                      pos = {$form->fields[pos]->value},
                      hits = {$form->fields[hits]->value}
                  WHERE id_catalog = {$form->fields[id_catalog]->value} AND
                        id_position = {$form->fields[id_position]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������������� �������");
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
