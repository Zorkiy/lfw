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
  // ���������� ���� ����������� ������ � ���� ��������
  require_once("../utils/utils.print_page.php");

  try
  {
    // ���������� �� SQL-��������
    $_GET['id_catalog'] = intval($_GET['id_catalog']);

    // ��������� ��������� �������� �����������
    $query = "SELECT * FROM $tbl_poll
              WHERE id_catalog = $_GET[id_catalog]";
    $pol = mysql_query($query);
    if(!$pol)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ����������
                               ���������� �� �����������");
    }
    if(mysql_num_rows($pol)) $poll = mysql_fetch_array($pol);
    // ������ ���������� ���������� �������� �������� � ���������.
    $title = $poll['name'];
    $pageinfo = '<p class=help>����� ����� ��������, ��������������� ���
                 ������� ������� ��� �������� �����������.</p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");

    // ���������� ������ � ������������ ���������
    $page_link = 3;
    // ���������� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_poll_answer,
                           "",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link);
  
    // �������� �������
    echo "<a href=anwadd.php?page=$_GET[page]&id_catalog=$_GET[id_catalog]
             title='�������� ������� ������'>
             �������� ������� ������</a><br><br>";
  
    // �������� ���������� ������� ��������
    $answer = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - ������� 
    if(!empty($answer))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>������� ������</td>
          <td width=40>����</td>
          <td width=40>���.</td>
          <td width=100>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($answer); $i++)
      {
        // ���� ������� �������� ��� ��������� (hide='hide'), �������
        // ������ "����������", ���� ��� ������� (hide='show') - "������"
        $colorrow = "";
        $url = "?id_catalog={$answer[$i][id_catalog]}&".
               "id_position={$answer[$i][id_position]}&".
               "page=$_GET[page]";

        // ������� �������
        echo "<tr $colorrow >
                <td>".print_page($answer[$i]['name'])."</td>
                <td align=center>{$answer[$i][hits]}</td>
                <td align=center>{$answer[$i][pos]}</td>
                <td align=center>
                   <a href=anwup.php$url>�����</a><br>
                   <a href=anwedit.php$url
                      title='������������� �������'>�������������</a><br>
                   <a href=# onClick=\"delete_position('anwdel.php$url',".
                     "'�� ������������� ������ ������� �������?');\">�������</a><br>
                   <a href=anwdown.php$url>����</a><br></td>
              </tr>";
      }
      echo "</table><br><br>";
    }
  
    // ������� ������ �� ������ ��������
    echo $obj;
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

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>