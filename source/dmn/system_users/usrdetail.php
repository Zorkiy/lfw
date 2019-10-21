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

  $title = '��������� ����������';  
  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><?php echo $title; ?></title>
<link rel="StyleSheet" type="text/css" href="../utils/cms.css">
</head>
<body leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" bottommargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" class="text">
  <tr valign="top">
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr valign=top>
    <td width=0>&nbsp;</td>
    <td class=main height=100%>

<?php
  // ��������� GET-���������
  $_GET['id_position'] = intval($_GET['id_position']);

  try
  {
    $query = "SELECT * FROM $tbl_users
              WHERE id_position = $_GET[id_position]
              LIMIT 1";
    $usr = mysql_query($query);
    if(!$usr)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� � 
                               ������� �������������");
    }
    if(mysql_num_rows($usr) > 0)
    {
      $user = mysql_fetch_array($usr);
      // �������� ����� ������������ ��� ���
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>��������</td>
          <td>��������</td>
        </tr>
      <?php
      if(!empty($user['name']))
      {
        echo "<tr>
                <td align=right>���</td>
                <td>".htmlspecialchars($user['name'])."</td>
              </tr>";
      }
      if(!empty($user['pass']))
      {
        echo "<tr>
                <td align=right>������</td>
                <td>".htmlspecialchars($user['pass'])."</td>
              </tr>";
      }
      if(!empty($user['email']))
      {
        echo "<tr>
                <td align=right>E-mail</td>
                <td>
                  <a href=mailto:".htmlspecialchars($user['email']).">".
                    htmlspecialchars($user['email'])."</a></td>
              </tr>";
      }
      if(!empty($user['block']))
      {
        if($user['block'] == 'block')
          $statususer = "��";
        else
          $statususer = "���";

        echo "<tr>
                <td align=right>������������?</td>
                <td>$statususer</td>
              </tr>";
      }
      if(!empty($user['dateregister']))
      {
        // ����������� ���� �����������
        list($date, $time)        = explode(" ", $user['dateregister']);
        list($year, $month, $day) = explode("-", $date);
        $time = substr($time, 0, 5);
        echo "<tr>
                <td align=right>���� �����������</td>
                <td>$day.$month.$year $time</td>
              </tr>";
      }
      if(!empty($user['lastvisit']))
      {
        // ����������� ���� ���������� ������
        list($date, $time)        = explode(" ", $user['lastvisit']);
        list($year, $month, $day) = explode("-", $date);
        $time = substr($time, 0, 5);
        echo "<tr>
                <td align=right>���� ���������� ������</td>
                <td>$day.$month.$year $time</td>
              </tr>";
      }
      echo "</table><br><br>";
    }
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
?>
</td>
<td width=10>&nbsp;</td>
</tr>
<tr class=authors>
  <td colspan="3"></td></tr>
</table>
</body>
</html>