<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) �������� �.�., �������� �.�.
  // PHP. �������� �������� Web-������
  // IT-������ SoftTime 
  // http://www.softtime.ru   - ������ �� Web-����������������
  // http://www.softtime.biz  - ������������ ������
  // http://www.softtime.mobi - ��������� �������
  // http://www.softtime.org  - �������������� �������
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ������������ ���������
  require_once("../utils/utils.pager.php");
  // ���������� SQL-�������
  require_once("utils.query_result.php");
  // ���������� ������� ��� ������ �� ��������
  require_once("../../utils/utils.time.php");
  // ��������� ������
  require_once("../../utils/utils.settings.php");
  // ��������� ��� 
  require_once("../../utils/utils.posts.php");

  try
  {
    // ��������� ���������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    if(empty($id_forum)) $id_forum = 1;

    // ��������� ��������� ������
    $query = "SELECT name FROM $tbl_forums
              WHERE id_forum = $id_forum";
    $name = query_result($query);

    $title = '������������� ������ '.$name;  
    $pageinfo = '<p class=help>�� ������ �������� ����� 
    ������, ����������, �������, ��������������� ���� 
    ��� ��������� ���������</p>';

    // �������� ��������� ��������
    require_once("../utils/top.php");
    // ����
    require_once("forummenu.php");

    // ��������� ������ ��� �������� � ������� �������
    $query = "SELECT * FROM $tbl_forums 
              ORDER BY pos";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������� � �������� ������");
    }
    if(mysql_num_rows($frm))
    {
      ?>
      <table width=100% 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">
      <tr class="header" align="center" valign="middle">
      <?php
      while($forum = mysql_fetch_array($frm))
      {
        echo "<td><a href=themes.php?id_forum=$forum[id_forum]>$forum[name]</a></td>";
      }
      echo "</tr></table><br><br>";
    }

    $settings = get_settings();
    // �������� ����� ��������� �� �������� ���
    // � ���������� $pnumber
    $pnumber = $settings['number_themes'];
    // ��������� ��������� �� ������ �������
    $page = intval($_GET['page']);
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;

    $query = "SELECT * FROM $tbl_themes 
              WHERE id_forum = $id_forum 
              ORDER BY `time` DESC
              LIMIT $begin, $pnumber";
    $thm = mysql_query($query);
    if(!$thm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������� � ����� ������");
    }
    if(mysql_num_rows($thm))
    {
    // ������ ������� � ������
    ?>
    <table class="table" 
           width="100%" 
           border="0" 
           cellpadding="0" 
           cellspacing="0">
    <tr class="header">
      <td class=headtable align=center>C��������</td>
      <td class=headtable align=center>�������� ����</td>
      <td width=70 class=headtable align=center>�����</td>
      <td class=headtable align=center colspan=4>��������</td>
    </tr>
    <?php
      while($themes = mysql_fetch_array($thm))
      {
        /////////////////////////////////////////////////////
        // ���� ������ ������ ���
        /////////////////////////////////////////////////////
        // ������� ���������� ��������� � ������ ����.
        $query = "SELECT COUNT(*) FROM $tbl_posts
                  WHERE id_theme = $themes[id_theme]";
        $posts = query_result($query);
        // �������������� ������������ ������� ������ � ������
        $name = theme_work_up($themes['name']);
        // ���������� ��������� � ����
        echo "<tr><td align=center width=50>$posts</td>";
        // ��������
        echo "<td><a href=posts.php?id_forum=$id_forum&id_theme=$themes[id_theme]&page=$page>$name</a></td>"; 
        // �����
        $author = htmlspecialchars($themes['author']);
        if($themes['id_author'] != 0)
        {
          echo "<td><a href='author.php?id_forum=$id_forum&id_author=$themes[id_author]'>$author</a></td>";
        }
        else echo "<td>$author</td>"; 
        // ������������� ����������� ��������� �������� ����
        $edit_theme = "<a href=thmedit.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>�������������</a>";
        echo "<td width=100 align=center>$edit_theme</td>";
        // ������������� ����������� ������, ���������� ��� ������� ����
        $show_theme = "<a href=thmshow.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>��������</a>";
        $hide_theme = "<a href=thmhide.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>������</a>";
        $lock_theme = "<a href=thmlock.php?id_theme=$themes[id_theme]&id_forum=$id_forum&page=$page>�������</a>";
        // ��������� ������ ����
        if($themes['hide'] == 'show') $show = "class=header";
        else $show = "";
        if($themes['hide'] == 'hide') $hide = "class=header";
        else $hide = "";
        if($themes['hide'] == 'lock') $lock = "class=header";
        else $lock = "";
        echo "<td $show width=100 align=center 
                  title='������� ���� ��������� ��� ���������'>$show_theme</td>"; // ���� ��������
        echo "<td $hide width=100 align=center 
                  title='������� ���� ����������� ��� ���������'>$hide_theme</td>"; // ���� ������
        echo "<td $lock width=100 align=center 
                  title='��������� ���������� ����� ���������'>$lock_theme</td></tr>"; // ���� �������
      }
      $page_link = 4;
      // ����������� ���������� �� ���������� ���� ���
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE id_forum = $id_forum";
      $total = query_result($query);
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber)-$number != 0) $number++;

      echo "<tr><td class=bottomtablen colspan=7>";
      // ������� ������ �� ������ ��������
      pager($page, 
            $total, 
            $pnumber, 
            3, 
            "");
      echo "</td></tr>";
      echo "</table>";
    }

    // ������� ���������� ��������
    require_once("../utils/bottom.php");
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }
?>