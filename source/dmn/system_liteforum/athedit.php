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
    $id_author = intval($_GET['id_author']);
    // ����������� ���������� �� ���� ������
    $query = "SELECT * FROM $tbl_authors
              WHERE id_author = $id_author
              LIMIT 1";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� 
                               � �������");
    }
    $author = mysql_fetch_array($ath);
    if(empty($_POST))
    {
      $_REQUEST = $author;
      // sendmail
      if($_REQUEST['sendmail'] == 'yes') $_REQUEST['sendmail'] = true;
      else $_REQUEST['sendmail'] = false;
      // ���������� ������ ������������
      if($_REQUEST['statususer'] == '') $_REQUEST['statususer'] = "user";
    }

    $name = new field_paragraph(htmlspecialchars($author['name'], ENT_QUOTES));
    $pass = new field_password("pass",
                           "������",
                            false,
                            $_REQUEST['pass']);
    $passagain = new field_password("passagain",
                           "������",
                            false,
                            $_REQUEST['passagain']);
    $email = new field_text_email("email",
                           "E-mail",
                            false,
                            $_REQUEST['email']);
    $sendmail = new field_checkbox("sendmail",
                           "�������� �����������",
                            $_REQUEST['sendmail']);
    $url = new field_text("url",
                           "URL",
                            false,
                            $_REQUEST['url']);
    $icq = new field_text("icq",
                           "ICQ",
                            false,
                            $_REQUEST['icq']);
    $about = new field_textarea("about",
                           "� ����",
                            false,
                            $_REQUEST['about']);
    $photo = new field_checkbox("photo",
                           "������� ����?",
                            $_REQUEST['photo']);
    $themes = new field_text("themes",
                           "�-�� ���������",
                            false,
                            $_REQUEST['themes']);
    $statususer = new field_select("statususer",
                           "�-�� ���������",
                            array("moderator" => "���������",
                                  "admin" => "�������������",
                                  "user" => "������������"),
                            $_REQUEST['statususer']);
    $id_author = new field_hidden_int("id_author",
                            true,
                            $_REQUEST['id_author']);
    $page = new field_hidden_int("page",
                            false,
                            $_REQUEST['page']);
  
    $form = new form(array("name"       => $name, 
                           "pass"       => $pass,
                           "passagain"  => $passagain,
                           "email"      => $email,
                           "sendmail"   => $sendmail,
                           "url"        => $url,
                           "icq"        => $icq,
                           "about"      => $about,
                           "photo"      => $photo,
                           "themes"     => $themes,
                           "statususer" => $statususer,
                           "id_author"  => $id_author,
                           "page"       => $page), 
                     "�������������",
                     "field");

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      // ���� ���� � �������� �� �����, ��������� ����� �� ���
      $pass      = $form->fields['pass']->value;
      $passagain = $form->fields['passagain']->value;
      if(!empty($pass) && 
         !empty($passagain))
      {
        if($pass != $passagain) $error[] = "������ �� �����";
      }

      if(empty($error))
      {
        // ���� ���� � ������� �� �����
        if(!empty($pass))
          $password = "passw = ".get_password($pass).",";
        else
          $password = "";

        // ������� �� ���������� email
        $email = $form->fields['email']->value;
        if(!empty($email))
        {
          if($form->fields['sendmail']->value) $sendmail = 'yes';
          else $sendmail = 'no';
        } else $sendmail = 'no';
        // ��������� �� ��������� �� �����������
        $url_photo = "";
        if($form->fields['photo']->value)
        {
          @unlink('../../forum/$author[photo]');
          $url_photo = "photo = '',";
        }
        // ���������� ������ ������������
        $statususer = '';
        switch($form->fields['statususer']->value)
        {
          case '':
          case 'user':
            $statususer = '';
            break;
          case 'moderator':
            $statususer = 'moderator';
            break;
          case 'admin':
            $statususer = 'admin';
            break;
        }

        // ��������� SQL-������ �� ����������
        // ���������� ���������
        $query = "UPDATE $tbl_authors
                  SET name       = '{$form->fields[name]->value}',
                      $password
                      email      = '{$form->fields[email]->value}',
                      sendmail   = '$sendmail',
                      url        = '{$form->fields[url]->value}',
                      icq        = '{$form->fields[icq]->value}',
                      $url_photo
                      about      = '{$form->fields[about]->value}',
                      themes     = '{$form->fields[themes]->value}',
                      statususer = '$statususer'
                  WHERE id_author = {$form->fields[id_author]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������������� ���������� � ������������");
        }

        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: authorslist.php?page={$form->fields[page]->value}");
        exit();
      }
    }

    // ������ ��������
    $title     = '�������������� ������������';
    $pageinfo  = '<p class=help>���� � �������� ����������� ������ � ��� ������, ���� ������� ������������� �� �����</p>';
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