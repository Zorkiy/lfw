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

  ///////////////////////////////////////////////////////////
  // ���������
  function title($title)
  {
    return '<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="20" bgcolor="#82A6DE" class="rightpanel_ttl">
            <img src="dataimg/dot_ttl.gif" align="absmiddle">
            '.htmlspecialchars($title).'
          </td>
        </tr>
        <tr>
          <td height="3" nowrap bgcolor="#004BBC"></td>
        </tr>
        </table>';
  }
?>