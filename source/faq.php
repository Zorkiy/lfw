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
    $obj = new pager_mysql($tbl_faq,
                           "",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link);

    // ���������� ������� ������
    $pagename = "������� � ������";
    $keywords = "������� � ������";
    require_once ("templates/top.php");

    // �������� ���������� ������� ��������
    $faq = $obj->get_page();
    // ���� ������� ���� �� ���� ������ - �������
    if(!empty($faq))
    {
      echo title($pagename);

      for($i = 0; $i < count($faq); $i++)
      {
        echo "<div class=main_txt><b>".nl2br(print_page($faq[$i]['question']))."</b></div>";
        echo "<div class=main_txt>".nl2br(print_page($faq[$i]['answer']))."</div>";
      }
      // ������� ������ �� ������ ��������
      echo "<div class=main_txt>";
      echo $obj;
      echo "</div>";
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
