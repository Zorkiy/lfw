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


  try
  {
    $title = '������������';  
    $pageinfo = '<p class=help>���������� � ������������� ������</p>';

    // �������� ��������� ��������
    require_once("../utils/top.php");
    // ����
    require_once("forummenu.php");
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center>��������&nbsp;������</td>
      <td align=center>����������&nbsp;���������</td>
      <td align=center>���������&nbsp;���������</td>
      <td colspan=2 align=center>��������</td>
      <td colspan=3 align=center>������</td>
    </tr>
    <?php
    // ���������� ������� �� $pnumber ����
    $pnumber = 25;
    // ��������� ��������� �� ������ �������
    $page = intval($_GET['page']);
    $id_forum = intval($_GET['id_forum']);

    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    $query = "SELECT * FROM $tbl_authors
              ORDER BY themes DESC 
              LIMIT $begin, $pnumber";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��������� � �������������");
    }
    if(mysql_num_rows($ath))
    {
      // ������� ������ ������ $pnumber �������
      while($author = mysql_fetch_array($ath))
      {
        $user  = "<a href=athsetuser.php?id_author=$author[id_author]&page=$page
                     title='��������� ���������� ������ �������� ��������� ������'>����������</a>";
        $moder = "<a href=athsetmoder.php?id_author=$author[id_author]&page=$page
                     title='��������� ���������� ������ ���������� ������'>���������</a>";
        $admin = "<a href=athsetadmin.php?id_author=$author[id_author]&page=$page
                     title='��������� ���������� ������ �������������� ������'>�������������</a>";
        $userhead = "";
        $moderhead = "";
        $adminhead = "";
        if($author['statususer'] == '') $userhead = "class=header";
        if($author['statususer'] == 'moderator') $moderhead = "class=header";
        if($author['statususer'] == 'admin') $adminhead = "class=header";
        echo "<tr>
                <td><a href=author.php?id_author=$author[id_author]>".htmlspecialchars($author['name'], ENT_QUOTES)."</a></td>
                <td align=center>$author[themes]</td>
                <td align=center>".convertdate($author['time'])."</td>
                <td align=center><a href=athedit.php?id_author=$author[id_author]&page=$page>�������������</a></td>
                <td align=center><a href=# onClick=\"delete_position('athdel.php?id_author=$author[id_author]&page=$page','�� ������������� ������ ������� ������������?');\">�������</a></td>
                <td align=center $userhead>$user</td>
                <td align=center $moderhead>$moder</td>
                <td align=center $adminhead>$admin</td>
             </tr>";
      }
      // ������� ������ �� ������ �������
      $query = "SELECT COUNT(*) FROM $tbl_authors";
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
?>