<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  // ���������� �.�. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // ���������� SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ������� ��� ������ �� ��������
  require_once("../utils/utils.time.php");
  // ������� ��� ������ � �����������
  require_once("../utils/utils.posts.php");
  // ��������� ������
  require_once("../utils/utils.settings.php");
  // ������� ��� ������ � ��������������
  require_once("../utils/utils.users.php");
  // ������� ��� ������ � �������
  require_once("../utils/utils.files.php");

  try
  {
    // ��������� ��������� ������
    $settings = get_settings();

    $id_forum = intval($_GET['id_forum']);
    $author = $_COOKIE['current_author'];
    $pass = $_COOKIE['wrdp'];
    if(empty($author) || empty($pass))
    {
      // ����������� �� ������������ - ���������� �
      // ����� �����������
      header("Location: enter.php");
      exit();
    }
    // ��������� ��������� �� �������� ������ � �������
    // ������� ����������
    $auth = get_user($author, $pass);
    if(!$auth)
    {
      // ����������� �� ������������ - ���������� �
      // ����� �����������
      header("Location: enter.php");
      exit();
    }
    // ���� ������ �����, ��������� ��
    if(empty($_POST))
    {
      $_REQUEST = $auth;
      if($_REQUEST['sendmail'] == 'yes') $_REQUEST['sendmail'] = "checked";
      else $_REQUEST['sendmail'] = "";
    }
    else
    {
      if($_REQUEST['sendmail'] == 'on') $_REQUEST['sendmail'] = "checked";
      else $_REQUEST['sendmail'] = "";
      if($_REQUEST['delete_photo'] == 'on') $_REQUEST['delete_photo'] = "checked";
      else $_REQUEST['delete_photo'] = "";
    }

    if(!empty($_POST))
    {
      // ���������� HTML-�����
      define("UPDATE", 1);
      $error = array();
      require_once("../utils/include.update.handler.php");
    }

    // ������������� ��� ��������
    $nameaction = "���������� ��������������� ������";
    // �������� "�����" ��������
    include "../utils/topforumaction.php";
    ?>
    <p class=linkbackbig><a href='<? echo "index.php?id_forum=".$id_forum ?>'>��������� � �����</a></p>
    <?php
      // ���� ������� ������ ������� ��
      if(!empty($error))
      {
        echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
      }
    ?>
    <form enctype='multipart/form-data' action=update.php method=post>  
    <table border="0" width="100%" cellpadding="0" cellspacing="0"><tr valign="top"><td>
    <table>
        <tr>
          <td><p class="fieldname">���:</td>
          <td><p class="authortext"><?php echo htmlspecialchars($_REQUEST['name'], ENT_QUOTES); ?></p></td>
        </tr>
        <tr>
          <td><p class="fieldname">������:*</td>
          <td><input size=25 class=input type=password name=pswrd_new maxlength=100 size=61 value='<?php echo htmlspecialchars($_REQUEST['pswrd_new'], ENT_QUOTES); ?>'></td>
        </tr>
        <tr>
          <td><p class="fieldname">������ ������:*</td>
          <td><input size=25 class=input type=password name=pswrd_again maxlength=100 size=61 value='<?php echo htmlspecialchars($_REQUEST['pswrd_again'], ENT_QUOTES); ?>'></td>
        </tr>
        <input type=hidden name=pswrd value='<?php echo htmlspecialchars($pass, ENT_QUOTES); ?>'>    
    </table>    
    </td>
    <td>
        <div class="blockremark">
        <p class=texthelp>��������� ����������� ������ � ������� ������ "������ ���������".
        ������������ ���� �������� ���������� (*).</p></div>
    </td>
    </tr>
    </table>        
    <?php
      $button = "��������";
      require_once("../utils/include.register.php");
    ?>
    <input type=hidden name=id_forum value='<?php echo $id_forum; ?>'>
    <input type=hidden name=author value='<?php echo htmlspecialchars($_REQUEST['name'], ENT_QUOTES); ?>'>
    <?php
      // ������� ���������� ��������
      require_once("../utils/bottomforumaction.php");
  }
  catch(ExceptionObject $exc) 
  {
    require_once("exception_object_debug.php"); 
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