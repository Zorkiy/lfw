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

  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ������� ��� ������ � ��������������
  require_once("../utils/utils.users.php");

  // ������� cookie
  cleanallcookie();
  // ������������ �������������� ������� �����
  if(empty($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER'] = "index.php";
  header("Location: $_SERVER[HTTP_REFERER]");
?>