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
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $id_post  = intval($_GET['id_post']);

    if(!empty($_POST))
    {
      // ���������� HTML-�����
      define("EDIT_POST", 1);
      $error = array();
      if($_REQUEST['delete_file'] == 'on') $_REQUEST['delete_file'] = "checked";
      else $_REQUEST['delete_file'] = "";
      require_once("../utils/include.edit_post.handler.php");
    }
    // ������������� �������� ��������
    $nameaction = "��������� ���������";
    // �������� "�����" ��������
    include "../utils/topforumaction.php";  

    // ���������� ����� ���������, ������� �� ������
    $query = "SELECT $tbl_posts.name AS name, 
                     $tbl_posts.url AS url,
                     $tbl_posts.putfile AS putfile 
              FROM $tbl_posts, $tbl_themes
              WHERE $tbl_themes.id_theme = $tbl_posts.id_theme AND
                    $tbl_themes.hide != 'hide' AND
                    $tbl_posts.hide != 'hide' AND 
                    $tbl_posts.id_post = $id_post";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ����������
                               ���������");
    }
    if(mysql_num_rows($pst))
    {
      $posts = mysql_fetch_array($pst);
    }
    if(empty($_POST))
    {
      $_REQUEST = $posts;
      $message = $posts['name'];
    }
    ?>
    <p class=linkbackbig><a href=read.php?id_forum=<?php echo $id_forum; ?>&id_theme=<?php echo $id_theme; ?>>��������� � ����</a></p>
    <?php
    // ���� ������� ������ ������� ��
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
    <form enctype='multipart/form-data' method=post name=form>
    <input type=hidden name=personally value='<?php echo htmlspecialchars($_GET['personally'], ENT_QUOTES); ?>'>
    <input type=hidden name=sid_add_theme value='<?php echo $sid_add_theme; ?>'>
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
    <td >
        <div class="blockremark">
        <p class=texthelp>
        <a href=# onClick="javascript:click_link()" href=#>����������</a><br><br>       
        ����������� ���� ��� ��������� ������:<br>
        ���: <a href=# onClick="javascript:tag('[code]', '[/code]')" href=#>[code][/code]</a><br>
        ������: <a href=# onClick="javascript:tag('[b]', '[/b]')" href=#>[b][/b]</a><br>
        ���������: <a href=# onClick="javascript:tag('[i]', '[/i]')" href=#>[i][/i]</a><br>
        URL: <a href=# onClick="javascript:tag('[url]', '[/url]')" href=#>[url][/url]</a><br>                
    </td></tr>
    </table>    
    <table>
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
