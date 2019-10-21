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
  require_once("config/config.php");
  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ���������� ������� ������ ������ � bbCode
  require_once("dmn/utils/utils.print_page.php");
  // ���������� ��������� 
  require_once("utils.title.php");

  try
  {
    // ����� ��������� �� ��������
    $pnumber = 10;
    // ����� ������ � ������������ ���������
    $page_link = 3;
    // ��������� ������ ������������ ���������
    $obj = new pager_mysql($tbl_guestbook,
                           "WHERE hide = 'show'",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);

    // ���������� ������� ������
    $pagename = "�������� �����";
    $keywords = "�������� �����";
    require_once ("templates/top.php");

    echo title($pagename);

    echo "<p class=main_txt>
           <a href=guestbook_add.php 
              class=main_txt_lnk>�������� ���������</a></p>";

    // �������� ���������� ������� ��������
    $guest = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - �������
    if(!empty($guest))
    {
      echo '<table border="0" 
                   cellpadding="0" 
                   cellspacing="0" 
                   width="100%" 
                   align="left">';
      for($i = 0; $i < count($guest); $i++)
      {
        // ���� ������ ����� - ������� ���
        if(!empty($guest[$i]['city']))
        {
          $city = "&nbsp;(".print_page($guest[$i]['city']).")";
        }
        else $city = "";
        // ��������� ���� � ��������� ��� ������������ �������
        list($date, $time) = explode(" ", $guest[$i]['putdate']);
        list($year, $month, $day) = explode("-", $date);
        $date = "$day.$month.$year ".substr($time, 0, 5);
        // ��������� ���� �� �������
        echo '<tr bgcolor="#C5D7DB" class=main_txt>
                <td rowspan="1" height="20">
                  <nobr><p class=ptdg><b>'.
                    print_page($guest[$i]['name']).
                  '</b>'.$city.'</nobr></td>
                <td width="100%" align="right">
                  <nobr><p class=help>��: <b>'.$date.'</b>&nbsp;</nobr>
                </td>
              </tr>';
        echo '<tr>
               <td colspan=2 bgcolor="gray" height="1"><img 
                 src="images/pic.gif" 
                 border="0" 
                 width="1" 
                 height="1" 
                 alt=""></td>
             </tr>';
        echo '<tr valign="top" class=main_txt>
                <td colspan="2"><p class=textgbook>'.
                  nl2br(print_page($guest[$i]['msg'])).'</p>';
        if(!empty($guest[$i]['answer']) && $guest[$i]['answer'] != '-')
        {
          // ���� ������� ����� �������������� - ������� ���
          echo '<p class=panswer style="color: grey">
                  <b>�������������: '.nl2br(print_page($guest[$i]['answer'])).'</b>
                </p>';
        }
        echo "</td></tr>";
      }
      echo "</table>";
      // ������� ������ �� ������ ��������
      echo '<br clear="all">';
      echo "<p class=main_txt>";
      echo $obj;
      echo "</p>";
    }

    //���������� ������ ������
    require_once ("templates/bottom.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>
