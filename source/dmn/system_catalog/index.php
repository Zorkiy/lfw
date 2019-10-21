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

  $title = $titlepage = '����������������� �������� ���������';  
  $pageinfo = '<p class=help>����� �������������� ����������������� 
               �������� ���������, ���������� ����� ������������ 
               � �������</p>';

  // �������� ��������� ��������
  require_once("../utils/top.php");

  $_GET['id_parent'] = intval($_GET['id_parent']);

  try
  {
    // ���� ��� �� �������� ������� ������� ������ ��� ��������
    // � ��� ���������� �����������
    echo '<table cellspacing="0" cellspacing="0" border=0>
          <tr valign="top"><td height="25"><p>';
    echo "<a class=menu 
             href=index.php?id_parent=0&page=$_GET[page]>
             �������� �������</a>-&gt;".
             menu_navigation($_GET['id_parent'], "", $tbl_cat_catalog).
         "<a class=menu href=catadd.php?".
         "id_catalog=$_GET[id_parent]&".
         "id_parent=$_GET[id_parent]&".
         "page=$_GET[page]>�������� ����������</a>";
    echo "</td></tr></table>";

    // ����� ������ � ������������ ���������
    $page_link = 3;
    // ����� ������� �� ��������
    $pnumber = 100;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_cat_catalog,
                           "WHERE id_parent=$_GET[id_parent]",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link,
                           "&id_parent=$_GET[id_parent]");

    // �������� ���������� ������� ��������
    $catalog = $obj->get_page();

    // ���� ������� ���� �� ���� ������ - �������
    if(!empty($catalog))
    {
      // ������� ��������� �������
      echo '<table width="100%" 
                   class="table" 
                   border="0" 
                   cellpadding="0" 
                   cellspacing="0">
              <tr class="header" align="center">
                <td align=center>��������</td>
                <td align=center>�������</td>
                <td align=center>��������</td>
                <td width=20 align=center>���.</td>
                <td width=50>��������</td>
              </tr>';
      for($i = 0; $i < count($catalog); $i++)
      {
        $url = "id_catalog={$catalog[$i][id_catalog]}&".
               "id_parent={$catalog[$i][id_parent]}&".
               "page=$_GET[page]";
        // �������� ����� ������� ��� ���
        if($catalog[$i]['hide'] == 'hide')
        {
          $strhide = "<a href=catshow.php?$url>����������</a>";
          $style=" class=hiddenrow ";
        } 
        else
        {
          $strhide = "<a href=cathide.php?$url>������</a>";
          $style="";
        }
      
        // ������������ ���������� ������� � ������ �� ������������
        $query = "SELECT COUNT(*) 
                  FROM $tbl_cat_position
                  WHERE id_catalog = {$catalog[$i][id_catalog]}";
        $pos = mysql_query($query);
        if(!$pos)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������� 
                                   ���������� �������");
        }
        $total = mysql_result($pos, 0);
        if($total > 0) $total = "&nbsp;($total)";
        else $total = "";

        // ������� ������ ���������
        echo "<tr $style >
          <td><a href=index.php?".
                "id_parent={$catalog[$i][id_catalog]}&page=$_GET[page]>".
                 htmlspecialchars($catalog[$i]['name'])."</a></td>
          <td align=center>
            <a href=position.php?id_catalog={$catalog[$i][id_catalog]}>�������$total</a>
          </td>
          <td>".
            nl2br(htmlspecialchars($catalog[$i]['description'])).
          "&nbsp;</td>
          <td align=center>{$catalog[$i][pos]}</td>
          <td>
            <a href=catup.php?$url>�����</a><br>
            $strhide<br>
            <a href=catedit.php?$url>�������������</a><br>
            <a href=# onClick=\"delete_position('catdel.php?$url',".
            "'�� ������������� ������ ������� �������?');\">�������</a><br>
            <a href=catdown.php?$url>����</a><br></td>
          </tr>";
      }
      echo "</table><br>";
      // ������� ������ �� ������ ��������
      echo $obj;
    }
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>