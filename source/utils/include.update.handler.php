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

  if(!defined("UPDATE")) return;

  ///////////////////////////////////////////////////////////
  // ���� ���������� � ��������
  ///////////////////////////////////////////////////////////
  // �������� ������ ������������ ������� POST
  $pswrd       = $_POST['pswrd'];
  $pswrd_new   = $_POST['pswrd_new'];
  $pswrd_again = $_POST['pswrd_again'];
  $email       = trim($_POST['email']);
  $icq         = trim($_POST['icq']);
  $url         = trim($_POST['url']);
  $about       = trim($_POST['about']);
  $sendmail    = $_POST['sendmail'];
  $id_author   = intval($_POST['id_author']);
  $id_forum    = intval($_POST['id_forum']);
  // �������������� ���������� ��� ���������� � SQL-������, ���������
  // ��� ����������� ��� ������ ������� mysql_escape_string();
  if (!get_magic_quotes_gpc())
  {
    $email       = mysql_escape_string($email);
    $author      = mysql_escape_string($author);
    $pswrd       = mysql_escape_string($pswrd);
    $pswrd_new   = mysql_escape_string($pswrd_new);
    $pswrd_again = mysql_escape_string($pswrd_again);
    $about       = mysql_escape_string($about);
    $message     = mysql_escape_string($message);
    $url         = mysql_escape_string($url);
    $sendmail    = mysql_escape_string($sendmail);
  }
  // ��������� ������������ ����� ������
  if(empty($author)) $error[] = "�� ������� ���";
  if(strlen($author) > 20) $error[] = "������� ������� ���";
  if($pswrd_new != $pswrd_again) $error[] = "������ � �������";

  // ��������� ������������ ����� e-mail
  if($settings['user_email_required'] == 'yes')
  {
    if (!preg_match("/^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$/i", $email))
    {
      $error[] = "������� e-mail � ���� <i>something@server.com</i>";
    }
  }
  else if(!empty($email))
  {
    if (!preg_match("/^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$/i", $email))
    {
      $error[] = "������� e-mail � ���� <i>something@server.com</i>";
    }
  }

  if(!empty($icq))
  {
    if(!preg_match("|^[\d]+$|",$icq)) $error[] = "������� ����� ICQ � ���� �����";
  }

  // ��������� ����� �� ���������� ������ ������ ��� ����������
  // ����� ����
  if(!empty($email))
  {
    if($sendmail == "on") $sendmail = 'yes';
    else $sendmail = 'no';
  } else $sendmail = 'no';

  // ���� �������������
  $auth = get_user($author, $pswrd);
  if(!$auth) $error[] = "������ �� ������������� ������";

  $url_photo = "";
  // ��������� �� ��������� �� �����������
  if(!empty($_REQUEST['delete_photo']) || !empty($_FILES['photo']['tmp_name']))
  {
    @unlink($auth['photo']);
    $url_photo = "photo = '',";
  }
  
  // ���� �������� ����� �� ������
  // ���� ���� ������ ���������� �� ������,
  // ���������� � �� ������ � ���������������
  if(!empty($_FILES['photo']['tmp_name']))
  {
    // ��������� �� ������ �� ���� 512 ��
    if($_FILES['photo']['size'] > $settings['size_photo'])
    {
      $error[] = "������� ������� ���������� (����� ".valuesize($settings['size_photo']).")";
    }
    else 
    {
      // ��������� �� ����� ����� ����������
      $ext = strrchr($_FILES['photo']['name'], "."); 
      // ��������� ��������� ����� ������ ������������ �������
      $extentions = array(".jpg",".gif");
      // ��������� ���� � �����    
      if(in_array($ext, $extentions))
      {
        $path="photo/".date("YmdHis",time()).$ext; 
        // ���������� ���� �� ��������� ���������� ������� �
        // ���������� /photo Web-����������
        if (copy($_FILES['photo']['tmp_name'], $path))
        {
          // ���������� ���� �� ��������� ����������
          unlink($_FILES['photo']['tmp_name']);
          // �������� ����� ������� � �����
          chmod($path, 0644);
          $url_photo = " photo = '$path',";
        }
      }
    }
  }
  // ���� ������ ��� - ��������� ��������������� ������ ������������
  if(empty($error))
  {
    if(!empty($pswrd_new))
    {
      $password = "passw = ".get_password($pswrd_new).",";
      $pswrd = $pswrd_new;
    }
    else $password = "";
    ///////////////////////////////////////////////////////////
    // ���� ������������ � ���������� SQL-�������
    ///////////////////////////////////////////////////////////
    // ��������� SQL-������ �� ���������� ����������
    $query = "UPDATE $tbl_authors
              SET $password
                  email = '$email',
                  sendmail = '$sendmail',
                  url = '$url',
                  icq = '$icq',
                  $url_photo
                  about = '$about'
              WHERE id_author = $auth[id_author]";
    if(!mysql_query($query))
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ����������
                                ��������������� ������
                                ����������");
    }
    // ������� �� �����
    setallcookie($author, $pswrd);
    // �������������� ������������ � ��� �������
    @header("Location: info.php?id_author=$auth[id_author]&id_forum=1");
    exit();
  }
?>