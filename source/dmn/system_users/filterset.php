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

  // ������������� ��������� ������
  $begin = "";
  if(!empty($_POST['chk_begin']))
  {
    $begin = mktime($_POST['b_date_hour'], 
                    $_POST['b_date_minute'], 
                    0, 
                    $_POST['b_date_month'], 
                    $_POST['b_date_day'], 
                    $_POST['b_date_year']);
  }
  $end = "";
  if(!empty($_POST['chk_end']))
  {
    $end = mktime($_POST['e_date_hour'], 
                  $_POST['e_date_minute'], 
                  0, 
                  $_POST['e_date_month'], 
                  $_POST['e_date_day'], 
                  $_POST['e_date_year']);
  }
  $url = "index.php?page=$_GET[page]&begin_date=$begin&end_date=$end";
  header("Location: $url");
?>