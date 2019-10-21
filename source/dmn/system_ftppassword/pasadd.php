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

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� ������ �����
  require_once("../../config/class.config.dmn.php");
  // ������������� ���������� � FTP-��������
  require_once("../../config/ftp_connect.php");
  // ���������� ��������� ������
  require_once("../utils/utils.password.php");
  // ���������� ������� ��� ������ � 
  // ������� .htaccess � .htpasswd
  require_once("../utils/uitls.htfiles.php");

  try
  {
    // ���������� ����� ������
    $pass_example = generate_password(10);

    $name = new field_text("name",
                           "���",
                            true,
                            $_REQUEST['name']);
    $pass = new field_password("pass",
                           "������",
                            true,
                            $_REQUEST['pass'],
                            255,
                            41,
                           "",
                           "��������, $pass_example");
    $passagain = new field_password("passagain",
                           "������",
                            true,
                            $_REQUEST['passagain'],
                            255,
                            41,
                           "",
                           "��������, $pass_example");
    $dir = new field_hidden("dir",
                            false,
                            $_REQUEST['dir']);
  
    $form = new form(array("name"      => $name, 
                           "pass"      => $pass,
                           "passagain" => $passagain,
                           "dir"       => $dir), 
                     "�������� ������������",
                     "field");

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if($form->fields['pass']->value != 
         $form->fields['passagain']->value)
      {
        $error[] = "������ �� �����";
      }
      // ��������� ��� � ������ �� ���������� �������
      $pattern = "|^[-\w\d_\"\.\[\]\(\)]+$|";
      if(!preg_match($pattern, $form->fields['name']->value))
      {
        $error[] = "������������ ������� � �����";
      }
      // ��������� ������������ ������
      if(!preg_match($pattern, $form->fields['pass']->value))
      {
        $error[] = "������������ ������� � ������";
      }

      if(empty($error))
      {
        // ������� ����������
        $dir = $form->fields['dir']->value;
        ///////////////////////////////////////////////////////
        // .htaccess
        ///////////////////////////////////////////////////////
        $path = str_replace("//","/",
                $ftp_absolute_path.$dir."/.htpasswd");
        if(!is_htaccess($ftp_handle, $dir))
        {
          // ����� .htaccess � ���������� ���, ������ ���
          // � ���������� files � ��������� �� FTP
          $content = "AuthType Basic\n".
                     "AuthName \"Fill name and password\"\n".
                     "AuthUserFile $path\n".
                     "require valid-user";
          put_htaccess($ftp_handle, $dir, $content);
        }
        else
        {
          // ���� .htpasswd � ���������� ������������
          // ��������� ���������� �����
          $content = get_htaccess($ftp_handle, $dir);
          // ���������, ������� �� � ����� ����� require valid-user, 
          // ���� ������� - ������ �� ���������, ���� ��������, ���������
          // ���������� ������
          $flag = (strpos($content, "require") !== false) && 
                  (strpos($content, "valid-user") !== false);
          if(!$flag)
          {
            $content .= "\nAuthType Basic\n".
                        "AuthName \"Fill name and password\"\n".
                        "AuthUserFile $path\nrequire valid-user";
            put_htaccess($ftp_handle, $dir, $content);
          }
          else
          {
            // ������� ������ ������
            $pattern = "#AuthType.*valid-user#is";
            $content = preg_replace($pattern, "", $content);
            // ������ �����
            $content .= "\nAuthType Basic\n".
                        "AuthName \"Fill name and password\"\n".
                        "AuthUserFile $path\n".
                        "require valid-user";
            put_htaccess($ftp_handle, $dir, $content);
          }
        }

        // ��� � ������
        $name = $form->fields['name']->value;
        $pass = $form->fields['pass']->value;
        ///////////////////////////////////////////////////////
        // .htpasswd
        ///////////////////////////////////////////////////////
        if(!is_htpasswd($ftp_handle, $dir))
        {
          // ����� .htpasswd � ���������� ���, ������ ���
          // � ���������� files � ��������� �� FTP
          $content = "$name:".crypt($pass)."\n";
          put_htpasswd($ftp_handle, $dir, $content);
        }
        else
        {
          // ���� .htpasswd � ���������� �������, ����� �������� � ���� ������
          // ��������� ���������� �����
          $content = get_htpasswd($ftp_handle, $dir);
          // ��������� ��� �� ������ ������������ � .htpasswd
          if(strpos($content, $name.":") !== false)
          {
            // ������������ ����������, ������ ������
            $pattern = "#".preg_quote($name).":[^\n]+\n#is";
            $content = preg_replace($pattern, 
            "$name:".crypt($pass)."\n", $content);
          }
          else
          {
            // ������������ ����� - ��������� �������
            $content .= "$name:".crypt($pass)."\n";
          }
          // ������ ����� ���� .htpasswd
          put_htpasswd($ftp_handle, $dir, $content);
        }
        // ������������ ���������������
        // �� ������� �������� �����������������
        $dir = $form->fields['dir']->value;
        $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
        header("Location: $url");
        exit();
      }
    }
    // ������ ��������
    $title = '��������� ������ �� ���������� '.urldecode($_GET['dir']);;
    $pageinfo = '<p class=help>������� ������ ��� ������������ 
                 � ��� ������ ��� ������ ����������� ����������.
                 ���� ������������ � ������ ������ ��� 
                 ����������, ��� ������ ����� ������ �� �����. 
                 ���� � ��������� ��� ��������� ���� .htaccess 
                 � .htpasswd, ��� �� ����� ���������� ��� 
                 �������, ����� ��������� ����� ��������� � ��� 
                 ������������ ����.</p>';

    // �������� ��������� ��������
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� �������, ���� ��� �������
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".
           implode("<br>", $error).
           "</span><br>";
    }
    // ������� HTML-����� 
    $form->print_form();
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