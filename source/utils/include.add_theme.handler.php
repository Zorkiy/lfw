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

  if(!defined("ADD_THEME")) return;

  // ��������� ��������� ������
  $settings = get_settings();

  ///////////////////////////////////////////////////////////
  // ���� ����������
  ///////////////////////////////////////////////////////////
  // ��������� �������� ���������� ������� POST ��
  // ���������������� ������� $_POST
  $author = trim($_POST['author']);
  $pswrd = $_POST['pswrd'];
  $message = trim($_POST['message']);
  $sub = trim($_POST['sub']);
//  $id_author = intval($_POST['id_author']);
  $id_forum = intval($_POST['id_forum']);

  if(empty($sub))     $error[] = "�� ������� ���� ���������";
  if(empty($author))  $error[] = "�� ������� ���";
  if(empty($message)) $error[] = "��������� �� �������";
  if($sid_add_theme != $_POST['sid_add_theme']) $error[] = "������ ���������� ���������";
  // �������������� ���������� ��� ���������� � SQL-������, ���������
  // ��� ����������� ��� ������ ������� mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $sub     = mysql_escape_string($sub);
    $author  = mysql_escape_string($author);
    $pswrd   = mysql_escape_string($pswrd);
    $message = mysql_escape_string($message);
  }
  ///////////////////////////////////////////////////////////
  // ���� �������������
  ///////////////////////////////////////////////////////////
  define("ADDMESSAGE",1);
  require_once("../utils/autreg.php");

  $id_author = intval($id_author);

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
  if(empty($error))
  {
    ///////////////////////////////////////////////////////////
    // ���� ���������� ����� ����
    ///////////////////////////////////////////////////////////
    // ��������� SQL-������ �� ���������� ����
    $query = "INSERT INTO $tbl_themes VALUES(
             NULL,
             '$sub',
             '$author',
             $id_author,
             '$author',
             $id_author,         
             'show',
             NOW(),
             $id_forum)";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ����������
                               ����� ����");
    }
    // �������� ��������� ���� ������ ��� ����������� ������
    // ��� ����������� ��� ���������� ��������� � �����
    $id_theme = mysql_insert_id();
    ///////////////////////////////////////////////////////////
    // ���� �������� ����� �� ������
    ///////////////////////////////////////////////////////////
    require_once("../utils/loadfile.php");
    ///////////////////////////////////////////////////////////
    // ���� ���������� ���������
    ///////////////////////////////////////////////////////////
    // ��������� SQL-������ �� ���������� ���������
    $query = "INSERT INTO $tbl_posts 
              VALUES (NULL,
                     '$message',
                     '',
                     '$path',
                     '$author',
                     $id_author,
                     'show',
                     NOW(),
                     0,
                     $id_theme)";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ���������");
    }
  
    // ��������� ���������� ����������� ������� ���������
    $query = "UPDATE $tbl_authors
              SET themes = themes + 1
              WHERE id_author = $id_author";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� ����������
                               ������������");
    }
  
    ///////////////////////////////////////////////////////////
    // ���� ������������ ��������� ���������
    ///////////////////////////////////////////////////////////
    // �������� ���� �� ������ ������������ �����
    $path_parts = pathinfo($_SERVER['PHP_SELF']);
    // ��������� �������� ������� ������, �� ������� ����������� ����
    $query = "SELECT name FROM $tbl_forums 
              WHERE id_forum=$id_forum";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� �������� ������");
    }
    $part_forum_name = mysql_result($frm,0);
    // �������� ��������� �����������
    $thm = "����� ���� �� ������ $settings[name_forum] - $sub";
    $msg = "�������� ����: $sub".
           "\n������: $part_forum_name".
           "\nURL: http://$_SERVER[SERVER_NAME]{$path_parts[dirname]}/read.php?id_forum=$id_forum&id_theme=$id_theme".
           "\n����� : $author".
           "\n\nIP-����� : $_SERVER[REMOTE_ADDR]".
           "\n\n����� ��������� : $message".
           "\n\n";
    // �������� ���������
    $thm =  convert_cyr_string(stripslashes($thm),'w','k'); 
    $msg =  convert_cyr_string(stripslashes($msg),'w','k'); 
    $header = "Content-Type: text/plain; charset=KOI8-R\r\n\r\n";
    // ���� �� �������� ����������������� ������
    // ����� ������� ��������� - ���������� ������
    if($settings['send_mail'] == "yes")
    {
      @mail($settings['email'], $thm, $msg, $header);
    }
    // ���������� ������, �����������, ������� ��������
    // �������� ����������� � ����� �����
    if($settings['email_distribution'] == "yes")
    {
      $query = "SELECT email FROM $tbl_authors 
                WHERE sendmail = 'yes'";
      $sdm = mysql_query($query);
      if(!$sdm)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� �������� �����������");
      }
      if(mysql_num_rows($sdm)>0)
      {
        while($sendemail = mysql_fetch_array($sdm)) @mail($sendemail['email'], $thm, $msg);
      }
    }
    // �������������� ������� �� ������� �������� ������
    @header("Location: index.php?id_forum=$id_forum");
    exit();
  }
?>