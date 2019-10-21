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

  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� SQL-�������
  require_once("utils.query_result.php");
  // ������������ ���������
  require_once("../utils/utils.pager.php");

  try
  {
    $title = $titlepage =  '�������������� ������';  
    $pageinfo = '<p class=help>�� ������ �������� �����
    ��������, ������� ��� ��������������� ��������������
    ������</p>';

    // �������� ��������� ��������
    require_once("../utils/top.php");
    // ����
    require_once("forummenu.php");

    // ���������� ������� �� ��������
    $pnumber = 5;
    // ���� � ������ ������� �� �������� ��������
    // ������� ������ ��������
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;
    $begin = ($page - 1)*$pnumber;

    if(empty($_GET['part'])) $_GET['part'] = 1;
    else $_GET['part'] = intval($_GET['part']);

    // ������� ��������� �������
    $query = "SELECT * FROM $tbl_links 
              WHERE part=$_GET[part]
              ORDER BY pos DESC
              LIMIT $begin, $pnumber";
    $res = mysql_query($query);
    if(!$res)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� 
                               � ������� ������");
    }
    echo "<a class=menu href=lnkadd.php?part=$_GET[part]>�������� ������</a>&nbsp;&nbsp;";
    if ($_GET['part']==1) echo "<a class=menu href=links.php?part=2>�������������� ������</a>";
    else echo "<a class=menu href=links.php?part=1>���������</a>";
    echo "<br><br>";
    ?>    
    <table width=100% 
         class=table 
         border=0 
         align=center 
         cellpadding=0 
         cellspacing=0>
    <tr class=header align="center">
      <td width=50px>�������</td>
      <td>������</td>
      <td>��������</td>
    </tr>
    <?php
    if(mysql_num_rows($res))
    {
      while($links = mysql_fetch_array($res))
      {
        $url = "id_link=$links[id_links]&part=$_GET[part]&page=$_GET[page]";
        // ���� ������ �������� ��� ��������� (hide='hide'), �������
        // ������ "����������", ���� ��� ������� (hide='show') - "������"
        if($links['hide'] == 'show')
        {
          $showhide = "<a href=lnkhide.php?$url title='������ ������'>������</a>";
          $colorrow = "";
        }
        else
        {
          $showhide = "<a href=lnkshow.php?$url title='���������� ������'>����������</a>";
          $colorrow = "class='hiddenrow'";
        }
        // ������� �������
        echo "<tr $colorrow valign=top>
              <td>$links[pos]</td>
              <td>
              <a href='$links[url]'>".htmlspecialchars($links['name'])."</a><br>
              ".htmlspecialchars($links['url'])."
              </td>
              <td align=center>
                 $showhide<br>
                 <a href=# onClick=\"delete_position('lnkdel.php?$url','�� ������������� ������ ������� ������?');\" title='������� ������'>�������</a><br>
                 <a href=lnkedit.php?$url title='������������� ������'>�������������</a>
              </td>
            </tr>";
      }
    }
    ///////////////////////////////////////////////////////////
    // ������������ ���������
    ///////////////////////////////////////////////////////////
    $page_link = 4;
    // ����������� ���������� �� ���������� ���� ���
    $query = "SELECT COUNT(*) FROM $tbl_links 
              WHERE part = $_GET[part]";
    $total = query_result($query);
    $number = (int)($total/$pnumber);
    if((float)($total/$pnumber) - $number != 0) $number++;

    // ������� ������ �� ������ ��������
    echo "<tr><td class=bottomtablen colspan=3>";
    pager($page, 
          $total, 
          $pnumber, 
          3, 
          "&part=$_GET[part]");
    echo "</td></tr></table>";

    // ������� ���������� ��������
    require_once("../utils/bottom.php");
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }
?>