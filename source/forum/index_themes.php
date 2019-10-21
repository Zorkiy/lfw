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

  try
  {
    // ��������� ��������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);

    // ����� ������� ����� ���������
    $showforumsline = true;
    // �������� "�����" ��������
    require_once("../utils/topforum.php");

    // ��������� �������� $id_forum �� ���������� ��������
    if(empty($id_forum)) $id_forum = 1;

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
      if($id_forum < $minmaxfrm['min']) $id_forum = $minmaxfrm['min'];
      if($id_forum > $minmaxfrm['max']) $id_forum = $minmaxfrm['max'];
    }

    // ���������� ���� �� $pnumber ����
    $page = intval($_GET['page']);
    $pnumber = $settings['number_themes'];
    if(empty($pnumber)) $pnumber = 30;
    // ���� � ������ ������� �� �������� ��������
    // ������� ������ ��������
    if(empty($page)) $page = 1;
    $begin = ($page - 1)*$pnumber;
    // ����������� ���������� �� $pnumber �����
    $query = "SELECT * FROM $tbl_themes 
              WHERE id_forum = $id_forum AND
                    hide != 'hide'
              ORDER BY time DESC
              LIMIT $begin, $pnumber";
    $thm = mysql_query($query);
    if(!$thm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ����������
                                ��� ������");
    }
    if(mysql_num_rows($thm))
    {
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
      // ��������� ����� ���������� ��������� ������
      $forum_lasttime = get_last_time($current_author, $id_forum);
      while($themes = mysql_fetch_array($thm))
      {
        // ������������ ���������� ��������� � ������� ����
        $posts_in_topic = get_number_posts($themes['id_theme']);
        // ������������ ���������� ����� ��������� � �������
        $new_posts_in_topics = get_number_posts($themes['id_theme'], $forum_lasttime);

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
          if($posts_in_topic > 1) $closetheme = "(���� �������)";
          else $closetheme = "(���� ����������)";
          $closetitle = "���� ������� ��� ����������";
        }
        echo "<td>
              <p $theme_style><a $theme_style 
                 title='$closetitle' 
                 href=read.php?id_forum=$id_forum&id_theme=$themes[id_theme]{$strpage}>$name $closetheme</a></p>
            </td>"; 
        ///////////////////////////////////////////////////////////
        // ���� ������ ������ ����
        ///////////////////////////////////////////////////////////
        $author = htmlspecialchars($themes['author']);
        if($themes['id_author'] != 0)
        {
          // ������������������ ������
          echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_author]>$author</a></td>";
        }   
        else
        {
          // �������������������� ������
          echo "<td><p class=author>$author</td>";
        }  
        ///////////////////////////////////////////////////////////
        // ���� ������ ���������� ���������� ����
        ///////////////////////////////////////////////////////////
        $themes['last_author'] = htmlspecialchars($themes['last_author'], ENT_QUOTES);
        echo "<td ><p class=tddate><nobr>".convertdate($themes['time'], 0)."</nobr></p></td>";
        // ��������� ������ �� ���������� ������ � ����
        if($themes['id_last_author'] != 0)
        {
          // ������������������ ������
          $last_author="<p class=authorreg><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$themes[id_last_author]><nobr>$themes[last_author]</nobr></a></p>";
        }   
        else
        {
          $last_author="<p class=author><nobr>".$themes['last_author']."</nobr></p>"; // �������������������� ������
        }  
        echo "<td>$last_author</td></tr>";      
        // ����� ������� �� ������ ��� ������
      }
      ///////////////////////////////////////////////////////////
      // ���� ������ ������ �� ������ ���� ������
      ///////////////////////////////////////////////////////////
      $page_link = 4;
      // ����������� ���������� � ���������� ���� ���
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE id_forum = $id_forum AND
                      hide != 'hide'";
      $tot = mysql_query($query);
      if(!$tot)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� ������� 
                                  ������ ����� ��� ������");
      }
      if(mysql_num_rows($tot)) $total = mysql_result($tot,0);
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
