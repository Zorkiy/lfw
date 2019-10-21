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
  // ��������� ������
  require_once("../utils/utils.settings.php");

  try
  {
    // ��������� ��������� ������
    $settings = get_settings();

    // ��������� ��������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $page     = intval($_GET['page']);
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

    // ������� �������� ����
    $query = "SELECT * FROM $tbl_themes WHERE id_theme = $id_theme";
    $thm = mysql_query($query);
    if (!$thm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� � ����");
    }
    if(mysql_num_rows($thm))
    {
      $themes = mysql_fetch_array($thm);
      $theme = theme_work_up($themes['name']);
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
  
    // ��������������
    if(isset($_COOKIE['current_author']))
    {
      $current_author = $_COOKIE['current_author'];
      $wrdp = $_COOKIE['wrdp'];
      if (!get_magic_quotes_gpc())
      {
        $current_author = mysql_escape_string($current_author);
        $wrdp = mysql_escape_string($wrdp);
      }
      // ���� �������� ������ ���������, ���������,
      // �� �������� �� ���
      if($settings['show_personally'] == 'yes')
      {
        // ���� ������ ��������� �������� - ���������
        // ������� �� ��� ������� ���������� ����� ���������
        // �������������� �������� �����������
        $query = "SELECT * FROM $tbl_authors 
                  WHERE name = '$current_author' AND
                        passw = ".get_password($wrdp)." AND
                        statususer != 'wait'";
        $ath = mysql_query($query);
        if(!$ath)
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "������ ��������������");
        }
        // ���� ������� ������, �������������, ���������� ���������������
        // � ���������� ������� ������
        if(mysql_num_rows($ath))
        {
          $auth = mysql_fetch_array($ath);
          // ��������� ����� �� ����� ������� ����� ������ ������ ����
          $query = "SELECT * FROM $tbl_personally 
                    WHERE id_theme = $id_theme AND
                         (id_first = $auth[id_author] OR id_second = $auth[id_author])";
          $aut = mysql_query($query);
          if(!$aut)
          {
             throw new ExceptionMySQL(mysql_error(), 
                                      $query,
                                     "������ ��������������");
          }
          if(mysql_num_rows($aut)) define("AUTHOR", 1);
        }
      }
    }
    if(defined("AUTHOR"))
    {
    ?>
    <table class=readmenu border="0" width="100%" cellpadding="4" cellspacing="0" >
    <tr>
    <td class="headertable" width="70%" valign="middle">
      <div class=nametemaread>
      <em style="font-size: 11px">����: </em><?php echo $theme; ?>
      </div>  
      <div class=nextback>
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
    // ���������� ������� ��� ���������� ���������
/*    @putpost($posts['id_post'],
             $id_theme,
             2,
             $lasttime,
             $current_author,
             $id_forum,
             $lineforum,
             $lineforumdown,
             $skin,
             $themes['hide'],
             "posts",
             "themes");*/
    }
    else
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "� ��� ��� ���� �� �������� ���� ����");
    }
    echo "</table>";
    // ������� ���������� ��������
    include "../utils/bottomforum.php";
    // �������� �������� �� ������� � ���������� $buffer
    $buffer = ob_get_contents();  
    // ������� ������
    ob_end_clean();
    // ���������� �������� �������
    echo $buffer;
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