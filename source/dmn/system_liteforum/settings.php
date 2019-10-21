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
  // ���������� SQL-�������
  require_once("utils.query_result.php");
  // ��������� ������
  require_once("../../utils/utils.settings.php");

  try
  {
    if(empty($_POST))
    {
      // ��������� ��������� ������
      // �� ������� settings
      $settings = get_settings();
      $_REQUEST = $settings;
      $_REQUEST['nameforum'] = $settings['name_forum'];
      $_REQUEST['numberthemes'] = $settings['number_themes'];
      $_REQUEST['sizefile'] = $settings['size_file'];
      $_REQUEST['sizephoto'] = $settings['size_photo'];
      if($_REQUEST['send_mail'] == 'yes') $_REQUEST['sendmail'] = true;
      else $_REQUEST['sendmail'] = false;
      if($_REQUEST['show_struct_switch'] == 'yes') $_REQUEST['showstructswitch'] = true;
      else $_REQUEST['showstructswitch'] = false;
      if($_REQUEST['show_forum_switch'] == 'yes') $_REQUEST['showforumswitch'] = true;
      else $_REQUEST['showforumswitch'] = false;
      if($_REQUEST['show_personally'] == 'yes') $_REQUEST['showpersonally'] = true;
      else $_REQUEST['showpersonally'] = false;
      if($_REQUEST['user_email_required'] == 'yes') $_REQUEST['useremailrequired'] = true;
      else $_REQUEST['useremailrequired'] = false;
      if($_REQUEST['email_distribution'] == 'yes') $_REQUEST['emaildistribution'] = true;
      else $_REQUEST['emaildistribution'] = false;
      if($_REQUEST['registration_required'] == 'yes') $_REQUEST['registrationrequired'] = true;
      else $_REQUEST['registrationrequired'] = false;
    }

    $nameforum = new field_text("nameforum",
                           "�������� ������",
                            true,
                            $_REQUEST['nameforum']);
    $numberthemes = new field_text("numberthemes",
                           "���������� �������",
                            true,
                            $_REQUEST['numberthemes']);
    $sizefile = new field_text_int("sizefile",
                           "������������ ������ �������������� �����, ����",
                            true,
                            $_REQUEST['sizefile']);
    $sizephoto = new field_text_int("sizephoto",
                           "������������ ������ ����������, ����",
                            true,
                            $_REQUEST['sizephoto']);
    $sendmail = new field_checkbox("sendmail",
                                   "E-mail �������� ��� ���������� ����� ���� (��������������)",
                                   $_REQUEST['sendmail']);
    $email = new field_text_email("email",
                           "E-mail ��������������",
                            false,
                            $_REQUEST['email']);
    $showstructswitch = new field_checkbox("showstructswitch",
                                   "������������ ����� \"��������\" � \"�����������\" ������ ������",
                                   $_REQUEST['showstructswitch']);
    $showforumswitch = new field_checkbox("showforumswitch",
                                   "������������ ����� ��������� ������",
                                   $_REQUEST['showforumswitch']);
    $hello = new field_text("hello",
                           "�����������",
                            true,
                            $_REQUEST['hello']);
    $cooktime = new field_text_int("cooktime",
                           "���� �������� cookie, �����",
                            true,
                            $_REQUEST['cooktime']);
    // �������� ������� ������ ������� � �������
    // ��� ����� ��������� ����� skins � ������
    // � ����������
    $skin_dir = opendir("../../skins");
    while(($dir = readdir($skin_dir)))
    {
      // ���� ��������� ������ � ����� skins
      // �������� �����������, ������� ��� �
      // ������ $skin_list()
      if(@is_dir("../../skins/".$dir) && $dir != "." && $dir != "..") $skin_list[$dir] = $dir;
    }
    // ��������� ����������
    closedir($skin_dir);

    $skin = new field_select("skin",
                             "������� ����",
                              $skin_list,
                              $_REQUEST['skin']);
    $showpersonally = new field_checkbox("showpersonally",
                             "������ ���������",
                              $_REQUEST['showpersonally']);
    $useremailrequired = new field_checkbox("useremailrequired",
                             "��� ����������� e-mail ����������",
                              $_REQUEST['useremailrequired']);
    $emaildistribution = new field_checkbox("emaildistribution",
                             "E-mail �������� ��� ���������� ����� ���� (����)",
                              $_REQUEST['emaildistribution']);
    $registrationrequired = new field_checkbox("registrationrequired",
                             "����������� �����������",
                              $_REQUEST['registrationrequired']);
  
    $form = new form(array("nameforum"            => $nameforum, 
                           "numberthemes"         => $numberthemes,
                           "sizefile"             => $sizefile,
                           "sizephoto"            => $sizephoto,
                           "hello"                => $hello,
                           "cooktime"             => $cooktime,
                           "registrationrequired" => $registrationrequired,
                           "showstructswitch"     => $showstructswitch,
                           "showforumswitch"      => $showforumswitch,
                           "showpersonally"       => $showpersonally,
                           "useremailrequired"    => $useremailrequired,
                           "emaildistribution"    => $emaildistribution,
                           "sendmail"             => $sendmail,
                           "email"                => $email,
                           "skin"                 => $skin), 
                     "���������",
                     "field");

    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ���������� ����� �� ���������� �����������
        // �� e-mail
        if($form->fields['sendmail']->value) $send_mail = "yes";
        else $send_mail = "no";
        // ���������� ������� �� �������� ����
        // ������������ ����� �������� � ����������� ��������
        if($form->fields['showstructswitch']->value) $show_struct_switch = "yes";
        else $show_struct_switch = "no";
        // ���������� ������� �� �������� ������
        // � ������ ���������� � �������� ������
        // � ���������� ������ ������������ ����� ���������
        if($form->fields['showforumswitch']->value) $show_forum_switch = "yes";
        else $show_forum_switch = "no";
        // ���������� ������� �� �������� ������
        // ���������
        if($form->fields['showpersonally']->value) $show_personally = "yes";
        else $show_personally = "no";
        // ��� ����������� ������ ������������ �����������
        // ��������� e-mail
        if($form->fields['useremailrequired']->value) $user_email_required = "yes";
        else $user_email_required = "no";
        // ��� �������� ����� ���� �������� ���������� �����������
        // �� ���� �� e-mail
        if($form->fields['emaildistribution']->value) $email_distribution = "yes";
        else $email_distribution = "no";
        // ��������� ����������� �� ����������� �� ������
        // �������������������� ���������� �� ������ ���������
        // ���� � ���������
        if($form->fields['registrationrequired']->value) $registration_required = "yes";
        else $registration_required = "no";
        // ��������� ��������� �� ������������� �����������
        // ������������������ ����������
        //if($confirm_registration == "on") $flgcnf = "yes";
        //else $flgcnf = "no";
        $confirm_registration = "no";
        // ��������� ���������� ���������
        $query = "UPDATE $tbl_settings 
                  SET name_forum            = '{$form->fields[nameforum]->value}',
                      number_themes         = '{$form->fields[numberthemes]->value}',
                      size_file             = '{$form->fields[sizefile]->value}',
                      size_photo            = '{$form->fields[sizephoto]->value}',
                      send_mail             = '$send_mail',
                      email                 = '{$form->fields[email]->value}',
                      show_struct_switch    = '$show_struct_switch',
                      show_forum_switch     = '$show_forum_switch',
                      show_personally       = '$show_personally',
                      user_email_required   = '$user_email_required',
                      email_distribution    = '$email_distribution',
                      registration_required = '$registration_required',
                      confirm_registration  = '$confirm_registration',
                      hello                 = '{$form->fields[hello]->value}',
                      cooktime              = '{$form->fields[cooktime]->value}',
                      skin                  = '{$form->fields[skin]->value}'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �������������� �������� ������");
        }

        // ������������ ���������������
        // �� ������� �������� �����������������
        header("Location: settings.php");
        exit();
      }
    }

    // ������ ��������
    $title = '��������� ������';  
    $pageinfo = '<p class=help>�� ������ �������� ����� ���������
                 ��������� �����</p>';
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