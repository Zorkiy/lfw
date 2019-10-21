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

  try
  {
    // ���������� �������� ��������
    $nameaction = "������ ���������� ������ � \"OnLine\"";
    // �������� "�����" ��������
    include "../utils/topforumaction.php"; 

    // ��������� �� ������ ������� ��������� ����
    // ������ $id_forum
    $id_forum = intval($_GET['id_forum']);
    ?>
     <p class=linkbackbig><a href="javascript: history.back()">��������� �����</a></p>         
     <table class="tablen" width="100%" border="0" cellspacing="1" cellpadding="3">
     <tr>
        <td class=tableheadern><p class="fieldname">�����</td>
        <td class=tableheadern><p class="fieldname">����� ��������� ������</td>
     </tr>
     <?php
     // ������� ����������, ������� ����� �� ����� ����� 10 ����� �����
     $query = "SELECT * FROM $tbl_authors
               WHERE `time` > NOW() - INTERVAL '10' minute
               ORDER BY time DESC";
     $ath = mysql_query($query);
     $count = 0;
     if(!$ath)
     {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ������� ����������� OnLine");
     }
     if(mysql_num_rows($ath))
     {
       $count += mysql_num_rows($ath);
       while($authors = mysql_fetch_array($ath))
       {
         echo "<tr class=trtablen>
               <td><p class=authorreg><nobr><a class=authorreg href=info.php?id_forum=$id_forum&id_author=".$authors['id_author'].">".htmlspecialchars($authors['name'])."</a></nobr></p></td>
               <td><p class=texthelp align=center>".convertdate($authors['time'],0)."</p></td>
               </tr>";
       }
     }
     // ������� ����������, ������� ����� �� ����� � ���������
     // �� 10 �� 20 ����� ����� (��������)
     $query = "SELECT * FROM $tbl_authors
               WHERE `time` > NOW() - INTERVAL '20' minute AND
                     `time` < NOW() - INTERVAL '10' minute
               ORDER BY time DESC";
     $ath = mysql_query($query);
     if(!$ath)
     {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ������� ����������� OnLine");
     }
     if(mysql_num_rows($ath))
     {
       $count += mysql_num_rows($ath);
       while($authors = mysql_fetch_array($ath))
       {
         echo "<tr class=trtablen>
               <td><p class=authorreg><nobr><a class=authorhide href=info.php?id_forum=$id_forum&id_author=".$authors['id_author'].">".htmlspecialchars($authors['name'])."</a></nobr></p></td>
               <td><p class=texthelp align=center>".convertdate($authors['time'],0)."</p></td>
               </tr>";
       }
     }
     echo "<tr class=trtablen>
           <td><p class=texthelp><nobr>����� ������� OnLine</nobr></p></td>
           <td><p class=texthelp align=center>$count</p></td>
           </tr>";

    // ��������� ��������
    require_once("../utils/bottomforumaction.php");
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