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
    // ��������� ������� ������������ �������
    $query = "SELECT MAX(pos) FROM $tbl_faq";
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
    $question = new field_textarea("question",
                                   "������",
                                   true,
                                   $_POST['question']);
    $answer = new field_textarea("answer",
                                 "�����",
                                 true,
                                 $_POST['answer']);
    $pos = new field_text_int("pos",
                              "�������",
                              true,
                              $_REQUEST['pos']);
    $hide        = new field_checkbox("hide",
                                      "����������",
                                      $_REQUEST['hide']);
    $page    = new field_hidden_int("page",
                                    false,
                                    $_REQUEST['page']);
  
    $form = new form(array("question" => $question, 
                           "answer" => $answer, 
                           "pos" => $pos,
                           "hide" => $hide,
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
        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "INSERT INTO $tbl_faq
                  VALUES (NULL,
                          '{$form->fields[question]->value}',
                          '{$form->fields[answer]->value}',
                          NOW(),
                          '$showhide',
                          '{$form->fields[pos]->value}')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� �������");
        }
        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: index.php?page={$form->fields[page]->value}");
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
