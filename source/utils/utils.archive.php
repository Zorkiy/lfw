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

  // ���������� ��������� � ����
  function get_archiv_id()
  {
    // ��������� ���������� � ������� ������ �����������
    global $tbl_archive_number;

    // ��������� ��������� ���� ����, ������� ��������� � 
    // �������� �������
    $query = "SELECT id_theme FROM $tbl_archive_number LIMIT 1";
    $arh = mysql_query($query);
    if(!$arh)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ������� ���������
                                �������� ����");
    }
    if(mysql_num_rows($arh)) return mysql_result($arh, 0);
    else return 0;
  }
?>