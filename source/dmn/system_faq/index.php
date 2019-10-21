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
  $title = '���������� ������ "������� � ������"';
  $pageinfo = '<p class=help>����� ����� ��������
               ���� "������-�����", ��������������� ���
               ������� ��� ������������.</p>';

  // �������� ��������� ��������
  require_once("../utils/top.php");

  try
  {
    // ���������� ������ � ������������ ���������
    $page_link = 3;
    // ���������� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_faq,
                           "",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link);
  
    // �������� �������
    echo "<a href=faqadd.php?page=$_GET[page]
             title='�������� ���� ������-�����'>
             �������� ���� ������-�����</a><br><br>";
  
    // �������� ���������� ������� ��������
    $faq = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - ������� 
    if(!empty($faq))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>������</td>
          <td>�����</td>
          <td width=40>���.</td>
          <td>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($faq); $i++)
      {
        // ���� ������� �������� ��� ��������� (hide='hide'), �������
        // ������ "����������", ���� ��� ������� (hide='show') - "������"
        $colorrow = "";
        $url = "?id_position={$faq[$i][id_position]}&page=$_GET[page]";
        if($faq[$i]['hide'] == 'show')
        {
          $showhide = "<a href=faqhide.php$url 
                          title='������ �������'>
                       ������</a>";
        }
        else
        {
          $showhide = "<a href=faqshow.php$url 
                          title='���������� �������'>
                       ����������</a>";
          $colorrow = "class='hiddenrow'";
        }

        // ������� �������
        echo "<tr $colorrow >
                <td>".nl2br(print_page($faq[$i]['question']))."</td>
                <td>".nl2br(print_page($faq[$i]['answer']))."</td>
                <td align=center>{$faq[$i][pos]}</td>
                <td align=center>
                   <a href=faqup.php$url>�����</a><br>
                   $showhide<br>
                   <a href=faqedit.php$url
                      title='������������� �������'>�������������</a><br>
                   <a href=# onClick=\"delete_position('faqdel.php$url',".
                    "'�� ������������� ������ ������� �������?');\" 
                      title='������� �������'>�������</a><br>
                   <a href=faqdown.php$url>����</a><br></td>
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