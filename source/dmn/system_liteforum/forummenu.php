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

  eval("\$menu$menu=\"class=tdactivemenu\";");    
  $dx=100/7;
?>
<table width=100% 
       class="table" 
       border="0" 
       cellpadding="0" 
       cellspacing="0">
<tr class="header" align="center" valign="middle">
  <td width=<?echo $dx?>% class="header"><a class=menu href=index.php>������� ������</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=themes.php>�������������</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=statistics.php>����������</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=authorslist.php>��������� ������</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=settings.php>��������� ������</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=links.php?part=1>������</a></td>  
  <td width=<?echo $dx?>% class="header"><a class=menu href=archive.php>�������������</a></td>
</tr>
</table><br><br>
