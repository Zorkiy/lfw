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
    $nameaction="���������� � ������������";
    // �������� "����� ��������"
    require_once("../utils/topforumaction.php");

    // ��������� ���������� �� ������ �������
    $id_author = intval($_GET['id_author']);
    $id_forum = intval($_GET['id_forum']);
    // ��������� ���������� � ����������
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_author
              LIMIT 1";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ����������
                                ���������� ������");
    }
    if(mysql_num_rows($ath))
    {
      $author = mysql_fetch_array($ath);
      echo "<p class=linkbackbig><a href=index.php?id_forum=$id_forum>��������� �����</a></p>";
      require_once("../utils/include.info.php");
    }
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
