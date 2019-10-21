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
    // �������� GET-�������� �� SQL-��������
    $_GET['id_position'] = intval($_GET['id_position']);
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_faq
                WHERE id_position=$_GET[id_position]
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
      $_REQUEST['page'] = $_GET['page'];
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }
  
    $question = new field_textarea("question",
                                   "������",
                                   true,
                                   $_REQUEST['question']);
    $answer = new field_textarea("answer",
                                 "�����",
                                 true,
                                 $_REQUEST['answer']);
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
    $id_position = new field_hidden_int("id_position",
                                        true,
                                        $_REQUEST['id_position']);

    $form = new form(array("question" => $question,
                           "answer" => $answer, 
                           "pos" => $pos,
                           "hide" => $hide,
                           "id_position" => $id_position,
                           "page" => $page), 
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
        // ��������� SQL-������ �� �������������� �������
        $query = "UPDATE $tbl_faq
                  SET question = '{$form->fields[question]->value}',
                      answer   = '{$form->fields[answer]->value}',
                      hide     = '$showhide',
                      pos      = '{$form->fields[pos]->value}'
                WHERE id_position = {$form->fields[id_position]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������������� 
                                   �������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: index.php?".
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