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
    $query = "SELECT * FROM $tbl_contactaddress LIMIT 1";
    $cnt = mysql_query($query);
    if(!$cnt)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� � 
                               ���������� ����������");
    }
    $contact = mysql_fetch_array($cnt);
    if(empty($_POST)) $_REQUEST = $contact;
  
    // �������
    $phone   = new field_textarea("phone",
                                  "��������",
                                  false,
                                  $_REQUEST['phone']);
    // ����
    $fax     = new field_textarea("fax",
                                  "����",
                                  false,
                                  $_REQUEST['fax']);
    // ������
    $email   = new field_textarea("email",
                                  "E-mail",
                                  false,
                                  $_REQUEST['email']);
    // �����
    $address = new field_textarea("address",
                                  "�����",
                                  false,
                                  $_REQUEST['address']);
    // ���������� ����� �������� �� ���� ���������
    // ���������� - ���� ����� name � ��������� �������
    // textarea
    $form = new form(array("phone" => $phone, 
                          "fax" => $fax, 
                          "email" => $email,
                          "address" => $address), 
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
        // ��������� SQL-������ �� ���������� �������
        $query = "UPDATE $tbl_contactaddress
                  SET phone = '{$form->fields[phone]->value}',
                      fax = '{$form->fields[fax]->value}',
                      email = '{$form->fields[email]->value}',
                      address = '{$form->fields[address]->value}'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������������� 
                                   ���������� ����������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: index.php");
        exit();
      }
    }
    // ������ ���������� ���������� �������� �������� � ���������.
    $title = "�������������� ���������� ����������";
    $pageinfo='<p class="help"></p>';
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