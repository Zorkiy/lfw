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

  // ������� ����������� �� ����� ���������� �������
  @set_time_limit(0);
  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� ������ �����
  require_once("../../config/class.config.dmn.php");
  // ��������������� ������� yandex(), google(), rambler(), aport()
  require_once("utils.php");
  
  try
  {
    $keywords = new field_text("keywords",
                           "�������� �����",
                            true,
                            $_REQUEST['keywords']);
    $site = new field_text("site",
                           "����",
                            true,
                            $_REQUEST['site']);
    $search = new field_select("search",
                           "��������� �������",
                            array("all"     => "���",
                                  "yandex"  => "������",
                                  "google"  => "Google",
                                  "rambler" => "�������",
                                  "aport"   => "�����"),
                            $_REQUEST['search']);

    $form = new form(array("keywords" => $keywords,
                           "site"     => $site, 
                           "search"   => $search), 
                     "������",
                     "field");

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
    }

    // ������ ��������
    $title     = '���������� ������� ����� � ��������� ��������';
    $pageinfo  = '<p class=help></p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
  
    // ������� ��������� �� ������� ���� ��� �������
    if(!empty($error))
    {
        echo "<span style=\"color:red\">".
             implode("<br>", $error).
             "</span><br>";
    }
    // ������� HTML-����� 
    $form->print_form();

    if(empty($error) && !empty($_POST))
    {
      echo "<p class=help>";
      if($form->fields['search']->value != 'all')
      {
        echo search($form->fields['keywords']->value,
                    $form->fields['site']->value,
                    $form->fields['search']->value);
      }
      else
      {
        $array = array('yandex', 'google', 'rambler', 'aport');
        $keywords = $form->fields['keywords']->value;
        $site = $form->fields['site']->value;
        foreach($array as $value)
        {
          echo search($keywords,
                      $site,
                      $value);
          echo "<br>";
        }
      }
      echo "</p>";
    }
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