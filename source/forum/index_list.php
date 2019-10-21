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

  try
  {
    // ��������� �������� $id_forum �� ���������� ��������
    if(empty($_GET['id_forum'])) $_GET['id_forum'] = 1;

    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);


    $query = "SELECT MIN(id_forum) AS min,
                     MAX(id_forum) AS max
              FROM $tbl_forums
              WHERE hide != 'hide'";
    $frm = mysql_query($query);
    if(!$frm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� 
                                � ������� �������");
    }
    if(mysql_num_rows($frm))
    {
      $minmaxfrm = mysql_fetch_array($frm);
      if($id_forum < $formum['min']) $id_forum = $minmaxfrm['min'];
      if($id_forum > $formum['max']) $id_forum = $minmaxfrm['max'];
    }

    // ����� ������� ����� ���������
    $showforumsline = true;
    // �������� "�����" ��������
    require_once("../utils/topforum.php");

    echo '<br><table border=0 
                     class=temamenu 
                     cellspacing="1" 
                     cellpadding="0" 
                     width=100%>';

    // ������� ������ �������
    $query = "SELECT * FROM $tbl_forums WHERE hide='show'
              ORDER BY pos";
    $frm = mysql_query($query);
    if(!$frm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� 
                                � ������� �������");
    }
    if(mysql_num_rows($frm))
    {
      while($forums = mysql_fetch_array($frm))
      {
        // ������� �������� ������
        echo "<tr valign=top class=trtema>
                <td class=image>&nbsp;</td>
                <td style='padding-left: 10px'>
                  <table><tr>
                    <td class=nameforum><a href='index.php?id_forum=$forums[id_forum]'><nobr>$forums[name]</nobr></a></td>
                    <td class=menuinfo style='padding-left: 10px'>$forums[logo]</td>
                  </tr></table>";
  
        // ��������� ��������� ��� ���� ������
        $query = "SELECT * FROM $tbl_themes
              WHERE id_forum = $forums[id_forum] AND
                    hide != 'hide'
              ORDER BY time DESC
              LIMIT 3";
        $thm = mysql_query($query);
        if(!$thm)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                   "������ ��� ������� 
                                    ��������� ���������
                                    ������");
        }
        if(mysql_num_rows($thm))
        {
          echo "<div class=bodydiv>
                <table border=0 
                       width=100% 
                       cellspacing=1 
                       cellpadding=0 
                       class=temamenu>
                <tr align=center class=\"headertable\">
                  <td width=100px class=\"headertable\">&nbsp;</td>
                  <td class=\"headertable\"><p class=fieldnameindex>��������� ����</td>
                  <td width=210 class=\"headertable\"><p class=fieldnameindex>��������� ���������</td>";
          // ��������� ����� ���������� ��������� ������
          $forum_lasttime = get_last_time($current_author, $forums['id_forum']);
          while($themes = mysql_fetch_array($thm))
          {
            // ������������ ���������� ��������� � ������� ����
            $posts_in_topic = get_number_posts($themes['id_theme']);
            // ������������ ���������� ����� ��������� � �������
            $new_posts_in_topics = get_number_posts($themes['id_theme'], $forum_lasttime);
  
            if($new_posts_in_topics != 0)
            {
              // ���� � ������� ������� ����� ��������� �������� ��
              // � �������
              $theme_count = "<b>$posts_in_topic ($new_posts_in_topics)</b> ";
              $theme_style = "class=namenewtema";
            }
            else
            {
              // ���� ����� ��������� ���, ������ �������� ����� �����
              // ��������� � ����
              $theme_count = $posts_in_topic;
              $theme_style = "class=nametema";
            }
  
            $last_author = htmlspecialchars($themes['last_author']);
            $time = convertdate($themes['time'], 0);
            $themes['name'] = theme_work_up($themes['name']);
  
            echo "<tr class=trtema>
            <td widht=100px class=trtemaheight align=center><p class=nametema>$theme_count</p></td>
            <td $theme_style><p class=nametema><a href='read.php?id_forum=$forums[id_forum]&id_theme=$themes[id_theme]'>$themes[name]</a></p></td>
            <td width=210 class=tddate><p class=nametema>$time �� $last_author</p></td>
            </tr>";
          }
          echo "</table></div>";
        }
        echo "</td></tr>";
      } 
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