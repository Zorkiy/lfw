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
    $name        = new field_text("name",
                                  "�������� ������",
                                  true,
                                  $_POST['name']);
    $body = new field_textarea("body",
                               "����������",
                               true,
                               $_POST['body']);
    $form = new form(array("name" => $name, 
                           "body" => $body), 
                    "���������",
                    "field");
  
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        require_once("sendmail.php");
        // ������������ �������� �� ��������, ����������
        // �� �������� �������� ���������
        header("Location: success.php");
      }
    }
    // ������ ��������
    $title = "�������� ��������";
    $pageinfo  = '<p class=help>��������� ��������� �����������</p>';
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
