<table class=toplinks border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr align="center">
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

    $query = "SELECT * FROM $tbl_links 
              WHERE part = 2 
              ORDER BY pos";
    $res = mysql_query($query);
    if(!$res)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ������");
    }
    if (mysql_num_rows($res))
    {
      while($link = mysql_fetch_array($res))
      {
        echo "<td><a class=menuinfo href='$link[url]'>$link[name]</a></td>";
      }
    }
  ?>  
  </tr>
</table>