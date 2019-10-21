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

  // ���������� SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ������� ��� ������ � �����������
  require_once("../utils/utils.posts.php");
  // ������� ��� ��������� �������
  require_once("../utils/utils.time.php");
  // ���������� ������������ ���������
  require_once("../utils/utils.pager.php");

  try
  {
    // �������� "�����" ��������
    $nameaction = "������ ��������";
    include "../utils/topforumaction.php";
    // ��������� ��������� �� ������ �������
    if(empty($_GET['id_forum'])) $_GET['id_forum'] = 1;
    $id_forum = intval($_GET['id_forum']);
    $page = intval($_GET['page']);
  ?>
  <p class=linkbackbig><a href="index.php?id_forum=<?php echo $id_forum; ?>">��������� �����</a></p>         
  <table class="tablen" width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="silver">
  <tr><td class=tableheadern><p class="fieldname">�������</td></tr>
  <?php
    // ���������� ������� �� $pnumber ����
    $pnumber = 25;
    if(empty($page)) $page=1;
    $begin = ($page - 1)*$pnumber;
    $query = "SELECT * FROM $tbl_links
              WHERE part = 1
              ORDER BY pos DESC
              LIMIT $begin, $pnumber";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ����������
                                ��������� �������");
    }
    if(mysql_num_rows($ath))
    {
      // ������� ������ ������ $pnumber �������
      while($author = mysql_fetch_array($ath))
      {
        echo "<tr class=trtablen>
               <td><p class=authorreg><nobr><a class=authorreg href=$author[url]>".$author['name']."</a></nobr></td></tr>";
      }
      // ������������ ���������
      $page_link = 1;

      $query = "SELECT COUNT(*) FROM $tbl_links WHERE part = 1";
      $tot = mysql_query($query);
      if(!$tot)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� ����������
                                  ��������� �������");
      }
      if(mysql_num_rows($tot)) $total = mysql_result($tot, 0);
      echo "<tr><td class=bottomtablen><p class=texthelp>";
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
