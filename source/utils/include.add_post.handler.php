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

  if(!defined("ADD_POST")) return;

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
  if($sid_add_theme != $_POST['sid_add_theme']) $error[] = "������ ���������� ����";
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

  // ��������
  if($id_author == 0)
  {
    // ��� �������������������� ������������� ��������
    // ��������
    if(strpos($message,".at"))  exit();
    if(strpos($message,".be"))  exit();
    if(strpos($message,".biz"))  exit();
    if(strpos($message,".com"))  exit();
    if(strpos($message,".es"))  exit();
    if(strpos($message,".ee"))  exit();
    if(strpos($message,".edu"))  exit();
    if(strpos($message,".de"))  exit();
    if(strpos($message,".info"))  exit();
    if(strpos($message,".it"))  exit();
    if(strpos($message,".in"))  exit();
    if(strpos($message,".net"))  exit();
    if(strpos($message,".no"))  exit();
    if(strpos($message,".org"))  exit();
    if(strpos($message,".pl"))  exit();
    if(strpos($message,".ru"))  exit();
    if(strpos($message,".sk"))  exit();
    if(strpos($message,".su"))  exit();
    if(strpos($message,".ws"))  exit();
    if(strpos($message,".us"))  exit();
    if(strpos($message,".name"))  exit();

    if(strpos($message,".gen.in"))  exit();
    if(strpos($message,"porno"))  exit();
    if(strpos($message,"narod.ru"))  exit();

    $number = preg_match_all("|<a[\s]+href=[^>]+>[^<]+<|is",$message,$out);
    if($number > 25) exit();
    $number = preg_match_all("#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU",$message,$out);
    if($number > 25) exit();
  }
  ///////////////////////////////////////////////////////////
  // ���� �������� ������������ ���������� ���������
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
    if($hide == 'lock') $error[] = "���� �������, ��������� ��������� ������";
  } else $error[] = "���� �������, ��������� ��������� ������";
  // ����� ��� ������ �� ����������, ��� ��� ����� �������� � �� �������� ����
  // ��������� ���� �������� - ������������ ��� ��������
  $query = "SELECT id_theme FROM $tbl_posts 
            WHERE id_post=$id_post";
  $idn = mysql_query($query);
  if(!$idn)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ���������� ���������� ����");
  } 
  if(mysql_num_rows($idn))
  {
    $id_theme_check = mysql_result($idn,0);
    if($id_theme_check != $id_theme) $error[] = "������� ������ �� ������ ����";
  } else $error[] = "���������, �� ������� �� ������ �������� �� ����������";
  // ���� ������ ��� - ��������� ���������
  if(empty($error))
  {
    ///////////////////////////////////////////////////////////
    // ���� �������� ����� �� ������
    ///////////////////////////////////////////////////////////
    require_once("../utils/loadfile.php");
    ///////////////////////////////////////////////////////////
    // ���� ���������� ���������
    ///////////////////////////////////////////////////////////
    $query = "INSERT INTO $tbl_posts 
              VALUES(NULL,
                    '$message',
                    '',
                    '$path',
                    '$author',
                    $id_author,
                    'show',
                     NOW(),
                     $id_post,
                     $id_theme)";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ������ ���������");
    }
    // ��������� ����� ���������� ������ ��� ����
    $query = "UPDATE $tbl_themes 
              SET `time` = NOW(), 
                  last_author = '$author',
                  id_last_author = $id_author
              WHERE id_theme = $id_theme";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ���������� ����");
    }
    // � ��� �� ���������� ����������� ������� ���������
    $query = "UPDATE $tbl_authors
              SET themes = themes + 1
              WHERE id_author = $id_author";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ���������� ������");
    }
    // � ������ ������ ������������ �������������� �������
    // � ����     
    if($_POST['personally'] == 'set') $url = "personallyread.php?id_forum=$id_forum&id_theme=$id_theme";
    else $url = "read.php?id_forum=$id_forum&id_theme=$id_theme";
  
    @header("Location: $url");
    exit();
  }
?>