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

  // ���������� ������� ������
  $pagename = "��������� ������ ��� ������ �����";
  $keywords = "��������� ������ ��� ������ �����";
  // �������� "�����" ��������
  require_once("../utils/topforum.php");

  ?>
      <div class="main_txt">� ������ ����� ��������� ������.
      �������� ��� ���� ���������. ���� ��� �� ���������, ��������
      ���������� ������������� �� ��������������� � �������������.</div>
  <?php
  // ������� ���������� ��������
  include "../utils/bottomforum.php";
?>