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
      $_GET['id_position'] = intval($_GET['id_position']);
      $query = "SELECT * FROM $tbl_users
                WHERE id_position=$_GET[id_position]
                LIMIT 1";
      $usr = mysql_query($query);
      if(!$usr)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                 "������ �������������� ������������");
      }
      unset($_REQUEST);
      $_REQUEST = mysql_fetch_array($usr);
      $_REQUEST['dateregister']['month']  = substr($_REQUEST['dateregister'], 5, 2);
      $_REQUEST['dateregister']['day']    = substr($_REQUEST['dateregister'], 8, 2);
      $_REQUEST['dateregister']['year']   = substr($_REQUEST['dateregister'], 0, 4);
      $_REQUEST['dateregister']['hour']   = substr($_REQUEST['dateregister'], 11, 2);
      $_REQUEST['dateregister']['minute'] = substr($_REQUEST['dateregister'], 14, 2);
      unset($_REQUEST['dateregister']);
      if($_REQUEST['block'] == 'block') $_REQUEST['block'] = true;
      else $_REQUEST['block'] = false;
      $_REQUEST['page']                   = $_GET['page'];
      $_REQUEST['id_position']            = $_GET['id_position'];
      $_REQUEST['begin_date']             = $_GET['begin_date'];
      $_REQUEST['end_date']               = $_GET['end_date'];
      $_REQUEST['lastvisit']['month']     = substr($_REQUEST['lastvisit']['lastvisit'], 5, 2);
      $_REQUEST['lastvisit']['day']       = substr($_REQUEST['lastvisit']['lastvisit'], 8, 2);
      $_REQUEST['lastvisit']['year']      = substr($_REQUEST['lastvisit']['lastvisit'], 0, 4);
      $_REQUEST['lastvisit']['hour']      = substr($_REQUEST['lastvisit']['lastvisit'], 11, 2);
      $_REQUEST['lastvisit']['minute']    = substr($_REQUEST['lastvisit']['lastvisit'], 14, 2);
      unset($_REQUEST['lastvisit']);
      $_REQUEST['passagain']              = $_REQUEST['pass'];
    }

    $text = "����, ���������� ��������� *, �������� ������������� � ����������";
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
    $email = new field_text("email",
                           "E-mail",
                           true,
                           $_REQUEST['email']);
    $block = new field_checkbox("block",
                               "�����������",
                               $_REQUEST['block']);
    $dateregister  = new field_datetime("dateregister",
                                  "���� �����������",
                                  $_REQUEST['dateregister']);
    $lastvisit  = new field_datetime("lastvisit",
                                  "���� ���������� ������",
                                  $_REQUEST['lastvisit']);
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $begin_date = new field_hidden_int("begin_date",
                                 false,
                                 $_REQUEST['begin_date']);
    $end_date = new field_hidden_int("end_date",
                                 false,
                                 $_REQUEST['end_date']);

    $form = new form(array("form_comment"     => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass, 
                           "passagain"    => $passagain,
                           "email"        => $email,
                           "block"        => $block,
                           "dateregister" => $dateregister,
                           "lastvisit"    => $lastvisit,
                           "id_position"  => $id_position,
                           "page"         => $page,
                           "begin_date"   => $begin_date,
                           "end_date"     => $end_date),
                     "�������������",
                     "field");

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      // ��������� ����� �� ������
      if($form->fields['pass']->value != 
         $form->fields['passagain']->value)
      {
        $error[] = "������ �� �����";
      }
  
      // ��������� �� ��������������� �� ������������
      // � ����������� ������ �����
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}' AND 
                      id_position != {$form->fields[id_position]->value}";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ �������������� ������������");
      }
      if(mysql_result($usr, 0))
      {
        $error[] = "������������ � ����� ������ ��� 
                    ����������";
      }

      if(empty($error))
      {
        // ������������ ������������ ��� ���
        if($form->fields['block']->value) $block = "block";
        else $block = "unblock";

        // ��������� SQL-������ �� �������������� �������
        $query = "UPDATE $tbl_users
                  SET name = '{$form->fields[name]->value}',
                      pass = '{$form->fields[pass]->value}',
                      email = '{$form->fields[email]->value}',
                      block = '$block',
                      dateregister = '{$form->fields[dateregister]->get_mysql_format()}',
                      lastvisit = '{$form->fields[lastvisit]->get_mysql_format()}'
                 WHERE id_position={$form->fields[id_position]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������
                                   �������������� ������ 
                                   ������������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: index.php?begin_date={$form->fields[begin_date]->value}&".
               "end_date={$form->fields[end_date]->value}");
        exit();
      }
    }

    // ������ ��������
    $title     = '�������������� ������ �������������';
    $pageinfo  = '<p class=help></p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� ������� ���� ��� �������
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