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
    // ����������� ���������� �� ���� ������
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_forums
                WHERE id_forum = $id_forum
                LIMIT 1";
      $cat = mysql_query($query);
      if(!$cat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� 
                                 � �������");
      }
      $_REQUEST = mysql_fetch_array($cat);
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }

    $name = new field_text("name",
                           "��������",
                            true,
                            $_REQUEST['name']);
    $rule = new field_textarea("rule",
                           "������� ������",
                            true,
                            $_REQUEST['rule']);
    $logo = new field_textarea("logo",
                           "������� ��������",
                            true,
                            $_REQUEST['logo']);
    $pos = new field_text_int("pos",
                           "�������",
                            true,
                            $_REQUEST['pos']);
    $id_forum = new field_hidden_int("id_forum",
                            true,
                            $_REQUEST['id_forum']);
    $hide = new field_checkbox("hide",
                           "����������",
                            $_REQUEST['hide']);
  
    $form = new form(array("name"     => $name, 
                           "rule"     => $rule,
                           "logo"     => $logo,
                           "pos"      => $pos,
                           "id_forum" => $id_forum,
                           "hide"     => $hide), 
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
        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "UPDATE $tbl_forums
                  SET name = '{$form->fields[name]->value}',
                      rule = '{$form->fields[rule]->value}',
                      logo = '{$form->fields[logo]->value}',
                      pos  = {$form->fields[pos]->value},
                      hide = '$showhide'
                  WHERE id_forum = {$form->fields[id_forum]->value}";
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
    $title     = '�������������� �������';
    $pageinfo  = '<p class=help>��� ����, ����� ��������������� ������ �������� 
    ���������� � ��������� ����� � ������� ������ "���������"</p>';
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