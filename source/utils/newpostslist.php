<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // ������� ��� ������ � �������
  require_once("../utils/utils.archive.php");

  // ����������� ������� ������, ������� ������������ � �������
  $query = "SELECT * FROM $tbl_forums
            WHERE hide = 'show' 
            ORDER BY pos";
  $frm = mysql_query($query);
  if(!$frm)
  {
     throw new ExceptionMySQL(mysql_error(), 
                              $query,
                             "������ ��� ������� 
                              �������� ������");
  }
  if(mysql_num_rows($frm))
  {
    // ������� �������
    echo "<table class=newposttable border=0 cellspacing=0 align=center cellpadding=0 width=100% >";
    $names = "<tr align='center' valign='middle'><td class=numberheader width=130px>�����:&nbsp;</td>";
    $numbers = "<tr align='center' valign='middle'><td class=numberheader><nobr>����� ���������:&nbsp;</nobr></td>";
    while($forums = mysql_fetch_array($frm))
    {
      // ��������� ����� ���������� ��������� ������
      $forum_lasttime = get_last_time($current_author, $forums['id_forum']);

      // ���� ������ �������� ���, ����������� ���� �� ����� � �����
      // �����, � ����������� �� ����� $id_theme
      if(basename($_SERVER['PHP_SELF']) == 'read.php')
      {
        // ��������� ��������� ���� ����, ������� ��������� � 
        // �������� �������
        if($arh) $id_theme_archive = get_archiv_id();
        if($id_theme > $id_theme_archive)
        {
          $page_name = "index.php"; // ����� �����
        }
        else
        {
          $page_name = "archive.php"; // �����
        }
      }
      else
      {
        // ���� ������ ������ ��� � "����� ������" ��� ������
        // ��������� �������� �� $_SERVER['PHP_SELF']
        $page_name = $_SERVER['PHP_SELF'];
      }
      $names .= "<td class=numberforum><a class=anumberforum 
      href='$page_name?id_forum=$forums[id_forum]'><nobr>$forums[name]</nobr></a></td>";

      $query = "SELECT count($tbl_posts.id_post)
                FROM $tbl_posts, $tbl_themes
                WHERE $tbl_posts.id_theme = $tbl_themes.id_theme AND
                      $tbl_themes.id_forum = $forums[id_forum] AND 
                      '$forum_lasttime' < $tbl_posts.time AND
                      $tbl_themes.hide != 'hide' AND 
                      $tbl_posts.hide != 'hide'";
      $tot = mysql_query($query);
      if(!$tot)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                 "������ ��� ��������
                                  ���������� ����� ���������");
      }
      if(mysql_num_rows($tot)) $count = mysql_result($tot,0);
      else $count = 0;
      $numbers .= "<td align=center class=numberforum>$count</td>";
    }
    $names .= "</tr>";
    $numbers .= "</tr>";
    echo $names;
    echo $numbers;
    echo "</table>";
  }
?>