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
  session_start();
  $sid_add_theme = session_id();
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
    // ��������� ��������� �� ������ �������
    $id_forum  = intval($_GET['id_forum']);
    $id_theme  = intval($_GET['id_theme']);
    $id_post   = intval($_GET['id_post']);
    $id_addresser = intval($_GET['id_author']);
  
    if(!empty($_POST))
    {
      // ���������� HTML-�����
      define("ADD_PERSONALLY", 1);
      $error = array();
      require_once("../utils/include.add_personally.handler.php");
    }

    // ��������� ��� ������, �������� ���������� ���������
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_addresser";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "��������� ������ � 
                               ������� �������");
    }
    if(mysql_num_rows($ath) <= 0)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "����� ����������� � 
                               ������� ������������������ �������");
    }
    $author = mysql_fetch_array($ath);

    // ����� ��������
    $nameaction = "����� ������ ��������� ��� ".htmlspecialchars($author['name']);
    // ������� "�����" ��������
    include "../utils/topforumaction.php";
  ?>
  <p class=linkbackbig><a href=# onClick='history.back()'>���������</a></p>
    <?php
    // ���� ������� ������ ������� ��
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
  <form enctype='multipart/form-data' name='form' action=addper.php method=post>
  <input type=hidden name=sid_add_theme value='<?php echo $sid_add_theme; ?>'>
  <input type=hidden name=id_addresser value='<?php echo $id_addresser; ?>'>
  <table border="0" width="100%"><tr valign="top"><td>
  <table border="0" >
    <tr valign="top">
      <td><p class="fieldname">���:</td>
      <td><input size=25 class=input type=text name=author size=61 maxlength=100 value='<?php echo htmlspecialchars($_COOKIE['current_author'], ENT_QUOTES); ?>'></td></tr>
    <tr>
      <td><p class="fieldname">������:</td>
      <td><input size=25 class=input type=password name=pswrd size=61 maxlength=100 value='<?php echo htmlspecialchars($_COOKIE['wrdp'], ENT_QUOTES); ?>'></td></tr>
        </table>
    </td>
    <td>
        <div class="blockremark">
        <p class=texthelp>
        <a href=# onClick="javascript:click_link()" href=#>����������</a><br><br>       
        ����������� ���� ��� ��������� ������:<br>
        ���: <a href=# onClick="javascript:tag('[code]', '[/code]'); return false;">[code][/code]</a><br>
        ������: <a href=# onClick="javascript:tag('[b]', '[/b]'); return false;" >[b][/b]</a><br>
        ���������: <a href=# onClick="javascript:tag('[i]', '[/i]'); return false;">[i][/i]</a><br>
        URL: <a href=# onClick="javascript:tag('[url]', '[/url]'); return false;" >[url][/url]</a><br>                
       </div>
    </td></tr>
  </table>    
  <table border="0" width="100%">
  <tr><td><p class="fieldname">����:</td><td><input class=input type=text name=theme size=74 maxlength=150 value='<?php echo htmlspecialchars($theme, ENT_QUOTES); ?>'></td></tr>
  <?php
     include "../utils/include.add_message.php";
  ?>
  </table>
  </form>
  <?php
    // ������� ���������� ��������
    include "../utils/bottomforumaction.php";
    include "../utils/forum.js";
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