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
  $title = '���������� ������ "�������� �����"';
  $pageinfo = '<p class=help>����� ����� ��������������� ���
               ������� ��������� � �������� �����.</p>';

  // �������� ��������� ��������
  require_once("../utils/top.php");

  try
  {
    // ���������� ������ � ������������ ���������
    $page_link = 3;
    // ���������� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_guestbook,
                           "",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);
  
    // �������� ���������� ������� ��������
    $guest = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - ������� 
    if(!empty($guest))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>���������</td>
          <td>�����</td>
          <td>����</td>
          <td>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($guest); $i++)
      {
        // ���� ������� �������� ��� ��������� (hide='hide'), �������
        // ������ "����������", ���� ��� ������� (hide='show') - "������"
        $colorrow = "";
        $url = "?id_position={$guest[$i][id_position]}&page=$_GET[page]";
        if($guest[$i]['hide'] == 'show')
        {
          $showhide = "<a href=guesthide.php$url 
                          title='������ �������'>
                       ������</a>";
        }
        else
        {
          $showhide = "<a href=guestshow.php$url 
                          title='���������� �������'>
                       ����������</a>";
          $colorrow = "class='hiddenrow'";
        }

        // ���� ������ ����� - ������� ���
        if(!empty($guest[$i]['city']))
        $city = "(".print_page($guest[$i]['city']).")";
        else $city = "";
        // ������� �������
        echo "<tr $colorrow >
                <td><b>".print_page($guest[$i]['name']).
                    " $city</b><br>".
                    nl2br(print_page($guest[$i]['msg']))."</td>
                <td>".nl2br(print_page($guest[$i]['answer']))."&nbsp</td>
                <td align=center>{$guest[$i][putdate]}</td>
                <td align=center>
                   $showhide<br>
                   <a href=guestedit.php$url
                      title='������������� �������'>�������������</a><br>
                   <a href=# onClick=\"delete_position('guestdel.php$url',".
                   "'�� ������������� ������ ������� �������?');\">�������</a></td>
              </tr>";
      }
      echo "</table><br><br>";
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