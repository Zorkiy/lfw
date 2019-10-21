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
    $id_theme = intval($_GET['id_theme']);
    $id_forum = intval($_GET['id_forum']);
    $page = intval($_GET['page']);
    // ����������� ���������� �� ���� ������
    $query = "SELECT * FROM $tbl_themes
              WHERE id_theme = $id_theme
              LIMIT 1";
    $thm = mysql_query($query);
    if(!$thm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� 
                               � ����");
    }
    $theme = mysql_fetch_array($thm);
    if(empty($_POST))
    {
      $_REQUEST = $theme;
      $_REQUEST['copy'] = true;
      $_REQUEST['id_forum'] = $id_forum;
      $_REQUEST['id_theme'] = $id_theme;
      $_REQUEST['page'] = $page;
    }

    $name = new field_text("name",
                           "�������� ����",
                            true,
                            $_REQUEST['name']);
    $author = new field_text("author",
                           "�����",
                            true,
                            $_REQUEST['author']);
    // �������� ������ �� ������� forums
    $query = "SELECT * FROM $tbl_forums 
              ORDER BY pos";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� � ������� �������");
    }
    $array = array(0 => "�� ����������");
    if(mysql_num_rows($frm))
    {
      while($forums = mysql_fetch_array($frm))
      {
        // ���������� ��������� �����
        if($forums['id_forum'] == $id_forum) continue;
        $array[$forums['id_forum']] = $forums['name'];
      }
    }
    $newforum = new field_select("newforum",
                           "����������� � �����",
                            $array,
                            $_REQUEST['newforum']);
    $copy = new field_checkbox("copy",
                               "�������� �����",
                               $_REQUEST['copy']);
    $id_theme = new field_hidden_int("id_theme",
                            true,
                            $_REQUEST['id_theme']);
    $id_forum = new field_hidden_int("id_forum",
                            true,
                            $_REQUEST['id_forum']);
    $page = new field_hidden_int("page",
                            false,
                            $_REQUEST['page']);

    $form = new form(array("name"       => $name, 
                           "author"     => $author,
                           "newforum"   => $newforum,
                           "copy"       => $copy,
                           "id_theme"   => $id_theme,
                           "id_forum"   => $id_forum,
                           "page"       => $page), 
                     "�������������",
                     "field");

    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ��������� � ��������� SQL-������
        $query = "UPDATE $tbl_themes 
                  SET name = '{$form->fields[name]->value}',
                      author = '{$form->fields[author]->value}'
                  WHERE id_theme = {$form->fields[id_theme]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������������� ����");
        }
        // ��������� �� ����������� �� ���� �� ������ ������� � ������
        if($form->fields['newforum']->value != 0)
        {
          if($form->fields['copy']->value)
          {
            $query = "SELECT * FROM $tbl_themes 
                      WHERE id_theme = {$form->fields[id_theme]->value}";
            $thm = mysql_query($query);
            if(!$thm)
            {
              throw new ExceptionMySQL(mysql_error(), 
                                       $query,
                                      "������ �������������� ����");
            }
            if(mysql_num_rows($thm))
            {
              $theme = mysql_fetch_array($thm);
              $query = "INSERT INTO $tbl_themes 
                        VALUES(NULL,
                              '$theme[name]',
                              '$theme[author]',
                               $theme[id_author],
                              '$theme[last_author]',
                               $theme[id_last_author],
                              'lock',
                              '$theme[time]',
                              {$form->fields[id_forum]->value})";
              if(!mysql_query($query))
              {
                throw new ExceptionMySQL(mysql_error(), 
                                         $query,
                                        "������ �������������� ����");
              }
              // ���� ����������������� ��� ������ ������ - �������� 
              // ��������� ���� ����� ����
              $id_new_theme = mysql_insert_id();
              $query = "INSERT INTO $tbl_posts 
                        VALUES(NULL,
                              '���� ���� ���������� �� ������ [url]http://$_SERVER[SERVER_NAME]/forum/read.php?id_forum=$id_new_forum&id_theme={$form->fields[id_theme]->value}[/url]',
                              '-',
                              '-',
                              '$theme[author]',
                               $theme[id_author],
                              'show',
                              '$theme[time]',
                               0,
                               $id_new_theme)";
              if(!mysql_query($query))
              {
                throw new ExceptionMySQL(mysql_error(), 
                                         $query,
                                        "������ ��� �������� ����� ����");
              }
            }
          }
          // ��������� ���� � ������ �����
          $query = "UPDATE $tbl_themes 
                    SET id_forum = {$form->fields[newforum]->value}
                    WHERE id_theme = {$form->fields[id_theme]->value}";
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� �������� ����");
          }
        }
        $header = "Location: themes.php?".
                  "id_forum={$form->fields[id_forum]->value}&".
                  "page={$form->fields[page]->value}";
        header($header);
        exit();
      }
    }

    $title = '�������������� ����';  
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