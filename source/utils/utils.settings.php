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

  function get_settings()
  {
    // ��������� ���������� � ������� ������ �����������
    global $tbl_settings;

    // �������� ��������� ������ ������������ ���������������
    $query = "SELECT * FROM $tbl_settings";
    $set = mysql_query($query);
    if(!$set)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ������� 
                                �������� ������");
    }
    if(mysql_num_rows($set)) return mysql_fetch_array($set);
    else false;
  }
?>
