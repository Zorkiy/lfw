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

  // ������ ���������� ���������� �������� �������� � ���������.
  if(empty($_GET['file']))
  {
    $title = '�������������� ����������';
    $pageinfo = '<p class=help>�������������� ����� 
    ���������� � ���� ������� ('.htmlspecialchars($_GET['dir']).').
    ����� ������� ��������� ������ ����� �� ������, ������ � 
    �������� ���������� ��� ��������� �����, ��� ������ � ���� 
    ��������� �������������. ���������� ������� ������ ����� 
    ������ �� ����������� ���������. ��� � ������ ���������� 
    ������� � ��������� ��� ������� ������ ����.</p>';
    $name_position = "����������";
    $ur_hint = '������ ������ ���������� ��� ���������';
    $uw_hint = '�������� � �������������� ������ � ���������� 
                ��� ���������';
    $ux_hint = '������ ����������� ���������� ��� ���������';
    $gr_hint = '������ ������ ���������� ��� ������';
    $gw_hint = '�������� � �������������� ������ � ���������� 
                ��� ������';
    $gx_hint = '������ ����������� ���������� ��� ������';
    $or_hint = '������ ������ ���������� ��� ������������� �� 
                �������� � ������ ���������';
    $ow_hint = '�������� � �������������� ������ � ���������� 
                ��� ������������� �� �������� � ������ ���������';
    $ox_hint = '������ ����������� ���������� ��� ������������� 
                �� �������� � ������ ���������';
  }
  else
  {
    $title = '�������������� �����';
    $pageinfo = '<p class=help>�������������� ����� ����� 
    � ���� ������� ('.htmlspecialchars($_GET['dir']).')</p>
    ����� ������� ��������� ������ ����� �� ������, ������ 
    � ���������� ���������� ��� ��������� �����,
    ��� ������ � ���� ��������� �������������. ���������� 
    ������� ������ ����� ������ �� ����������� ���������. 
    ��� � ������ ���������� ������� � ��������� 
    ��� ������� ������ ����.';
    $name_position = "�����";
    $ur_hint = '������ ����� ��� ���������';
    $uw_hint = '�������������� ����� ��� ���������';
    $ux_hint = '���������� ����� ��� ���������';
    $gr_hint = '������ ����� ��� ������';
    $gw_hint = '�������������� ����� ��� ������';
    $gx_hint = '���������� ����� ��� ������';
    $or_hint = '������ ����� ��� ������������� �� �������� 
                � ������ ���������';
    $ow_hint = '�������������� ����� ��� ������������� �� 
                �������� � ������ ���������';
    $ox_hint = '���������� ����� ��� ������������� �� �������� 
                � ������ ���������';
  }

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // �������� ��������� ��������
  require_once("../utils/top.php");
  // ������������� ���������� � FTP-��������
  require_once("../../config/ftp_connect.php");

  // ��������� �� ������ ������� ��� ���������� ����������
  // � ����� �������
  $dir = $_GET['dir'];
  $acc = $_GET['acc'];
  // ������������ ������ ���� ������� ������������
  if(substr($acc, 1, 1) == 'r') $ur = "checked";
  else $ur = "";
  if(substr($acc, 2, 1) == 'w') $uw = "checked";
  else $uw = "";
  if(substr($acc, 3, 1) == 'x') $ux = "checked";
  else $ux = "";
  // ������������ ������ ���� ������� ������
  if(substr($acc, 4, 1) == 'r') $gr = "checked";
  else $gr = "";
  if(substr($acc, 5, 1) == 'w') $gw = "checked";
  else $gw = "";
  if(substr($acc, 6, 1) == 'x') $gx = "checked";
  else $gx = "";
  // ������������ ������ ���� ������� ��������� �������������
  if(substr($acc, 7, 1) == 'r') $or = "checked";
  else $or = "";
  if(substr($acc, 8, 1) == 'w') $ow = "checked";
  else $ow = "";
  if(substr($acc, 9, 1) == 'x') $ox = "checked";
  else $ox = "";
  // ���� �� �������� ��������� - �����������
  // ����� �� ���������� ����������
  $action = "chdir.php";
  $button = "�������������";
  // �������� HTML-�����
?>
<form action=<?= $action; ?> method=post>
<table>
<tr>
  <td class=field>�������� 
    <? echo htmlspecialchars($name_position); ?>:</td>
  <td><input size=31 type=text name=name 
  value='<? echo htmlspecialchars(basename($dir)); ?>'></td>
</tr>
<tr>
  <td class=field>����� �������:</td>
  <td>
    <input type=checkbox 
           title='<?php echo $ur_hint; ?>' 
           name=ur <?php echo $ur; ?>>
    <input type=checkbox 
           title='<?php echo $uw_hint; ?>' 
           name=uw <?php echo $uw; ?>>
    <input type=checkbox 
           title='<?php echo $ux_hint; ?>' 
           name=ux <?php echo $ux; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $gr_hint; ?>' 
           name=gr <?php echo $gr; ?>>
    <input type=checkbox 
           title='<?php echo $gw_hint; ?>' 
           name=gw <?php echo $gw; ?>>
    <input type=checkbox 
           title='<?php echo $gx_hint; ?>' 
           name=gx <?php echo $gx; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $or_hint; ?>' 
           name=or <?php echo $or; ?>>
    <input type=checkbox 
           title='<?php echo $ow_hint; ?>' 
           name=ow <?php echo $ow; ?>>
    <input type=checkbox 
           title='<?php echo $ox_hint; ?>' 
           name=ox <?php echo $ox; ?>>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><input class=button 
             type=submit 
             value=<?php echo htmlspecialchars($button);?>></td></tr>
  <input type=hidden 
         name=dir 
         value=<?php echo htmlspecialchars($dir);?>>
</table>
</form>
<?php
  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>