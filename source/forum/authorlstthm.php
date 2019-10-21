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
  // ���������� ������������ ���������
  require_once("../utils/utils.pager.php");

  try
  {
    // ����� �������� ��������
    $nameaction = "������ ��������� ���";
    // ������� "�����" ��������
    require_once("../utils/topforumaction.php");
    echo "<p class=linkbackbig><a href=index.php?id_forum=$_GET[id_forum]>���������</a></p>";
    // ���������� ������� �� $pnumber ����
    $page = intval($_GET['page']);
    $id_forum = intval($_GET['id_forum']);
    $_GET['id_author'] = intval($_GET['id_author']);
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    $query = "SELECT id_theme FROM $tbl_posts
              WHERE id_author = $_GET[id_author] AND hide = 'show'
              GROUP BY id_theme
              ORDER BY `time` DESC
              LIMIT 30";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� � ����������");
    }
    // ���� ������� ���� �� ���� ���� - ������� ������
    if(mysql_num_rows($pst))
    {
      // ������ ������� � ������
      ?>
       <p class="zagtext">����������:</p>
       <table class=srchtable border="0" width="100%" cellpadding="4" cellspacing="1" >
          <tr class="tableheadern" align="center">
            <td class="tableheadern"><p class=fieldnameindex><nobr>���-��</nobr> �����.</p></td>
            <td class="tableheadern"><p class=fieldnameindex>�������� ����</p></td>
            <td class="tableheadern"><p class=fieldnameindex>�����</p></td>
            <td class="tableheadern"><p class=fieldnameindex>��������� ���������</p></td>
          </tr>
      <?php
      while($id_theme = mysql_fetch_array($pst))
      {
        // ������� ���������� ��������� � ������ ����.
        $query = "SELECT COUNT(*) FROM $tbl_posts
                  WHERE id_theme = $id_theme[id_theme] AND 
                        hide != 'hide'";
        $cnt = mysql_query($query);
        if(!$cnt) 
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������� ���������� ��������� ����");
        }
        if(mysql_num_rows($cnt)) $theme_count = mysql_result($cnt,0);

        // ��������� ��������� ����
        $query = "SELECT * FROM $tbl_themes 
                  WHERE id_theme = $id_theme[id_theme] AND 
                        hide = 'show'";
        $thm = mysql_query($query);
        if(!$thm)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� ���������� ����");
        }
        if(mysql_num_rows($thm))
        {
          $themes = mysql_fetch_array($thm);
      
          // ���������� ��������� � ����
          echo "<tr class=trtablen><td class=trtemaheight align=center><p class=nametema><nobr>$theme_count</nobr></p></td>";
          // ������� ����
          // �������������� ������������ ������� ������ � ������
          // ������������ ���� [b],[/b],[i] � [/i]
          $namet = theme_work_up($themes['name']);
          // ������� ������ ���
          if(!empty($page)) $strpage = "&page=".$page;
          // ��������
          echo "<td><p><a target='_blank' href=read.php?id_forum={$themes[id_forum]}&id_theme={$themes[id_theme]}$strpage>$namet</a></td>";
          // �����
          if($themes['id_author'] != 0) echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$themes[id_forum]&id_author=$themes[id_author]>".htmlspecialchars($themes['author'])."</a></td>";
          else echo "<td><p class=author>".htmlspecialchars($themes['author'])."</td>";
          // ����� ���������� ���������� ����
          echo "<td><p class=texthelp>".convertdate($themes['time'])."</p></td></tr>";
        }
      }
      echo "</table>";
    }
    else
    {
      echo "<p class=result>������ ���������� �� ����������� �� ����� ����.</p>";
    }
    // ������� ���������� ��������
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