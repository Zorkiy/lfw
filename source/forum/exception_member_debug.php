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
  // ���������� ������� ��������� ������ (http://www.softtime.ru/info/articlephp.php?id_article=23)
  Error_Reporting(E_ALL & ~E_NOTICE); 

  if(defined("DEBUG"))
  {
    echo "<p class=help>��������� �������������� 
          �������� (ExceptionMember) - ������� 
          ��������� � ��������������� ����� ������.
          {$exc->getMessage()}.</p>";
    echo "<p class=help>������ � ����� {$exc->getFile()}
          � ������ {$exc->getLine()}.</p>";
    exit();
  }
  else
  {
    echo "<HTML><HEAD>
            <META HTTP-EQUIV='Refresh' CONTENT='0; URL=exception_member.php'>
          </HEAD></HTML>";
    exit();
  }
?>