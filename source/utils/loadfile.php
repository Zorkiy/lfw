<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 
  ///////////////////////////////////////////////////
  // �������� �����-������ �� ������
  ///////////////////////////////////////////////////
  if(!defined("ADDMESSAGE")) return;
  $path = '';
  // ���� ���� ������ �����(�������) �� ������,
  // ���������� ��� �� ������ � ���������������
  if (!empty($_FILES['attach']['tmp_name']))
  {
    if($_FILES['attach']['size'] > $settings['size_file'])
      $error[] = "������� ������� ���������� (����� ".valuesize($settings['size_file']).")";
    // ���������, �� �������� �� ���� �������� PHP ��� Perl, 
    // html, ���� ��� ��� ����������� ��� � ������ .txt
    $extentions = array("#\.php#i",
                        "#\.phtml#i",
                        "#\.php3#i",
                        "#\.html#i",
                        "#\.htm#i",
                        "#\.hta#i",
                        "#\.pl#i",
                        "#\.xml#i",
                        "#\.inc#i",
                        "#\.shtml#i", 
                        "#\.xht#i", 
                        "#\.xhtml#i");
    // ��������� �� ����� ����� ����������
    $ext = strrchr($_FILES['attach']['name'], "."); 
    // ��������� ���� � �����    
    $path="files/$id_theme-".date("YmdHis",time()).$ext; 
    foreach($extentions AS $exten) 
    {
      if(preg_match($exten, $ext)) 
        $path="files/$id_theme-".date("YmdHis",time()).".txt"; 
    }
    // ���������� ���� �� ��������� ���������� ������� �
    // ���������� /files Web-����������
    if(copy($_FILES['attach']['tmp_name'], $path))
    {
      // ���������� ���� �� ��������� ����������
      @unlink($_FILES['attach']['tmp_name']);
      // �������� ����� ������� � �����
      @chmod($path, 0644);
    }
  }
?>