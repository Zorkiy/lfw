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

  $_GET['id_catalog'] = intval($_GET['id_catalog']);

  try
  {
    // ���������� ������ � ������������ ���������
    $page_link = 3;
    // ���������� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_photo_position,
                           "WHERE id_catalog = $_GET[id_catalog]",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link,
                           "&id_catalog=$_GET[id_catalog]");
    echo "<a class=menu 
             href=phtadd.php?id_catalog=$_GET[id_catalog]&".
             "page=$_GET[page]>�������� �������</a><br><br>";

    // �������� ������ ���� ������ � ���� �������
    $photo = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - ������� 
    if(!empty($photo))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>�����������</td>
          <td>��������</td>
          <td width=20 align=center>���.</td>
          <td>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($photo); $i++)
      {
        // ��������� URL ��� ����������� ������
        $url = "?id_position={$photo[$i][id_position]}".
               "&id_catalog=$_GET[id_catalog]&".
               "page=$_GET[page]";
        // �������� ������ ���������� ��� ���
        $colorrow = "";
        if($photo[$i]['hide'] == "hide")
        {
          $showhide = "<a href=phtshow.php$url>����������</a>";
          $colorrow = "class='hiddenrow'";
        } 
        else
        {
          $showhide = "<a href=phthide.php$url>������</a>";
        }
        $size = @getimagesize("../../".$photo[$i]['big']);

        // ������� �������
        echo "<tr $colorrow >
                <td align=center>
                  <a href=# 
                     onclick=\"show_img('{$photo[$i][id_position]}',".
                     $size[0].",".$size[1]."); return false \">
                    <img src=../../{$photo[$i][small]} 
                         border=1 
                         style=\"border-color:#000000\" 
                         vspace=3></a>
                </td>
                <td valign=top>�������� : {$photo[$i][name]}<br>
                               ALT-���: {$photo[$i][alt]}</td>
                <td align=center>{$photo[$i][pos]}</td>
                <td align=center>
                  <a href=phtup.php$url>�����</a><br>
                  $showhide<br>
                  <a href=# onClick=\"delete_position('phtdel.php$url',".
                  "'�� ������������� ������ ������� �������?');\">�������</a><br>
                  <a href=phtedit.php$url
                      title='������������� �������'>�������������</a><br>
                  <a href=phtdown.php$url>����</a>
                </td>
              </tr>";
      }
      echo "</table><br>";
    }
    echo $obj;
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
  function show_img(id_position,width,height)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a = (screen.height-vidWindowHeight)/5;
    b = (screen.width-vidWindowWidth)/2;
    features = "top=" + a + ",left=" + b + 
               ",width=" + vidWindowWidth + 
               ",height=" + vidWindowHeight + 
               ",toolbar=no,menubar=no,location=no," + 
               "directories=no,scrollbars=no,resizable=no";
    url = "../../show.php?id_position=" + id_position;
    window.open(url,'',features,true);
  }
//-->
</script>