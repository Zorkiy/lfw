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

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // ������������� ����
  require_once("../utils/utils.navigation.php");
  // ���������� ���� ����������� ������ � ���� ��������
  require_once("../utils/utils.print_page.php");

  $title = $titlepage = '�������';  
  $pageinfo = '<p class=help>����� �������������� ����������
                             ��������� �����</p>';

  // �������� ��������� ��������
  require_once("../utils/top.php");

  try
  {
    // ������ ��� ���������� �������
    echo "<a class=menu href=catadd.php>�������� �������</a>&nbsp;&nbsp;
          <a class=menu href=settings.php>���������</a><br><br>";
  
    // ������� ������ ���������
    $query = "SELECT * FROM $tbl_photo_catalog
              ORDER BY pos";
      
    $ctg = mysql_query($query);
    if(!$ctg)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������
                               � ��������");
    }
    if(mysql_num_rows($ctg)>0)
    {
      // ������� ��������� ������� ���������
      echo '<table width="100%" 
                   class="table" 
                   border="0" 
                   cellpadding="0" 
                   cellspacing="0">
                <tr class="header" align="center">
                  <td align=center>��������</td>
                  <td align=center>��������</td>
                  <td width=50 align=center>��������</td>
                </tr>';
      while($catalog = mysql_fetch_array($ctg))
      {
        $url = "id_catalog=$catalog[id_catalog]";
        // �������� ����� ������� ��� ���
        if($catalog['hide'] == 'hide') {
          $strhide = "<a href=catshow.php?$url>����������</a>";
          $style=" class=hiddenrow ";
        } 
        else
        {
          $strhide = "<a href=cathide.php?$url>������</a>";
          $style="";
        }
        // ��������� ���������� ���������� � �������
        $query = "SELECT COUNT(*) FROM $tbl_photo_position
                  WHERE id_catalog = $catalog[id_catalog]";
        $cnt = mysql_query($query);
        if(!$cnt)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� �-��
                                   �����������");
        }
        $total = mysql_result($cnt, 0);
        if($total) $total = "&nbsp;($total)";
        else $total = "";


        // ������� ������ ���������
        echo "<tr $style >
              <td><a href=photos.php?id_catalog=$catalog[id_catalog]>$catalog[name]$total</td>
              <td>".nl2br(print_page($catalog['name']))."</td>
              <td>
              <a href=catup.php?$url>�����</a><br>
              $strhide<br>
              <a href=catedit.php?$url>�������������</a><br>
              <a href=# onClick=\"delete_catalog('catdel.php?$url',".
              "'�� ������������� ������ ������� ������?');\">�������</a><br>
              <a href=catdown.php?$url>����</a><br></td>
            </tr>";
      }
      echo "</table><br>";
    }
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>