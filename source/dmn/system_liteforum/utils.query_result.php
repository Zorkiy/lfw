<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) �������� �.�., �������� �.�.
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

  function query_result($query)
  {
    $tot = mysql_query($query);
    if(!$tot)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ���������� �������");
    }
    if(mysql_num_rows($tot))
    {
      return @mysql_result($tot, 0);
    }
    else
    {
      return false;
    }
  }
?>