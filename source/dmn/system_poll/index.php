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

  // ������ ���������� ���������� �������� �������� � ���������.
  $title = '���������� ������ "�����������"';
  $pageinfo = '<p class=help>����� ����� ��������, ��������������� 
  ��� ������� ���� �����������</p>';
  // �������� ��������� ��������
  require_once("../utils/top.php");

  try
  {
    // ���������� ������ � ������������ ���������
    $page_link = 3;
    // ���������� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_poll,
                           "",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);
  
    // �������� �������
    echo "<a href=polladd.php?page=$_GET[page]
             title='�������� ����� �����'>
             �������� ����� �����</a><br><br>";
  
    // �������� ���������� ������� ��������
    $poll = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - ������� 
    if(!empty($poll))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>������</td>
          <td width=60>������</td>
          <td>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($poll); $i++)
      {
        // ���� ������� �������� ��� ��������� (hide='hide'), �������
        // ������ "����������", ���� ��� ������� (hide='show') - "������"
        $colorrow = "";
        $url = "?id_catalog={$poll[$i][id_catalog]}&page=$_GET[page]";
        if($poll[$i]['hide'] == 'show')
        {
          $showhide = "<a href=pollhide.php$url 
                          title='������ ����'>
                       ������</a>";
        }
        else
        {
          $showhide = "<a href=pollshow.php$url 
                          title='���������� ����'>
                       ����������</a>";
          $colorrow = "class='hiddenrow'";
        }
        // �������� ������ �������
        if($poll[$i]['archive'] == 'archive') $status = "��������";
        else $status = "��������";


        // ������� �������
        echo "<tr $colorrow >
                <td><a href=answers.php?id_catalog={$poll[$i][id_catalog]}&".
                       "page=$_GET[page]>".print_page($poll[$i]['name'])."</a></td>
                <td align=center>$status</td>
                <td align=center>
                   $showhide<br>
                   <a href=polledit.php$url
                      title='������������� �������'>�������������</a><br>
                   <a href=# onClick=\"delete_position('polldel.php$url',".
                   "'�� ������������� ������ ������� ����?');\">�������</a></td>
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