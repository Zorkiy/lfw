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
    // �������� ������ hide
    $_REQUEST['hide'] = true;
  }
  try
  {
    $name        = new field_text("name",
                                  "��������",
                                  true,
                                  $_POST['name']);
    $body = new field_textarea("body",
                               "����������",
                               true,
                               $_POST['body']);
    $url         = new field_text("url",
                                  "������",
                                  false,
                                  $_POST['url']);
    $urltext    = new field_text("urltext",
                                 "����� ������",
                                  false,
                                  $_POST['urltext']);
    $date        = new field_datetime("date",
                                  "���� �������",
                                  $_POST['date']);
    $hide        = new field_checkbox("hide",
                                      "����������",
                                      $_REQUEST['hide']);
    $urlpict   = new field_file("urlpict",
                                 "�����������",
                                 false,
                                 $_FILES,
                                 "../../files/news/");
    $page    = new field_hidden_int("page",
                                       false,
                                       $_REQUEST['page']);
  
    $form = new form(array("name" => $name, 
                           "body" => $body, 
                           "url" => $url,
                           "urltext" => $urltext,
                           "date" => $date,
                           "hide" => $hide,
                           "urlpict" => $urlpict,
                           "page" => $page), 
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
        // ������� ��� �������� ����������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // �����������
        $str = $form->fields['urlpict']->get_filename();
        if(!empty($str))
        {
          $img = "files/news/".$form->fields['urlpict']->get_filename();
        }
        else $img = '';
        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "INSERT INTO $tbl_news
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[body]->value}',
                          '{$form->fields[date]->get_mysql_format()}',
                          '{$form->fields[url]->value}',
                          '{$form->fields[urltext]->value}',
                          '$img',
                          '$showhide')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� ����������
                                   ���������");
        }
        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: index.php?page={$form->fields[page]->value}");
        exit();
      }
    }
    // ������ ��������
    $title     = '���������� ���������� ���������';
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
