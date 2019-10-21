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
  $pageinfo = '<p class=help>����� �������������� ���������� �������, 
               �������� ��� �������������� ��� ������������ �������</p>';

  // �������� ��������� ��������
  require_once("../utils/top.php");

  $_GET['id_catalog'] = intval($_GET['id_catalog']);

  try
  {
    // ��������� ��������� �������� ��������
    $query = "SELECT * FROM $tbl_cat_catalog
              WHERE id_catalog = $_GET[id_catalog]
              LIMIT 1";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ����������
                               ���������� ��������");
    }
    $catalog = mysql_fetch_array($cat);
    // ���� ��� �� �������� ������� ������� ������ ��� ��������
    // � ��� ���������� �����������
    echo '<table cellspacing="0" cellspacing="0" border=0>
          <tr valign="top"><td height="25"><p>';
    echo "<a class=menu href=index.php?".
        "id_parent=0&page=$_GET[page]>�������� ����</a>-&gt;".
             menu_navigation($_GET['id_catalog'], "", $tbl_cat_catalog).
         "<a class=menu href=posadd.php?id_catalog=$_GET[id_catalog]".
            "&page=$_GET[page]>�������� �������</a>";
    echo "&nbsp;&nbsp;&nbsp;<a href=catcsvimport.php?id_catalog=$_GET[id_catalog]".
         "&page=$_GET[page]>������������� �� CSV-�������</a>";
    echo "</td></tr></table>";

    // ����� ������ � ������������ ���������
    $page_link = 3;
    // ����� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_cat_position,
                           "WHERE id_catalog=$_GET[id_catalog]",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link,
                           "&id_catalog=$_GET[id_catalog]");

    // �������� ���������� ������� ��������
    $position = $obj->get_page();

    // ���� ������� ���� �� ���� ������ - �������
    if(!empty($position))
    {
      // ������� ��������� �������
      echo '<table width="100%" 
                   class="table" 
                   border="0" 
                   cellpadding="0" 
                   cellspacing="0">
              <tr class="header" align="center">
                <td width=150>�����/�����</td>
                <td>����������</td>
                <td width=100>��������</td>
              </tr>';
      for($i = 0; $i < count($position); $i++)
      {
        $url = "id_position={$position[$i][id_position]}&".
               "id_catalog={$_GET['id_catalog']}&".
               "page={$_GET[page]}";
        // �������� ������ ������� ��� ���
        if($position[$i]['hide'] == 'hide')
        {
          $strhide = "<a href=posshow.php?$url>����������</a>";
          $style = " class=hiddenrow ";
        }
        else
        {
          $strhide = "<a href=poshide.php?$url>������</a>";
          $style = "";
        }

        // ���������� �����
        $distr = "�����������";
        switch ($position[$i]['district'])
        {
          case 'kanavinskii':
            $distr = "�����������";
            break;
          case 'nizhegorodskii':
            $distr = "�������������";
            break;
          case 'sovetskii':
            $distr = "���������";
            break;
          case 'priokskii':
            $distr = "���������";
            break;
          case 'moskovskii':
            $distr = "����������";
            break;
          case 'avtozavodskii':
            $distr = "�������������";
            break;
          case 'leninskii':
            $distr = "���������";
            break;
          case 'sormovskii':
            $distr = "����������";
            break;
        }
        
        // ������� �������
        echo "<tr $style>
                <td>
                  <a href=# onclick=\"show_detail('posdetail.php".
                    "?id_position={$position[$i][id_position]}',400,350);".
                    " return false\"
                    title=\"���������\">
                    $distr<br>
                    {$position[$i][address]}
                  </a>
                </td>
                <td>{$position[$i][note]}</td>";
        echo "  <td>
                  <a href=posup.php?$url>�����</a><br>
                  $strhide<br>
                  <a href=posedit.php?$url>�������������</a><br>
                  <a href=# onClick=\"delete_position('posdel.php?$url',".
                  "'�� ������������� ������ ������� �������?');\">�������</a><br>
                  <a href=posdown.php?$url>����</a>
                </td>
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
<script language='JavaScript1.1' type='text/javascript'>
<!--
  function show_detail(url,width,height)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a=(screen.height-vidWindowHeight)/5;
    b=(screen.width-vidWindowWidth)/2;
    features = "top=" + a + ",left=" + b + 
               ",width=" + vidWindowWidth + 
               ",height=" + vidWindowHeight + 
               ",toolbar=no,menubar=no,location=no" +
               ",directories=no,scrollbars=no,resizable=no";
    window.open(url,'',features,true);
  }
//-->
</script>