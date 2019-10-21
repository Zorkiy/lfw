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
  // ���������� ������� ��������� ������� �����������
  require_once("../utils/utils.resizeimg.php");

  // ������ �� SQL-��������
  $_GET['id_catalog']   = intval($_GET['id_catalog']);

  if(empty($_POST)) $_REQUEST['hide'] = true;
  try
  {
    $name = new field_text("name",
                           "��������",
                           false,
                           $_POST['name']);
    $alt = new field_text("alt",
                           "ALT-���",
                           false,
                           $_POST['alt']);
    $big   = new field_file("big",
                            "�����������",
                             false,
                             $_FILES,
                            "../../files/photo/");
    $pollnumber = new field_text_int("pollnumber",
                                     "���������� ���������������",
                                      false,
                                     $_POST['pollnumber']);
    $pollmark = new field_text_int("pollmark",
                                   "���������� �������",
                                    false,
                                    $_POST['pollmark']);
    $countwatch = new field_text_int("countwatch",
                                     "���������� ����������",
                                      false,
                                      $_POST['countwatch']);
    $hide = new field_checkbox("hide",
                               "����������",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name"       => $name,
                           "alt"        => $alt, 
                           "big"        => $big,
                           "pollnumber" => $pollnumber,
                           "pollmark"   => $pollmark,
                           "countwatch" => $countwatch,
                           "hide"       => $hide, 
                           "id_catalog" => $id_catalog,
                           "page"       => $page), 
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
        // ��������� ������� ������������ �������
        $query = "SELECT MAX(pos) FROM $tbl_photo_position
                  WHERE id_catalog={$form->fields['id_catalog']->value}";
        $pos = mysql_query($query);
        if(!$pos)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� 
                                   ������� �������");
        }
        $pos = mysql_result($pos, 0) + 1;
        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // �����������
        $var = $form->fields['big']->get_filename();
        if(!empty($var))
        {
          $big = "files/photo/".$var;
          $small = "files/photo/s_".$var;
        }
        else $big = "";
        // ��������� ��������� �������
        $query = "SELECT * FROM $tbl_photo_settings LIMIT 1";
        $set = mysql_query($query);
        if(!$set)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� 
                                   ���������� �������");
        }
        if(mysql_num_rows($set))
        {
          $settings = mysql_fetch_array($set);
        }
        else
        {
          $settings['width'] = 150;
          $settings['height'] = 133;
        }
        // ��������� ����� �����������
        resizeimg($big, $small, $settings['width'], $settings['height']);
        // ��������� SQL-������ �� ���������� �������
        $query = "INSERT INTO $tbl_photo_position
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[alt]->value}',
                          '$small',
                          '$big',
                          '{$form->fields[pollnumber]->value}',
                          '{$form->fields[pollmark]->value}',
                          '{$form->fields[countwatch]->value}',
                          '$showhide',
                           $pos,
                          {$form->fields[id_catalog]->value})";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� 
                                   �������");
        }
        // ������������ �������� �� ������� ��������
        header("Location: photos.php?".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // ������ ��������
    $title     = '���������� �����������';
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