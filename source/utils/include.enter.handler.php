<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  if(!defined("ENTER")) return;

  ///////////////////////////////////////////////////////////
  // ���� ���������� � ��������
  ///////////////////////////////////////////////////////////
  // �������� ������ ������������ ������� POST
  $pswrd  = $_POST['pswrd'];
  $author = $_POST['author'];
  $id_forum = intval($_POST['id_forum']);
  // �������������� ���������� ��� ���������� � SQL-������, ���������
  // ��� ����������� ��� ������ ������� mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $author      = mysql_escape_string($author);
    $pswrd       = mysql_escape_string($pswrd);
  }

  // ���� �������������
  $auth = get_user($author, $pswrd);
  if(!$auth) $error[] = "������ �� ������������� ������";

  // ���� ������ ��� - ������������ ���� ������������
  if(empty($error))
  {
    // ��������� ���������� ��������� ������ � ������� �������
    $query = "SELECT COUNT(*) FROM $tbl_posts 
              WHERE id_author = $auth[id_author]";
    $pst = mysql_query($query);
    if(!$pst)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� � 
                                ���� �������");
    }
    $count = mysql_result($pst, 0);
    $query = "SELECT COUNT(*) FROM $tbl_archive_posts 
              WHERE id_author = $auth[id_author]";
    $pst = mysql_query($query);
    if(!$pst)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� � 
                                ���� �������");
    }
    $count += mysql_result($pst,0);

    $query_author = "UPDATE $tbl_authors 
                     SET themes = $count 
                     WHERE id_author = $auth[id_author]";
    if(!mysql_query($query_author))
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ���������� ������");
    }
    // ������������� � ������� ������ � ��� ������
    setallcookie($author, $pswrd);
    // ��������� ���� ���������� ���������
    settime($author, true, $id_forum);
    // ��������� �������
    @header("Location: index.php?id_forum=$id_forum");
    exit();
  }
?>