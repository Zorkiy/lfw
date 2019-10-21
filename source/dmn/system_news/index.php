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
  $title = '���������� ������ "���� ��������"';
  $pageinfo = '<p class=help>����� ����� ��������
               ��������� ����, ��������������� ���
               ������� ��� ������������ ����.</p>';

  // �������� ��������� ��������
  require_once("../utils/top.php");

  try
  {
    // ���������� ������ � ������������ ���������
    $page_link = 3;
    // ���������� ������� �� ��������
    $pnumber = 10;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_news,
                           "",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);
  
    // �������� ����
    echo "<a href=newsadd.php?page=$_GET[page]
             title='�������� ��������� ����'>
             �������� ��������� ����</a><br><br>";
  
    // �������� ���������� ������� ��������
    $news = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - ������� 
    if(!empty($news))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td width=200>����</td>
          <td width=60%>�������</td>
          <td width=40>����-�</td>
          <td>��������</td>
        </tr>
      <?php
      for($i = 0; $i < count($news); $i++)
      {
        // ���� ������� �������� ��� ��������� (hide='hide'), �������
        // ������ "����������", ���� ��� ������� (hide='show') - "������"
        $colorrow = "";
        $url = "?id_news={$news[$i][id_news]}&page=$page";
        if($news[$i]['hide'] == 'show')
        {
          $showhide = "<a href=newshide.php$url 
                          title='������ ������� � ����� ��������'>
                       ������</a>";
        }
        else
        {
          $showhide = "<a href=newsshow.php$url 
                          title='���������� ������� � ����� ��������'>
                       ����������</a>";
          $colorrow = "class='hiddenrow'";
        }
        // ��������� ������� �����������
        if($news[$i]['urlpict'] != '' && 
           $news[$i]['urlpict'] != '-' && 
           is_file("../../".$news[$i]['urlpict']))
        {
        $url_pict = "<b><a href=../../{$news[$i][urlpict]}>����</a></b>";
        }
        else $url_pict = "���";
        
        $news_url="";
        if (!empty($news[$i]['url']))
        {
          if(!preg_match("|^http://|i",$news[$i]['url']))
          {
            $news[$i]['url'] = "http://{$news[$i][url]}";
          }
          $news_url = "<br><b>������:</b> 
                       <a href='{$news[$i][url]}'>
                          {$news[$i][urltext]}</a>";
          if(empty($news[$i]['urltext']))
          {
            $news_url = "<br><b>������:</b> 
                         <a href='{$news[$i][url]}'>
                            {$news[$i][url]}</a>";
          }
        }

        // ����������� ���� �� ������� MySQL YYYY-MM-DD hh:mm:ss
        // � ������ DD.MM.YYYY hh:mm:ss
        list($date, $time) = explode(" ", $news[$i]['putdate']);
        list($year, $month, $day) = explode("-", $date);
        $news[$i]['putdate'] = "$day.$month.$year $time";

        // ������� �������
        echo "<tr $colorrow >
                <td><p align=center>{$news[$i][putdate]}</td>
                <td>
                  <a title='������������� ����� �������' 
                     href=newsedit.php$url>{$news[$i][name]}</a><br>
                 ".nl2br(print_page($news[$i]['body']))." $news_url </td>
                <td align=center>$url_pict</td>
                <td align=center>$showhide<br>
                   <a href=# onClick=\"delete_position('newsdel.php$url',".
                                      "'�� ������������� ������ �������".
                                      " ��������� ���������?');\" 
                      title='������� �������'>�������</a><br>
                   <a href=newsedit.php$url} 
               title='������������� ����� �������'>�������������</a></td>
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