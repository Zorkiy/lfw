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

  // �������������� ������ ���� ������
  function valuesize($filesize)
  {
    // ���� ������ ���� ��������� 1024 �����,
    // ������������� ������ � ��
    if($filesize > 1024)
    {
      $filesize = (float)($filesize/1024);
      // ���� ������ ���� ��������� 1024 ������,
      // ������������� ������ � ������
      if($filesize > 1024)
      {
        $filesize = (float)($filesize/1024);
        // ��������� ������� ����� ��
        // ������� ����� ����� �������
        $filesize = round($filesize, 1);
        return $filesize." ��";
      }
      else
      {
        // ��������� ������� ����� ��
        // ������� ����� ����� �������
        $filesize = round($filesize, 1);
        return $filesize." ��";
      }
    }
    else
    {
      return $filesize." ����";
    }
  }

  // ������������ ������� �����
  function getfilesize($filename)
  {
    $filesize=filesize($filename);
    if($filesize > 1024)
    {
      $filesize = (float)($filesize/1024);
      // ���� ������ ���� ��������� 1024 ������
      // ������������� ������ � ������
      if($filesize > 1024)
      {
        $filesize = (float)($filesize/1024);
        // ��������� ������� ����� ��
        // ������� ����� ����� �������
        $filesize = round($filesize, 1);
        return $filesize." ��";
      }
      else
      {
        // ��������� ������� ����� ��
        // ������� ����� ����� �������
        $filesize = round($filesize, 1);
        return $filesize." ��";
      }
    }
    else
    {
      return $filesize." ����";
    }
  }
?>
