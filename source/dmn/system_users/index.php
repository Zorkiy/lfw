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

  // ������ ���������� ���������� �������� �������� � ���������.
  $title = '���������� ��������������';
  $pageinfo = '<p class=help>������ �������� ��������� 
               ��������� ��������������� ����������� 
               ������������������ �������������</p>';
  // �������� ��������� ��������
  require_once("../utils/top.php");

  // �������� �������
  echo "<a href=usradd.php?page=$_GET[page]
           title='�������� ������������'>
           �������� ������������</a><br><br>";

  // ������� ����� ���������� ���������
  require_once("filter.php");

  $url = "&begin_date=$_GET[begin_date]".
         "&end_date=$_GET[end_date]";

  $where = "WHERE 1=1";
  if(!empty($_GET['begin_date']))
  {
    $where .= " AND dateregister >= '".
              date("Y-n-d H:i:s", $_GET['begin_date'])."'";
  }
  if(!empty($_GET['end_date']))
  {
    $where .= " AND dateregister <= '".
              date("Y-n-d H:i:s", $_GET['end_date'])."'";
  }

  try
  {
    // ����� ������ � ������������ ���������
    $page_link = 3;
    // ����� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_users,
                           $where,
                           "ORDER BY dateregister DESC",
                           $pnumber,
                           $page_link,
                           $url);
  
    // �������� ���������� ������� ��������
    $users = $obj->get_page();

    // ���� ������� ���� �� ���� ������ - �������
    if(!empty($users))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td align=center width=120>���� �����������</td>
          <td align=center>���</td>
          <td align=center>E-mail</td>
          <td align=center width=50>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($users); $i++)
      {
        $url = "?id_position={$users[$i][id_position]}&page=$page{$url}";

        // ������������ ������������ ��� ���
        $colorrow = "";
        if($users[$i]['block'] == 'block')
        {
          $blk = "<a href=usrunblock.php$url 
                          title='�������������� ������������'>
                       ��������������</a>";
          $colorrow = "class='hiddenrow'";
        }
        else
        {
          $blk = "<a href=usrblock.php$url 
                          title='������������� ������������'>
                       �����������</a>";
        }

        // ����������� ���� �����������
        list($date, $time)        = explode(" ", $users[$i]['dateregister']);
        list($year, $month, $day) = explode("-", $date);
        $time = substr($time, 0, 5);

        // ������� �������
        echo "<tr $colorrow>
                <td align=center>$day.$month.$year $time</td>
                <td align=center>
                  <a href=# 
                     onclick=\"show_detail('usrdetail.php?id_position={$users[$i][id_position]}',400,350); return false\">".
                     htmlspecialchars($users[$i]['name'])."</a></p></td>
                <td align=center>
                  <a href=mailto:".htmlspecialchars($users[$i]['email']).">".
                     htmlspecialchars($users[$i]['email'])."</a>$address_print</td>
                <td align=center>
                  $blk<br>
                  <a href=usredit.php$url>�������������</a><br>
                  <a href=# onClick=\"delete_user('usrdel.php$url',".
                  "'�� ������������� ������ ������� ������������?');\">�������</a>
                </td>
              </tr>";
      }
      echo "</table><br>";
    }
    // ������� ������ �� ������ ��������
    echo $obj;
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>
<script language="JavaScript">
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
               ",toolbar=no,menubar=no,location=no," + 
               "directories=no,scrollbars=no,resizable=no";
    window.open(url,'',features,true);
  }
//-->
</script>