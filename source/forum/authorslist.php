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
    // �������� "�����" ��������
    $nameaction = "������ ���������� ������";
    include "../utils/topforumaction.php";
    // ��������� ��������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    $page = intval($_GET['page']);
    ?>
    <p class=linkbackbig><a href="index.php?id_forum=<?php echo $id_forum; ?>">��������� �����</a></p>         
    <table class="tablen" width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="silver">
    <tr><td class=tableheadern><p class="fieldname">��������&nbsp;������</td>
    <td class=tableheadern><p class="fieldname"><a href=authorslist.php?id_forum=<?php echo $id_forum; ?>&page=<?php echo $page; ?> title="����������� �� ���������� ���������">����������&nbsp;���������</a></td>
    <td class=tableheadern><p class="fieldname"><a href=authorslist.php?id_forum=<?php echo $id_forum; ?>&page=<?php echo $page; ?>&order=time title="����������� �� ���� ���������� ���������">���������&nbsp;���������</a></td>
    <td class=tableheadern><p class="fieldname">������</td></tr>
    <?php
    // ��������� �� ������ ���� ������������ 
    // ����������
    $ord = "themes DESC";
    $orde = "";
    if($_GET['order'] == "time")
    {
      $ord = "time DESC";
      $orde = "time";
    }
    // ���������� ������� �� $pnumber ����
    $pnumber = 25;
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    $query = "SELECT * FROM $tbl_authors
              ORDER BY $ord 
              LIMIT $begin, $pnumber";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� � ������� �������");
    }
    if(mysql_num_rows($ath) > 0)
    {
      // ������� ������ ������ $pnumber �������
      while($author = mysql_fetch_array($ath))
      {
        // ��������� ������ ������
        $status = "";
        if($author['statususer'] == 'moderator') $status = "���������";
        if($author['statususer'] == 'admin') $status = "�������������";
        echo "<tr class=trtablen><td><p class=authorreg><nobr><a class=authorreg href=info.php?id_forum=$id_forum&id_author=$author[id_author]>".htmlspecialchars($author['name'])."</a></nobr></td>
            <td><p class=texthelp align=center>".$author['themes']."</td>
            <td><p class=texthelp align=center>".convertdate($author['time'])."</td><td><p align=center>$status</p></td></tr>";
      }
      // ����� ���������� �������
      $query = "SELECT COUNT(*) FROM $tbl_authors";
      $tot = mysql_query($query);
      if(!$tot)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� � ������� �������");
      }
      $total = mysql_result($tot, 0);

      // ������� ������ �� ������ �������
      $page_link = 1;
      $number = (int)($total/$pnumber);
      if((float)($total/$pnumber)-$number != 0) $number++;
      echo "<tr><td class=bottomtablen colspan=4><p class=texthelp>";
      pager($page, $total, $pnumber, $page_link, "&id_forum=$id_forum&order=$orde");
      echo "</td></tr>";
    }
    echo "</table>";
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