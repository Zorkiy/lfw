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
  require_once("config/config.php");
  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ���������� ������� ������ ������ � bbCode
  require_once("dmn/utils/utils.print_page.php");
  // ���������� ��������� 
  require_once("utils.title.php");

  try
  {
    $name = new field_text("name",
                           "���",
                           true,
                           $_POST['name']);
    $city = new field_text("city",
                           "�����",
                            false,
                            $_POST['city']);
    $msg = new field_textarea("msg",
                              "���������",
                               true,
                               $_POST['msg'],
                               70,
                               10);
    $text = "� ����� �������������� ����� (��������� ����������), ��������� ���������� ������ �����������.";
    $warning = new field_paragraph($text);
    $form = new form(array("name" => $name,
                           "city" => $city, 
                           "msg" => $msg,
                           "warning" => $warning), 
                     "�������� ���������",
                     "main_txt",
                     "",
                     "in_input");
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      // ��������� ������� URL � ���������
      if(preg_match("|http://|i", $form->fields['msg']->value) ||
         preg_match("|http://|i", $form->fields['city']->value) ||
         preg_match("|http://|i", $form->fields['name']->value)) 
      {
        $error[] = "� �������� ����� �� ����������� 
                    ������������� URL";
      }
      if(preg_match("|www\.|i", $form->fields['msg']->value) ||
         preg_match("|www\.|i", $form->fields['city']->value) ||
         preg_match("|www\.|i", $form->fields['name']->value)) 
      {
        $error[] = "� �������� ����� �� ����������� 
                    ������������� URL";
      }
      // ��������� ��������� ������������� �� ���������� �����
      if(!preg_match("|[�-��]|i", $form->fields['msg']->value))
      {
        $error[] = "� �������� ����� �� ����������� 
                   ��������� ��������� �������";
      }
      // ���� ��� �������� ������� �������� - ��������� ���������
      if(empty($error))
      {
        // ��������� SQL-������ �� ���������� �������
        $query = "INSERT INTO $tbl_guestbook
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[city]->value}',
                          '{$form->fields[msg]->value}',
                          '',
                          NOW(),
                          'show')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ����������
                                   ����� �������");
        }
        // ������������ �������� �� �������� ��������
        header("Location: guestbook.php");
        exit();
      }
    }

    // ���������� ������� ������
    $pagename = "�������� ����� (�������� ���������)";
    $keywords = "�������� �����";
    require_once ("templates/top.php");

    // �������� ��������
    echo title($pagename);

    ?>
    <p class=main_txt>
        ���� ��� ��������� ������:
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[b]', '[/b]'); return false;" >
           [b]<b>������</b>[/b]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[i]', '[/i]'); return false;">
           [i]<i>���������</i>[/i]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[u]', '[/u]'); return false;" >
           [u]<u>������������</u>[/u]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[sup]', '[/sup]'); return false;" >
           [sup]<sup>������� ������</sup>[/sup]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[sub]', '[/sub]'); return false;" >
           [sub]<sub>������ ������</sub>[/sub]</a>
    </p>
    <?php
    // ������� ��������� �� ������� ���� ��� �������
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\" class=main_txt>$err</span><br>";
      }
    }
    // ������� HTML-����� 
    $form->print_form();

    //���������� ������ ������
    require_once ("templates/bottom.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>
<script language='JavaScript1.1' type='text/javascript'>
<!--
  function tag(text1, text2)
  {
     if ((document.selection))
     {
       document.form.msg.focus();
       document.form.document.selection.createRange().text =
       text1+document.form.document.selection.createRange().text + text2;
     } else document.form.msg.value += text1+text2;
  }
//-->
</script>