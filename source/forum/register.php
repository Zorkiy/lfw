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
    // ������������� SQL-��������
    $id_forum = intval($_GET['id_forum']);

    if(!empty($_POST))
    {
      // ���������� HTML-�����
      define("REGISTER", 1);
      $error = array();
      require_once("../utils/include.register.handler.php");
      if($_REQUEST['sendmail'] == 'on') $_REQUEST['sendmail'] = "checked";
      else $_REQUEST['sendmail'] = "";
    }

    // ������������� �������� ��������
    $nameaction = "����������� �� ������";
    // ������� "�����" ��������
    require_once("../utils/topforumaction.php");

    if(!isset($action)) $action = "register.php";
    if(!isset($button)) $button = "������������������";
    $id_forum = $_GET['id_forum'];
    ?>
    <p class=linkbackbig><a href=index.php?id_forum=<?php echo htmlspecialchars($id_forum, ENT_QUOTES); ?>>��������� � ������ ���</a></p>
    <?php
      // ���� ������� ������ ������� ��
      if(!empty($error))
      {
        echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
      }
    ?>
    <form enctype='multipart/form-data' action='<?php echo htmlspecialchars($action, ENT_QUOTES); ?>' method=post>
    <table border="0" width="100%" cellpadding="0" cellspacing="0"><tr valign="top"><td>
    <table>
    <tr>
      <td><p class="fieldname">���: *</td>
      <td><input size=25 class=input type=text name=author maxlength=100 size=61 value='<?php echo htmlspecialchars($_POST['author'], ENT_QUOTES); ?>'></td></tr>
    <tr>
      <td><p class="fieldname">������: *</td>
      <td><input size=25 class=input type=password name=pswrd maxlength=100 size=61 value='<?php echo htmlspecialchars($_POST['pswrd'], ENT_QUOTES); ?>'></td></tr>
    <tr>
      <td><p class="fieldname">������ ������: *</td>
      <td><input size=25 class=input type=password name=pswrd_again maxlength=100 size=61 value='<?php echo htmlspecialchars($_POST['pswrd_again'], ENT_QUOTES); ?>'></td></tr>
    </table>    
    </td>
    <td>
      <div class="blockremark">
      <p class=texthelp>��� ����������� ��������� ����������� ������ � ������� ������ "����������������".
      ������������ ���� �������� ���������� (*).</p></div>
    </td>
    </tr>
    </table>        
    <?php
      require_once("../utils/include.register.php");
    ?>
    <input type=hidden name=id_author value='<?php echo htmlspecialchars($id_author, ENT_QUOTES); ?>'>
    <input type=hidden name=id_forum value='<?php echo htmlspecialchars($id_forum, ENT_QUOTES); ?>'>
    </form>
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
