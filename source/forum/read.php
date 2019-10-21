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
  Error_Reporting(E_ALL & ~E_NOTICE); 
  // �������� �� � ������
  ob_start();
  // ���������� SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ������� ��� ������ �� ��������
  require_once("../utils/utils.time.php");
  // ������� ��� ������ � �����������
  require_once("../utils/utils.posts.php");
  // ������� ��� ������ � �������
  require_once("../utils/utils.files.php");

  try
  {
    // ��������� ��������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    if(empty($id_forum)) $id_forum = 1;
    $id_theme = intval($_GET['id_theme']);
    // ��������� ��� ���������� �� cookie
    $current_author = $_COOKIE['current_author'];
    // ��������� ��� ������ �� cookie
    $lineforum = $_COOKIE['lineforum'];
    $lineforumdown = $_COOKIE['lineforumdown'];

    // ���������� �� SQL-��������
    if (!get_magic_quotes_gpc())
    {
      $current_author = mysql_escape_string($current_author);
      $lineforum = mysql_escape_string($lineforum);
      $lineforumdown = mysql_escape_string($lineforumdown);
    }

    // ��������� ��������� ���� ����, ������� ��������� �
    // �������� �������
    $query = "SELECT id_theme FROM $tbl_archive_number 
              LIMIT 1";
    $arh = mysql_query($query);
    if(!$arh)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ���������� ��������
                                ���");
    }
    if(mysql_num_rows($arh)) $id_theme_archive = mysql_result($arh, 0);
    else $id_theme_archive = 0;
    // ��� ����, ������� ����� ��������� ���� ���� $id_theme_archive
    // ��������� � ������, ���, ��� ���� - � "����� ������"

    if($id_theme <= $id_theme_archive)
    {
      // ���������� ���������� � ������
      $tbl_themes = $tbl_archive_themes;
      $tbl_posts  = $tbl_archive_posts;
    }

    // ������� �������� ����
    $theme_prev = "";
    $theme_next = "";
    $query = "SELECT * FROM $tbl_themes 
              WHERE id_theme = $id_theme AND 
                    hide !='hide'";
    $thm = mysql_query($query);
    if(!$thm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� � ����");
    }
    if(mysql_num_rows($thm))
    {
      $themes = mysql_fetch_array($thm);
      // ������������ ������ �� ��������� ���������� ����
      $query = "SELECT * FROM $tbl_themes
                WHERE `time` > '$themes[time]' AND
                      id_forum = $id_forum AND
                      hide != 'hide'
                ORDER BY `time`
                LIMIT 1";
      $thmprv = mysql_query($query);
      if(!$thmprv)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ���������� 
                                 ���������� ����");
      }
      if(mysql_num_rows($thmprv))
      {
        $theme_prev = mysql_fetch_array($thmprv);
        $theme_prev['name'] = theme_work_up($theme_prev['name']);
      }
  
      // ������������ ������ �� ��������� ��������� ����
      $query = "SELECT * FROM $tbl_themes
                WHERE `time` < '$themes[time]' AND
                      id_forum = $id_forum AND
                      hide != 'hide'
                ORDER BY `time` DESC
                LIMIT 1";
      $thmnxt = mysql_query($query);
      if(!$thmnxt)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ���������� 
                                 ��������� ����");
      }
      if(mysql_num_rows($thmnxt))
      {
        $theme_next = mysql_fetch_array($thmnxt);
        $theme_next['name'] = theme_work_up($theme_next['name']);
      }
  
      // �������������� ������������ ������ � ������ � ��������� ���
      // ������������ ���� [b],[/b],[i] � [/i]
      $theme = theme_work_up($themes['name']);
    }
    else
    {
      // ����� ���� - ���������������� ���������� �� 
      // �������� � �����������
      $nameaction = "���� �����������";
      // ������� "�����" ��������
      include "../utils/topforumaction.php";
      echo "<p class=linkbackbig><a href=# onClick='history.back()'>���������</a></p>";
      echo "<div class=fieldname style='color:red'>���� ����������� ��� ������� ��� ���������.</div><br>";
      // ������� ���������� ��������
      include "../utils/bottomforumaction.php";
      exit();
    }

    // ���������� ���� ������������ ����� "��������" � "�����������" ��������
    $show_switch = true;
    // ���������� ������ "������ ���"
    $showlisttopics = true;
    // ����� ������� ����� ���������
    $showforumsline = true;
    $readforumline = true;
    $title = strip_tags($theme);
    // ������� "�����" ��������
    require_once("../utils/topforum.php");
   ?>
   <table class=readmenu border="0" width="100%" cellpadding="4" cellspacing="0" >
   <tr>
    <td class="headertable" width="70%" valign="middle">
      <div class=nametemaread>
      <em style="font-size: 11px">����: </em><?php echo $theme; ?>
      </div>
      <div class=nextback>
      <?php
        if (isset($theme_prev['name']))
        {
          echo "<p class=linkback><a class='linkback' 
          href=read.php?id_forum=$id_forum&".
          "id_theme=$theme_prev[id_theme]>$theme_prev[name]</a></p>";
        }
        if (isset($theme_next['name']))
        {
          echo "<p class=linknext><a class='linknext' 
          href=read.php?id_forum=$id_forum&".
          "id_theme=$theme_next[id_theme]>$theme_next[name]</a></p>";
        }
      ?>
     </div>
     </td>
  </tr>
  </table>
  <table class=fonposts width="100%" border="0" cellspacing="1" cellpadding="0">
  <?php
    // �������� ��� ��������� ������� ����
    $query = "SELECT * FROM $tbl_posts 
              WHERE id_theme = $id_theme AND 
                    hide != 'hide' 
              ORDER BY time";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ������� ��������� ����");
    }
    if(mysql_num_rows($pst))
    {
      unset($post_arr);
      while($posts = mysql_fetch_array($pst))
      {
        $post_arr[$posts['id_post']]['name']        = $posts['name'];
        $post_arr[$posts['id_post']]['url']         = $posts['url'];
        $post_arr[$posts['id_post']]['putfile']     = $posts['putfile'];
        $post_arr[$posts['id_post']]['author']      = $posts['author'];
        $post_arr[$posts['id_post']]['id_author']   = $posts['id_author'];
        $post_arr[$posts['id_post']]['hide']        = $posts['hide'];
        $post_arr[$posts['id_post']]['time']        = $posts['time'];
        $post_arr[$posts['id_post']]['parent_post'] = $posts['parent_post'];

        $post_par[$posts['parent_post']][]= $posts['id_post'];
      }
    }
    // ��������� ����� ���������� ��������� ������
    $forum_lasttime = get_last_time($current_author, $id_forum);
    // ������� ��������� ����
    putpost_arr(0, 
                $id_theme, 
                $post_arr, 
                $post_par,
                2,
                $forum_lasttime,
                $current_author,
                $id_forum,
                $lineforum,
                $lineforumdown,
                $skin,
                $themes['hide']);

    echo "</table>";
    // ������� ���������� ��������
    include "../utils/bottomforum.php";
    // �������� �������� �� ������� � ���������� $buffer
    $buffer = ob_get_contents();
    // ������� ������
    ob_end_clean();
    // ���������� �������� �������
    echo $buffer;

    mysql_close();
    @include "../counter/count.php";
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