<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) �������� �.�., �������� �.�.
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

  // ���������
  require_once("utils.title.php");

  // ���������� ������� ������
  $pagename = "��������� ������ ��� ������ �����";
  $keywords = "��������� ������ ��� ������ �����";
  require_once ("templates/top.php");

  // ��������
  echo title($pagename);
  ?>
    <div class="main_txt">� ������ ����� ��������� ������.
      �������� ��� ���� ���������. ���� ��� �� ���������, ��������
      ���������� ������������� �� ��������������� � �������������.</td>
    </div>
<?php
  //���������� ������ ������
  require_once ("templates/bottom.php");
?>