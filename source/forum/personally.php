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
  // ���������� ������������ ���������
  require_once("../utils/utils.pager.php");
  // ��������� ������
  require_once("../utils/utils.settings.php");

  try
  {
    // ��������� ��������� ������
    $settings = get_settings();

    // ��������� ��������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $id_author = 0;
    $page = intval($_GET['page']);

    // ��������� �������� $id_forum �� ���������� ��������
    if(!isset($id_forum)) $id_forum = 1;
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

    // ��������������
    if(empty($_COOKIE['current_author']))
    {
      header("Location: index.php?id_forum=$id_forum");
    }
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
      if(mysql_num_rows($ath)>0)
      {
        define("AUTHOR", 1);
        $auth = mysql_fetch_array($ath);
        $id_author = $auth['id_author'];
      }
    }

    if(defined("AUTHOR"))
    {
      // ���������� ���� �� $pnumber ����
      $pnumber = $settings['number_themes'];
      if(empty($pnumber)) $pnumber = 30;
      // ���� � ������ ������� �� �������� ��������
      // ������� ������ ��������
      if(empty($page)) $page = 1;
      $begin = ($page - 1)*$pnumber;
      // ����������� ���������� �� $pnumber �����
      $query = "SELECT $tbl_themes.id_theme AS id_theme,
                       $tbl_themes.time AS time,
                       $tbl_themes.name AS name,
                       $tbl_themes.author AS author,
                       $tbl_themes.id_author AS id_author,
                       $tbl_themes.last_author AS last_author,
                       $tbl_themes.id_last_author AS id_last_author
              FROM $tbl_personally, $tbl_themes
              WHERE ($tbl_personally.id_first = $id_author OR
                    $tbl_personally.id_second = $id_author) AND
                    $tbl_themes.id_theme = $tbl_personally.id_theme
              ORDER BY `time` DESC
              LIMIT $begin, $pnumber";
      $thm = mysql_query($query);
      if(!$thm)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� ������� ��� ������");
      }
      // ������ ������� � ������
      ?>
      <table border=0 class=temamenu cellspacing="1" cellpadding="0" width=100% >
        <tr class="headertable" align="center">
          <td class="headertable" width=30px><p class=fieldnameindex>&nbsp;</p></td>
          <td class="headertable"><p class=fieldnameindex>�������� ����</p></td>
          <td class="headertable"><p class=fieldnameindex>�����</p></td>
          <td colspan=2 width=25% class="headertable" ><p class=fieldnameindex>��������� ��������� � �����</p></td>
         </tr>
      <?php
      while($themes = mysql_fetch_array($thm))
      {
        ///////////////////////////////////////////////////////////
        // ���� ������ ����� ��������� � ����
        ///////////////////////////////////////////////////////////
        // ������������ ���������� ��������� � ������� ����,
        // ��������� �������� � ���������� $posts_in_topic
        $query = "SELECT COUNT(*) FROM $tbl_posts
                  WHERE id_theme = $themes[id_theme] AND 
                        hide != 'hide'";
        $pst = mysql_query($query);
        if(!$pst)
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "������ ��� �������� ���������� ��������� ����");
        }
        if(mysql_num_rows($pst)) $posts_in_topic = mysql_result($pst, 0);
  
        // ������������ ���������� ����� ��������� � �������
        // ����, ��������� �������� � ���������� $new_posts_in_topics
        $query = "SELECT COUNT(*) FROM $tbl_posts
                  WHERE id_theme='$themes[id_theme]' AND
                        '$lasttime'<time AND
                        hide != 'hide'";
        $tim = mysql_query($query);
        if(!$tim)
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "������ ��� �������� ���������� ���������");
        }
        if(mysql_num_rows($tim)) $new_posts_in_topics = mysql_result($tim, 0);
        else $new_posts_in_topics = 0;
          
        // ��������� �������� ���������� ���� � ������ � ������ ����� �
        // ����� ������ ��������� � ����
        if($new_posts_in_topics != 0)
        {
          // ���� � ������� ������� ����� ��������� �������� ��
          // � �������
          $theme_count = "$posts_in_topic($new_posts_in_topics) ";
          $theme_style = "class=namenewtema";
        }        
        else
        {
          // ���� ����� ��������� ���, ������ �������� ����� �����
          // ��������� � ����
          $theme_count = $posts_in_topic;
          $theme_style = "class=nametema";             
        }    
        echo "<tr class=trtema><td class=trtemaheight align=center><p $theme_style><nobr>$theme_count</nobr></p></td>";
        ///////////////////////////////////////////////////////////
        // ���� ������ �������� ����
        ///////////////////////////////////////////////////////////
        // �������������� ������������ ������� ������ � ������
        // ������������ ���� [b],[/b],[i] � [/i]
        $name = theme_work_up($themes['name']);
        if(isset($page)) $strpage = "&page=".$page;
        // ���� ���� ������� ������� ��������������
        $closetheme = "";
        $closetitle = "";
        if($themes['hide'] == 'lock')
        {
          if($posts_in_topic>1) $closetheme = "(���� �������)";
          else $closetheme = "(���� ����������)";
          $closetitle = "���� ������� ��� ����������";
        }
        echo "<td>
                <p $theme_style><a $theme_style title='$closetitle' href=personallyread.php?id_forum=$id_forum&id_theme=$themes[id_theme]{$strpage}>$name $closetheme</a></p>
              </td>"; 
        ///////////////////////////////////////////////////////////
        // ���� ������ ������ ����
        ///////////////////////////////////////////////////////////
        $author = htmlspecialchars($themes['author']);
        if($themes['id_author'] != 0)
          echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_author]>$author</a></td>";
        else
          echo "<td><p class=author>$author</td>";
        ///////////////////////////////////////////////////////////
        // ���� ������ ���������� ���������� ����
        ///////////////////////////////////////////////////////////
        $themes['last_author'] = htmlspecialchars($themes['last_author']);
        echo "<td ><p class=tddate><nobr>".convertdate($themes['time'], 0)."</nobr></p></td>";
        // ��������� ������ �� ���������� ������ � ����
        if($themes['id_last_author'] != 0)
        {
          $last_author = "<p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_last_author]><nobr>$themes[last_author]</nobr></a></p>";
        }   
        else
        {
           $last_author = "<p class=author><nobr>$themes[last_author]</nobr></p>";
        }  
        echo "<td>$last_author</td></tr>";      
        // ����� ������� �� ������ ��� ������
      }
      ///////////////////////////////////////////////////////////
      // ���� ������ ������ �� ������ ���� ������
      ///////////////////////////////////////////////////////////
      $page_link = 4;
      // ����������� ���������� � ���������� ���� ���
      $query = "SELECT COUNT($tbl_themes.id_theme)
                FROM $tbl_personally, $tbl_themes
                WHERE ($tbl_personally.id_first = $id_author OR
                       $tbl_personally.id_second = $id_author) AND
                       $tbl_themes.id_theme = $tbl_personally.id_theme";
      $tot = mysql_query($query);
      if(!$tot)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� �������� ���������� ���������");
      }
      if(mysql_num_rows($tot)) $total = mysql_result($tot,0);
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber) - $number != 0) $number++;

      // ������� ������������ ���������
      echo "<tr><td class=bottomtabletema colspan=5><div class=leftblock><p class=texthelp>���������: ";
      pager($page, $total, $pnumber, $page_link, "&id_forum=$id_forum");
      echo "&nbsp;<a title='����� ������' class=menuinfo href=archive.php?id_forum=$id_forum> <nobr>[�����]</nobr></a>&nbsp;";
      echo "</div><div align=right class=linksofttime>����� ���������� <nobr>IT-������� <a class=linksofttime href='http://www.softtime.ru'>SoftTime</a></nobr></div></td></tr>";
      // ����� ������ ������ �� ������ ���� ������
      echo "</table>";
    }
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