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
  // ��������� ������
  require_once("../utils/utils.settings.php");
  // ������� ��� ������ � ��������������
  require_once("../utils/utils.users.php");
  // ������� ��� ������ � �������
  require_once("../utils/utils.files.php");

  try
  {
    // ����� �������� ��������
    $nameaction = "������� ������";
    // ������� "�����" ��������
    include "../utils/topforumaction.php"; 
    $_GET['id_forum'] = intval($_GET['id_forum']);
    // ��������� ��������� ������
    $query = "SELECT * FROM $tbl_forums 
              WHERE id_forum=$_GET[id_forum]";
    $rls = mysql_query($query);
    if(!$rls)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������� 
                               ���������� ������");
    }
    $rules = mysql_fetch_array($rls);
    echo "<p class=linkbackbig><a href='javascript: history.back()'>��������� �����</a></p>";
    echo "<p class=remark>$rules[rule]</p>";
    // ������� ���������� ��������
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
