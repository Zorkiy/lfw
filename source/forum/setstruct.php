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
    // ��������� �������� ���������� �� ������ �������
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $page     = intval($_GET['page']);
    $struct   = $_GET['struct'];
    $down     = $_GET['down'];
    // ���������� ����, ��� ���������� �����
    $tmppos = strrpos($_SERVER['PHP_SELF'],"/") + 1;
    $path = substr($_SERVER['PHP_SELF'], 0, $tmppos);
    if($struct)
    {
      $settings = get_settings();
      @setcookie("lineforum", "set_line_forum", time() + 3600*24*$settings['cooktime'], $path);
      // ���� ���������� ���������� $down ����� ��������� ��������� �� ������ ��� � �����
      if(!empty($down)) 
      {
        setcookie("lineforumdown", "set_line_forum_down", time() + 3600*24*$settings['cooktime'], $path);
      }
      else setcookie("lineforumdown", "", 0, $path);
    } else setcookie("lineforum", "", 0, $path);
    // ������������ �������������� ������� � ����
    header("Location: read.php?id_forum=$id_forum&id_theme=$id_theme&page=$page");
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