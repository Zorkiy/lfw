<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������
  session_start();
  $sid_add_theme = session_id();
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  if(!defined("EDIT_POST")) return;

  // ��������� ��������� ������
  $settings = get_settings();

  ///////////////////////////////////////////////////////////
  // ���� ����������
  ///////////////////////////////////////////////////////////
  // ��������� �������� ���������� ������� POST ��
  // ���������������� ������� $_POST
  $author    = trim($_POST['author']);
  $pswrd     = $_POST['pswrd'];
  $message   = trim($_POST['message']);
  $id_author = intval($_POST['id_author']);
  $id_forum  = intval($_POST['id_forum']);
  $id_theme  = intval($_POST['id_theme']);
  $id_post   = intval($_POST['id_post']);

  if(empty($author))  $error[] = "�� ������� ���";
  if(empty($message)) $error[] = "��������� �� �������";
  if($sid_add_theme != $_POST['sid_add_theme']) $error[] = "������ �������������� ���������";
  // �������������� ���������� ��� ���������� � SQL-������, ���������
  // ��� ����������� ��� ������ ������� mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $author  = mysql_escape_string($author);
    $pswrd   = mysql_escape_string($pswrd);
    $message = mysql_escape_string($message);
  }
  ///////////////////////////////////////////////////////////
  // ���� �������������
  ///////////////////////////////////////////////////////////
  define("ADDMESSAGE",1);
  require_once("../utils/autreg.php");

  ///////////////////////////////////////////////////////////
  // ���� �������� ������������ �������������� ���������
  ///////////////////////////////////////////////////////////
  $query = "SELECT hide FROM $tbl_themes 
            WHERE id_theme = $id_theme";
  $idn = mysql_query($query);
  if(!$idn)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ���������� ���������� ����");
  }
  if(mysql_num_rows($idn))
  {
    $hide =  mysql_result($idn,0);
    if($hide == 'lock') $error[] = "���� �������, ��������� ������������� ������";
  } else $error[] = "���� �������, ��������� ������������� ������";
  // ����� ��� ������ �� ����������, ��� ��� ����� �������� � �� �������� ����
  // ��������� ���� �������� - ������������ ��� ��������
  $query = "SELECT * FROM $tbl_posts 
            WHERE id_post = $id_post";
  $idn = mysql_query($query);
  if(!$idn)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ���������� ���������� ����");
  } 
  if(mysql_num_rows($idn))
  {
    $post = mysql_fetch_array($idn);
    $id_theme_check = $post['id_theme'];
    if($id_theme_check != $id_theme) $error[] = "������� �������������� ������ �� ������ ����";
    if($id_author != $post['id_author']) $error[] = "������ ������������� ����� ����";
  } else $error[] = "��������� ������� �� ������ ������������� �� ����������";
  // ���� ������ ��� - ����������� ���������
  if(empty($error))
  {
    ///////////////////////////////////////////////////////////
    // ���� �������� ������� �����������
    ///////////////////////////////////////////////////////////
    $update_path = "";
    // ���� �������� � ���� ��� ����������� ������� ������
    // "-" ��� ������ �������� ������� ���������� ����������
    if (!empty($_FILES['attach']['name']) || !empty($_POST['delete_file']))
    {
      $query = "SELECT putfile, id_post 
                FROM $tbl_posts
                WHERE id_post = $id_post";
      $pct = mysql_query($query);
      if(!$pct)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� � ������� ���������");
      } 
      if(mysql_num_rows($pct))
      {
        $file = mysql_result($pct,0);
        if(file_exists($file) && $file != "-") @unlink($file);
      }
      $update_path = " putfile = '', ";
    }
    ///////////////////////////////////////////////////////////
    // ���� �������� ����� �� ������
    ///////////////////////////////////////////////////////////
    require_once("../utils/loadfile.php");
    if(!empty($path)) $update_path = " putfile = '$path', ";
    ///////////////////////////////////////////////////////////
    // ���� ���������� ���������
    ///////////////////////////////////////////////////////////
    $query = "UPDATE $tbl_posts 
              SET $update_path
                  name = '$message'
              WHERE id_post = $id_post";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ���������");
    }
    // � ������ ������ ������������ �������������� �������
    // � ����     
    if($_POST['personally'] == 'set') $url = "personallyread.php?id_forum=$id_forum&id_theme=$id_theme";
    else $url = "read.php?id_forum=$id_forum&id_theme=$id_theme";
  
    @header("Location: $url");
    exit();
  }
?>