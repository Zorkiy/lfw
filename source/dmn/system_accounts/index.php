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
  $title = '���������� ����������';
  $pageinfo = '<p class=help>����� ����� �������� ������ 
               ������������, ��������������� ��� ������� 
               �������������. �� �� ������ ������ ������
               ������������� ������������, ��� ��� �� 
               ��������� ����������, ������ �� ������ 
               ��������� ��� ����� ������</p>';

  // �������� ��������� ��������
  require_once("../utils/top.php");

  try
  {
    // ����� ������ � ������������ ���������
    $page_link = 3;
    // ����� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_accounts,
                           "",
                           "ORDER BY name",
                           $pnumber,
                           $page_link);
  
    // �������� �������
    echo "<a href=addaccount.php?page=$_GET[page]
             title='�������� ����� �������'>
             �������� �������</a><br><br>";
  
    // �������� ���������� ������� ��������
    $accounts = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - �������
    if(!empty($accounts))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>������������</td>
          <td>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($accounts); $i++)
      {
        // ������� ������ �������
        echo "<tr>
                <td align=center>{$accounts[$i][name]}</td>
                <td align=center>
                  <a href=# 
                     onClick=\"delete_position('".
                    "delaccount.php?page=$_GET[page]&".
                    "id_account={$accounts[$i][id_account]}',".
                    "'�� ������������� ������ ������� �������?');\" 
                     title='������� ������������'>�������</a></td>
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