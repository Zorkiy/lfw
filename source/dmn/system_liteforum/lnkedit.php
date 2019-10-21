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
      $_GET['id_link'] = intval($_GET['id_link']);
      $query = "SELECT * FROM $tbl_links 
                WHERE part = $_GET[part] AND
                      id_links = $_GET[id_link]
                LIMIT 1";
      $lnk = mysql_query($query);
      if(!$lnk)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��������� ������� ������");
      }
      if(mysql_num_rows($lnk))
      {
        $_REQUEST = mysql_fetch_array($lnk);
        if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;
      }
      $_REQUEST['part'] = $_GET['part'];
      $_REQUEST['id_link'] = $_GET['id_link'];
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
    $id_link = new field_hidden_int("id_link",
                            true,
                            $_REQUEST['id_link']);
    $page = new field_hidden_int("page",
                            false,
                            $_REQUEST['page']);

    $form = new form(array("name"    => $name, 
                           "url"     => $url,
                           "pos"     => $pos,
                           "part"    => $part,
                           "hide"    => $hide,
                           "id_link" => $id_link,
                           "page"    => $page), 
                     "�������������",
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
        $query = "UPDATE $tbl_links
                  SET name = '{$form->fields[name]->value}',
                      url = '{$form->fields[url]->value}',
                      hide = '$showhide',
                      pos = {$form->fields[pos]->value}
                  WHERE part = {$form->fields[part]->value} AND
                        id_links = {$form->fields[id_link]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������������� �������");
        }
        header("Location: links.php?part={$form->fields[part]->value}&page={$form->fields[page]->value}");
        exit();
      }
    }

    // ������ ��������
    $title     = '�������������� ������';
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