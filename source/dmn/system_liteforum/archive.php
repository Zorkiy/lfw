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

  // �������� ����� ����������� �� ����� ���������� ���������
  @set_time_limit(0);

  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� SQL-�������
  require_once("utils.query_result.php");

  try
  {
    // ��������� ��������� ���� ����, ������� ��������� � 
    // �������� �������
    $query = "SELECT id_theme FROM $tbl_archive_number 
              LIMIT 1";
    $id_theme_archive = query_result($query);
    // ��� ����, ������� ����� ��������� ���� ���� $id_theme_archive
    // ��������� � ������, ���, ��� ���� - � "����� ������"

    // ��������� ��������� ����� ���� � "����� ������"
    $query = "SELECT MAX(id_theme) AS id_theme FROM $tbl_themes";
    $id_theme = query_result($query);

    if(empty($_POST))
    {
      $_REQUEST['idthemearchive'] = $id_theme_archive;
    }

    $idthemearchive = new field_text_int("idthemearchive",
                           "���������� ��� � ������",
                            true,
                            $_REQUEST['idthemearchive']);

    $form = new form(array("idthemearchive" => $idthemearchive), 
                     "����������� � �����",
                     "field");

    if(!empty($_POST))
    {
      // ��������� �� �������� �� ����� ������������ ���
      // ������ 
      if($form->fields['idthemearchive']->value > $idtheme)
      {
        $error[] = "��������, �� ��������, ������� ��� ��� � ������";
      }
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ���������� ��������� ������
        $query = "INSERT IGNORE INTO $tbl_archive_posts 
                  SELECT * FROM $tbl_posts 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ����������� ��������� ������");
        }
        // ���� ������
        $query = "SELECT * FROM $tbl_themes 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        $thm = mysql_query($query);
        if(!$thm)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� ��� ������");
        }
        if(mysql_num_rows($thm))
        {
          while($themes = mysql_fetch_array($thm))
          {
            $id_theme = $themes['id_theme'];
            $name = mysql_escape_string($themes['name']);
            $author = mysql_escape_string($themes['author']);
            $id_author = $themes['id_author'];
            $last_author = mysql_escape_string($themes['last_author']);
            $id_last_author = $themes['id_last_author'];
            $hide = $themes['hide'];
            $time = $themes['time'];
            $id_forum = $themes['id_forum'];
            $query = "SELECT COUNT(*) FROM $tbl_posts
                      WHERE id_theme = $id_theme AND 
                            hide != 'hide'";
            $posts_in_topic = query_result($query);
            $val[] = "($id_theme,'$name','$author',$id_author,".
                     "'$last_author',$id_last_author,'$hide',".
                     "'$time',$posts_in_topic,$id_forum)";
          }
          $query = "INSERT INTO $tbl_archive_themes VALUES ".implode(",",$val);
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� ����������� ��� ������");
          }
        }
        // ������� ������ ���������
        $query = "DELETE FROM $tbl_posts 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������� ��������� ������");
        }
        // ���� ������
        $query = "DELETE FROM $tbl_themes 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������� ��� ������");
        }
        // ��������� ��� �������� ����, ������� �� ������
        $query = "UPDATE $tbl_archive_themes 
                  SET hide = 'lock' 
                  WHERE hide = 'show'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������� ��� ������");
        }
        // ��������� ��������� ����� �������� ����
        $query = "UPDATE $tbl_archive_number 
                  SET id_theme = {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ��������� ������ ��������� �������� ����");
        }
        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: archive.php");
        exit();
      }
    }

    $title = '������������� ������';  
    $pageinfo = '<p class=help>���� � ������ ���������� �� 1 �� N, 
    ��� N - ���������� ��� � ������. ����� ��� ����� ����������� � 
    �����. ���� "���������� ��� � ������" ��������, ������� ��� ��� 
    ��������� � ������. ��������� �����, ����������� � ������ ����, 
    � ������� ���������� � ������� ������ "����������� � �����". 
    ����������� ��� � ����� �������� ��������� �����, � ������� 
    ����� �� ������� ����������� 30 ������, ������� ������������� 
    ������������ �� ���� ��� ��������� ���������� ���. ����� �����, 
    ����� ����������� � ����� � ���� ��� ������ ��������� ������, 
    � ��������� ���� ����������� ��������� � ����� ��� ����������� 
    �����������, ������� ������� ��������� � ����� ������ �� ������� 
    ���.</p>';

    // �������� ��������� ��������
    require_once("../utils/top.php");
    // ����
    require_once("forummenu.php");
    
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� �������, ���� ��� �������
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".implode("<br>", $error)."</span><br>";
    }
    // ������� HTML-����� 
    $form->print_form();

    // ������� ���������� ��������
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