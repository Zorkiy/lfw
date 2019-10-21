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
  // ������������ ���������
  require_once("../utils/utils.pager.php");
  // ���������� SQL-�������
  require_once("utils.query_result.php");
  // ���������� ������� ��� ������ �� ��������
  require_once("../../utils/utils.time.php");
  // ���������� ������� ��� ������ � ��������������
  require_once("../../utils/utils.users.php");
  // ��������� ������
  require_once("../../utils/utils.settings.php");

  try
  {
    // ��������� �������� ������ �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $id_post  = intval($_GET['id_post']);
    // ����������� ���������� �� ���� ������
    $query = "SELECT * FROM $tbl_posts
              WHERE id_post = $id_post
              LIMIT 1";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� 
                               � ���������");
    }
    $posts = mysql_fetch_array($pst);
    if(empty($_POST))
    {
      $_REQUEST = $posts;
    }

    $author = new field_text("author",
                           "�����",
                            true,
                            $_REQUEST['author']);
    $name = new field_textarea("name",
                           "���������",
                            true,
                            $_REQUEST['name'],
                            60,
                            15);
    $delete = new field_checkbox("delete",
                               "������� ��������",
                               $_REQUEST['delete']);
    $id_forum = new field_hidden_int("id_forum",
                            true,
                            $_REQUEST['id_forum']);
    $id_theme = new field_hidden_int("id_theme",
                            true,
                            $_REQUEST['id_theme']);
    $id_post = new field_hidden_int("id_post",
                            true,
                            $_REQUEST['id_post']);

    if(!empty($posts['putfile']) && $posts['putfile'] != '-')
    {
      $array = array("author"     => $author, 
                     "name"       => $name,
                     "delete"     => $delete,
                     "id_theme"   => $id_theme,
                     "id_forum"   => $id_forum,
                     "id_post"    => $id_post);
    }
    else
    {
      $array = array("author"     => $author, 
                     "name"       => $name,
                     "id_theme"   => $id_theme,
                     "id_forum"   => $id_forum,
                     "id_post"    => $id_post);
    }
    $form = new form($array, 
                     "�������������",
                     "field");

    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ������� ��������
        $update_path = '';
        if($form->fields['delete']->value)
        {
          // ������� ������������ ���������
          @unlink("../../forum/".$posts['putfile']);
          $update_path = "putfile = '',";
        }
        // ��������� ���������
        $query = "UPDATE $tbl_posts 
                  SET $update_path
                      name = '{$form->fields[name]->value}',
                      author = '{$form->fields[author]->value}'
                  WHERE id_post = {$form->fields[id_post]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� ���������");
        }
        $header = "Location: posts.php?".
                  "id_forum={$form->fields[id_forum]->value}&".
                  "id_theme={$form->fields[id_theme]->value}";
        header($header);
        exit();
      }
    }

    $title = '�������������� ���������';
    $pageinfo = '';
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