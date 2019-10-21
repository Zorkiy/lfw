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
    // ���������� �� SQL-��������
    $id_author = intval($_GET['id_author']);
    $id_forum  = intval($_GET['id_forum']);
    $page      = intval($_GET['page']);
    // ����� �������� ��������
    $nameaction = "������ ���";
    // ������� "�����" ��������
    require_once("../utils/topforumaction.php");
    echo "<p class=linkbackbig><a href=index.php?id_forum=$_GET[id_forum]>���������</a></p>";
    // ���������� ���� �� $pnumber ����
    $pnumber = 25;
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    // ��������� ����� ���������� ���, ������� ������� ������ �� ������
    if(!empty($_GET['arch'])) $tbl = $tbl_archive_themes;
    else $tbl = $tbl_themes;
    $query = "SELECT * FROM $tbl 
              WHERE id_author = $id_author AND hide != 'hide'
              ORDER BY time DESC
              LIMIT $begin, $pnumber";
    $thm = mysql_query($query);
    if(!$thm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� � ������� ���");
    }
    // ���� ������� ���� �� ���� ���� - ������� ������
    if(mysql_num_rows($thm))
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

      // ��������� ��������� ���� ����, ������� ��������� � 
      // �������� �������
      $query = "SELECT id_theme FROM $tbl_archive_number LIMIT 1";
      $arh = mysql_query($query);
      if(!$arh)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� ���������� ��������� �������� ����");
      }
      if(mysql_num_rows($arh)) $id_theme_archive = mysql_result($arh, 0);
      // ��� ����, ������� ����� ��������� ���� ���� $id_theme_archive
      // ��������� � ������, ���, ��� ���� - � "����� ������"

      while($themes = mysql_fetch_array($thm))
      {
        if($themes['id_theme'] > $id_theme_archive)
        {
          // ������������ ���������� ��������� ��� ����
          // � ����� ������
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
          $theme_count = mysql_result($pst,0);
        }
        else
        {
          // ��������� ���������� ��������� � ����
          // � �������� ������
          $theme_count = $themes['number'];
        }
      
        // ���������� ��������� � ����
        echo "<tr class=trtablen><td class=trtemaheight align=center><p class=nametema><nobr>$theme_count</nobr></p></td>";
        // ������� ����
        // �������������� ������������ ������� ������ � ������
        // ������������ ���� [b],[/b],[i] � [/i]
        $namet = theme_work_up($themes['name']);
        // ������� ������ ���
        if(!empty($page)) $strpage = "&page=".$page;
        // ��������
        echo "<td><p><a target='_blank' href=read.php?id_forum=$themes[id_forum]&id_theme=$themes[id_theme]{$strpage}>$namet</a></td>";
        // �����
        if($themes['id_author'] != 0) echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$themes[id_forum]&id_author=$themes[id_author]>".htmlspecialchars($themes['author'])."</a></td>";
        else echo "<td><p class=author>".htmlspecialchars($themes['author'])."</td>";
        // ����� ���������� ���������� ����
        echo "<td><p class=texthelp>".convertdate($themes['time'])."</p></td></tr>";
      }
      // ����� ������� �� ������ ��� ������
      // ������� ������ �� ������ ���� ������
      $page_link = 1;
    
      // ��������� ����� ����� ���, ������� ������� ������ �� ������
      $query = "SELECT COUNT(*) FROM $tbl 
                WHERE id_author = $id_author AND 
                      hide != 'hide'";
      $tot = mysql_query($query);
      if(!$tot)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� �������� ���������� ���");
      }
      $total = mysql_result($tot, 0);
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber)-$number != 0) $number++;

      echo "<tr><td class=bottomtablen colspan=4><p class=texthelp>";
      pager($page, $total, $pnumber, $page_link, "&id_forum=$id_forum&id_author=$id_author&arch=$_GET[arch]");
      echo "</td></tr>";
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