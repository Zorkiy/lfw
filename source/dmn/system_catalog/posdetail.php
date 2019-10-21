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
    $query = "SELECT * FROM $tbl_cat_position
              WHERE id_position = $_GET[id_position]
              LIMIT 1";
    $pos = mysql_query($query);
    if(!$pos)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� � 
                               ������� �������");
    }
    if(mysql_num_rows($pos))
    {
      $position = mysql_fetch_array($pos);
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
        // ���������� �����
        $distr = "�����������";
        switch ($position['district'])
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
        // ���������� �������� ����
        $material = "���������";
        switch ($position['material'])
        {
          case 'brick':
            $material = "����������";
            break;
          case 'concrete':
            $material = "���������";
            break;
          case 'reconcrete':
            $material = "�������";
            break;
        }
        // ���������� ��� ���.����
        $su = "���.";
        switch ($position['su'])
        {
          case 'separate':
            $su = "���.";
            break;
          case 'combined':
            $su = "����.";
            break;
        }
        // ���������� ������� �������
        $balcony = "������";
        switch ($position['balcony'])
        {
          case 'balcony':
            $balcony = "������";
            break;
          case 'loggia':
            $balcony = "������";
            break;
        }
        echo "<tr>
                <td align=right>�����</td>
                <td>$distr</td>
              </tr>";
        echo "<tr>
                <td align=right>�����</td>
                <td>$position[address]</td>
              </tr>";
        echo "<tr>
                <td align=right>�������(�/�/�)</td>
                <td>$position[squareo]/$position[squarej]/$position[squarek]</td>
              </tr>";
        echo "<tr>
                <td align=right>���. ������</td>
                <td>$position[rooms]</td>
              </tr>";
        echo "<tr>
                <td align=right>����</td>
                <td>$position[floor]</td>
              </tr>";
        echo "<tr>
                <td align=right>��������� ����</td>
                <td>$position[floorhouse]</td>
              </tr>";
        echo "<tr>
                <td align=right>��������</td>
                <td>$material</td>
              </tr>";
        echo "<tr>
                <td align=right>���. ����</td>
                <td>$su</td>
              </tr>";
        echo "<tr>
                <td align=right>������</td>
                <td>$balcony</td>
              </tr>";
        echo "<tr>
                <td align=right>����</td>
                <td>$position[price]</td>
              </tr>";
        echo "<tr>
                <td align=right>���� �.��.</td>
                <td>$position[pricemeter]</td>
              </tr>";
        echo "<tr>
                <td align=right>������</td>
                <td>$position[currency]</td>
              </tr>";
    }
    echo "</table><br><br>";
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