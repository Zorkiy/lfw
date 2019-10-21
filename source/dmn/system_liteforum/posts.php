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
  // ���������� SQL-�������
  require_once("utils.query_result.php");
  // ��������� ��� 
  require_once("../../utils/utils.posts.php");

  try
  {
    // ������������� SQL-��������
    $id_theme = intval($_GET['id_theme']);
    $id_forum = intval($_GET['id_forum']);

    // ������� �������� ����
    $query = "SELECT * FROM $tbl_themes 
              WHERE id_theme = $id_theme
              LIMIT 1";
    $thm = mysql_query($query);
    if(!$thm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������� � ����");
    }
    if(mysql_num_rows($thm)) $themes = mysql_fetch_array($thm);
    // �������������� ������������ ������ � ������ � �������� ����
    $theme = theme_work_up($themes['name']);

    $title = '������������� ����: '.$theme;
    $pageinfo = '';

    // �������� ��������� ��������
    require_once("../utils/top.php");
?>
<style type="text/css">
@charset "windows-1251";

body, table{font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
a{color: #19308C}
a:hover{color: #010103}
.text{font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #2D2D2D; line-height: 20px; text-align: justify}
.texthelp{color: #5B5B5B; margin: 0px}
.authorreg{font-size: 12px; padding-left: 10px; padding-right: 10px; color: #446343; font-weight: bold}
a.authorreg{font-size: 12px; padding-left: 0px; padding-right: 0px; color: #446343; font-weight: bold}
a.authorreg:hover{color: #182D0B;}
.author{font-size: 12px; padding-left: 10px; padding-right: 10px; color: #446343; font-style: normal}
.button{background-color: #D6E1E2; font-size: 11px; color: #264973; padding: 1px; padding-left: 10px; padding-right: 10px}
.codeblock{background-color: #E3E5E3; border-style: solid; border-width: 1px;
	border-color: #B8C1B7; padding: 10px; padding-left: 35px; 
	background-image: url(images/code2.gif); background-repeat: repeat-y; font-size: 12px}
@charset "windows-1251";

.readmenu{border-top-style: solid; border-width: 1px; border-color: #6B8699; border-left-style: solid}

div.nametemaread{float: left; margin: 0px}
.nametemaread{padding: 3px; padding-left: 20px; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; font-weight: bold; font-style: oblique}
.tablenametemaread{height: 70; padding: 10px; background-color: #FFFFFF}
div.nextback{float: right; padding-right: 20px}
.posttext{font-size: 12px; color: #000000; line-height: 16px}
.postbody{background-color: #F8F8F8}
.postbodynew{background-color: #FFFFFF}

p.linkback{text-align: left; font-size: 11px; margin: 0px; background-image: url(images/backpage.gif); background-repeat: no-repeat; 
 background-position: left;  margin-left: 20px; padding-left: 20px;}
a.linkback{color: #21353D} 
p.linknext{text-align: left; font-size: 11px; margin: 0px; background-image: url(images/nextpage.gif); background-repeat: no-repeat; 
 background-position: left; margin-left: 60px; padding-left: 20px; color: #E3D41B}
a.linknext{color: #21353D} 
.fonposts{background-color: #D1D1D1;}
.infopost{font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #969696}
.postmenu{font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #969696; text-align: right}

.codeblock{background-color: #E3E5E3; border-style: solid; border-width: 1px;
	border-color: #B8C1B7; padding: 10px; padding-left: 35px; 
	background-image: url(images/code2.gif); background-repeat: repeat-y; font-size: 12px}
.attachfile{float: right;}
.toauthor{font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #969696;}</style>
<?php
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

    ?>
    <table width="100%" border="0" bgcolor="silver" cellspacing="1" cellpadding="0" style='background-color: silver'>
    <tr>
    <?php
    // �������� ������ ��������� ����
    $query = "SELECT * FROM $tbl_posts 
              WHERE id_theme = $id_theme
              ORDER BY parent_post
              LIMIT 1";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������� � ����������");
    }
    if(mysql_num_rows($pst))
    {
      $posts = mysql_fetch_array($pst);
      // ���������� ������� ��� ���������� ���������
      put_post_admin($posts['id_post'],
              $id_theme,
              5,
              $id_forum,
              "../../skins/base/",
              "../../forum/");
            
    }
    echo "</table>";
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

  // ����������� ������� ������ ���������
  // $id_post - ����������������� ����� ���������
  // $id_theme - ����������������� ����� ����
  // $indent - ������� ������� ������ ����� ������ � 0,
  // $id_forum - ������� �����
  // $skin - ���� � �����
  // $forum - ���� � ������
  // ��� ������������ ������ ������� ������������� ��������� 
  // �������� ����� ���������
  function put_post_admin($id_post,
                          $id_theme,
                          $indent,
                          $id_forum,
                          $skin,
                          $forum)
  {
    // ��������� �������� ������ ����������
    global $tbl_posts;
    // ������� ��������� � id_post == $id_post
    $query = "SELECT * FROM $tbl_posts 
              WHERE id_post = $id_post";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ������� ��������� ����");
    }
    if(mysql_num_rows($pst))
    {
      $posts = mysql_fetch_array($pst);
      post_down_admin($id_post,
                $id_theme,
                $indent,
                $id_forum,
                $posts['id_author'],
                $posts['author'],
                $posts['time'],
                $posts['putfile'],
                $posts['name'],
                $posts['url'],
                $skin,
                $posts['hide'],
                $forum);
      // ������� ���������� ���������
      $query = "SELECT * FROM $tbl_posts 
                WHERE parent_post = $id_post
                ORDER BY id_post";
      $pst = mysql_query($query);
      if(!$pst)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ������� ��������� ����");
      }
      $num_rows = mysql_num_rows($pst);
      if($num_rows)
      {
        while($posts = mysql_fetch_array($pst))
        {
          $shap_indent=5;   
          if ($num_rows*$shap_indent>350) $shap_indent = 3;
          // ��������� ������
          if($indent<70) $temp = ($shap_indent + $indent*(95)/100);
          else $temp = (5 + $indent*(100 - $indent)/100);
          // ���������� �������� ������� putpost ��� ��������� ���������� ������
          put_post_admin($posts['id_post'],
                  $id_theme,
                  $temp,
                  $id_forum,
                  $skin,
                  $forum);
        }
      }
    }
  }
  // ������� ������ ����� �� ��������
  function post_down_admin(
           $id_post,
           $id_theme,
           $indent,
           $id_forum,
           $id_author,
           $author,
           $time,
           $file,
           $name,
           $puturl,
           $skin,
           $posthide,
           $forum)
  {
    // ������� ��������� - ��� ������ ��������� - ���� �������
    ?>
    <tr><td>
    <table border="0" width="100%" class="postbody" cellpadding="0" cellspacing="0">
    <?
    // ������� ��������� ���������: ���, ����� �������� ���������
    // ���� id_author �� ����� 0 ������ ����� ��������������� - ����� ������ �� ���� ������
    if($id_author != 0)
      echo "<tr>
              <td width='".$indent."%'>&nbsp;</td>
              <td class=infopost>�����: <a class=authorreg href=info.php?id_forum=$id_forum&id_author=$id_author>".htmlspecialchars($author)."</a>&nbsp;&nbsp;&nbsp;(".$time.")</td>
              <td width=50>&nbsp;</td>
            </tr>";
    else
      echo "<tr>
              <td width='".$indent."%'>&nbsp;</td>
              <td class=infopost>�����: <em class=author>".htmlspecialchars($author)."</em>&nbsp;&nbsp;&nbsp;(".$time.")</td>
              <td width=50>&nbsp;</td>
            </tr>";
    // ������� ���� ���������
    // ���� ���� ������������ ����(�������) ������� ������
    $writefile = "";
    if($file != "" && $file != "-" && is_file("../$forum/".$file))
    {
      // ���� ���� �� ������� ����� ����� �� ���� ������
      if(filesize("../$forum/".$file)) $writefile = "<a href=../$forum/".$file."><img border=0 src={$skin}images/flopy.gif></a>";
      // ����� ���������� ���
      else unlink($file);
    }

    // ������������ ����� �����
    $postbody = post_work_up($name);
    // ��������, ������ ���� � �������� ������
    // ��������������� �����
    $show = "����������";
    $hide = "������";
    $lock = "�������";
    // ���������� $posthide, �� ����� ���� ����� ���������
    // ��� �������� - show, hide � lock
    $$posthide = "<b>".$$posthide."</b>";
    // ��������� ������ ��� ������ ���������
    $edit = "<td class=postmenu>
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=pstshow.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post
                  title='������� ��������� ��������� ��� �����������'>$show</a>
               &nbsp;&nbsp;&nbsp;
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=psthide.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post
                  title='������� ��������� ����������� ��� �����������'>$hide</a>
               &nbsp;&nbsp;&nbsp;
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=pstlock.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post
                  title='��������� ����� �� ������ ���������'>$lock</a>
               &nbsp;&nbsp;&nbsp;
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=pstedit.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post>�������</a>
             </td>";
    // ������� ���� ���������
    echo "<tr valign=top>
            <td width='$indent%'>&nbsp;</td>
            <td><p class=posttext>$postbody".$url."</p></td>
            <td align=center>$writefile</td></tr>";
    echo "<tr>
            <td width='$indent%'>&nbsp;</td>
            $edit
            <td>&nbsp;</td></tr>";
    echo "<tr>
            <td width='$indent%'>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td></tr>";
    echo "</table>";
    echo "</td></tr>";
  }
?>