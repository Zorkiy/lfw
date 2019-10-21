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
    // ������������� ����������� ���������� HTML-�����  
    $nameaction = "���� �� ����� (�����������)";
    $auth = $_COOKIE['current_author'];
    $pass = $_COOKIE['wrdp'];
    if(empty($_GET['id_forum'])) $_GET['id_forum'] = 1;
    $id_forum = intval($_GET['id_forum']);

    if(!empty($_POST))
    {
      // ���������� HTML-�����
      define("ENTER", 1);
      $error = array();
      require_once("../utils/include.enter.handler.php");
    }

    $nameaction = "����";
    // ������� "�����" ��������
    include "../utils/topforumaction.php";  
    echo "<p class=linkbackbig><a href='index.php?id_forum?id_forum=$id_forum'>��������� � �����</a></p>";
    // ���� ������� ������ ������� ��
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
      <div class="blockremark">
      <p class=texthelp>��� ����������� ��� ���������� ������ ���� ��� � ������.
      �������� <a href="register.php?id_forum=<?php echo $id_forum; ?>">�����������</a>, ���� �� ��� �� ���������������� �� ������.
      </p></div>

      <table>
      <form method=post>
      <tr><td><p class="fieldname">���:</td><td><input class=input type=text name=author maxlength=100 size=50 value='<?php echo htmlspecialchars($auth); ?>'></td></tr>
      <tr><td><p class="fieldname">������:</td><td><input class=input type=password name=pswrd maxlength=100 size=50 value='<?php echo htmlspecialchars($pass); ?>'></td></tr>
      <tr><td>&nbsp;</td><td><input class=button type=submit value="�����"></td></tr>
      <input type=hidden name=id_forum value='<?php echo $id_forum; ?>'>
      </form>
      </table>
    <?php
    include "../utils/bottomforumaction.php";
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
