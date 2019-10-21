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
    // ���� GET-�������� id_news �� ������� - ������� 
    // ������ ��������� ���������
    if(empty($_GET['id_news']))
    {
      // ��������� �������� page, ������������ SQL-��������
      $_GET['page'] = intval($_GET['page']);
  
      // ����� ��������� �� ��������
      $pnumber = 10;
      // ����� ������ � ������������ ���������
      $page_link = 3;
      // ��������� ������ ������������ ���������
      $obj = new pager_mysql($tbl_news,
                             "",
                             "ORDER BY putdate DESC",
                             $pnumber,
                             $page_link);
  
      // ���������� ������� ������
      $pagename = "�������";
      $keywords = "�������";
      require_once ("templates/top.php");
  
      // �������� ���������� ������� ��������
      $news = $obj->get_page();
      // ���� ������� ���� �� ���� ������ - �������
      if(!empty($news))
      {
        echo title($pagename);
  
        $patt = array("[b]", "[/b]", "[i]", "[/i]");
        $repl = array("", "", "", "");
        $pattern_url = "|\[url[^\]]*\]|";
        $pattern_b_url = "|\[/url[^\]]*\]|";
        for($i = 0; $i < count($news); $i++)
        {
          if(strlen($news[$i]['body']) > 100)
          {
            $news[$i]['body'] = substr($news[$i]['body'], 0, 100)."...";
            $news[$i]['body'] = str_replace($patt, 
                                            $repl, $news[$i]['body']);
            $news[$i]['body'] = preg_replace($pattern_url, 
                                             "", $news[$i]['body']);
            $news[$i]['body'] = preg_replace($pattern_b_url, 
                                             "", $news[$i]['body']);
          }
    
          echo "<div class=main_txt><b>".$news[$i]['putdate']." | ".
                print_page($news[$i]['name'])."</b>
                <br>".print_page($news[$i]['body'])."
                <a href=\"news.php?id_news=".$news[$i]['id_news']."\" >
                   ���������
                 </a>
                <br></div>";
        }
        // ������� ������ �� ������ ��������
        echo "<div class=main_txt>";
        echo $obj;
        echo "</div>";
      }
    }
    // ���� GET-�������� id_news ������� - ������� ������ 
    // ������ ���������� ���������
    else
    {
      // ���������, �������� �� �������� id_news ������
      $_GET['id_news'] = intval($_GET['id_news']); 
      // ������� ��������� ��������� ���������
      $query = "SELECT id_news,
                       name,
                       body,
                       DATE_FORMAT(putdate,'%d.%m.%Y') as putdate_format,
                       url,
                       urltext,
                       urlpict,
                       hide
                FROM $tbl_news
                WHERE hide = 'show' AND
                      id_news = $_GET[id_news]";
      $res = mysql_query($query);
      if(!$res)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ���������� 
                                 ������� �������");
      }
      $news = mysql_fetch_array($res);
  
      // ���������� ������� ������
      $pagename = $news['name'];
      $keywords = "�������";
      require_once ("templates/top.php");
     
      echo title($pagename);
  
      $url_pict = "";
      if ($news['urlpict'] != '' && $news['urlpict'] != '-')
      {
        $url_pict = "<img src=".print_page($news['urlpict']).">";
      }
  
      $news_url = "";
      if (!empty($news['url']))
      {
        if(!preg_match("|^http://|i",$news['url']))
        {
          $news['url'] = "http://{$news[url]}";
        }
        $news_url = "<br><b>������:</b> 
                     <a href='".print_page($news['url']).">".
                                print_page($news['urltext'])."</a>";
        if(empty($news['urltext']))
        {
          $news_url = "<br><b>������:</b> 
                       <a href='".print_page($news['url'])."'>".
                                  print_page($news['url'])."</a>";
        }
      }
  
  
      echo "<div class=main_txt><b>".$news['putdate_format']." | ".
            print_page($news['name'])."</b>
            <br> 
            $url_pict ".nl2br(print_page($news['body']))."
            <br>$news_url
            </div>";
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
