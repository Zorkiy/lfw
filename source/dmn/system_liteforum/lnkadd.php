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
  // ���������� SQL-�������
  require_once("utils.query_result.php");

  try
  {
    if(empty($_POST))
    {
      // ������������� SQL-��������
      $_GET['part'] = intval($_GET['part']);
      $query = "SELECT MAX(pos) AS maxpos 
                FROM $tbl_links 
                WHERE part = $_GET[part]";
      $_REQUEST['pos'] = query_result($query);
      $_REQUEST['pos']++;
      $_REQUEST['part'] = intval($_GET['part']);
      if(empty($_REQUEST['part'])) $_REQUEST['part'] = 1;
      $_REQUEST['hide'] = true;
    }

    $name = new field_text("name",
                           "�������� ����",
                            true,
                            $_REQUEST['name']);
    $url = new field_text("url",
                          "URL",
                           true,
                           $_REQUEST['url']);
    $pos = new field_text("pos",
                          "�������",
                           true,
                           $_REQUEST['pos']);
    $hide = new field_checkbox("hide",
                             "����������",
                             $_REQUEST['hide']);
    $part = new field_hidden_int("part",
                            true,
                            $_REQUEST['part']);

    $form = new form(array("name" => $name, 
                           "url"  => $url,
                           "pos"  => $pos,
                           "part" => $part,
                           "hide" => $hide), 
                     "��������",
                     "field");

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

        // ��������� � ��������� ������ �� ���������� ������
        $query = "INSERT INTO $tbl_links
                  VALUES(NULL, 
                         '{$form->fields[name]->value}',
                         '{$form->fields[url]->value}',
                         '$showhide',
                         {$form->fields[pos]->value},
                         {$form->fields[part]->value})";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� 
                                   ����� �������");
        }
        header("Location: links.php?part={$form->fields[part]->value}");
        exit();
      }
    }

    // ������ ��������
    $title     = '���������� ������';
    $pageinfo  = '<p class=help></p>';
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