<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) Кузнецов М.В., Симдянов И.В.
  // PHP. Практика создания Web-сайтов
  // IT-студия SoftTime 
  // http://www.softtime.ru   - портал по Web-программированию
  // http://www.softtime.biz  - коммерческие услуги
  // http://www.softtime.mobi - мобильные проекты
  // http://www.softtime.org  - некоммерческие проекты
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Подключаем функцию вывода текста с bbCode
  require_once("dmn/utils/utils.print_page.php");
  // Подключаем заголовок 
  require_once("utils.title.php");

  try
  {
    // Если GET-параметр id_news не передан - выводим 
    // список новостных сообщений
    if(empty($_GET['id_news']))
    {
      // Проверяем параметр page, предотвращая SQL-инъекцию
      $_GET['page'] = intval($_GET['page']);
  
      // Число сообщений на странице
      $pnumber = 10;
      // Число ссылок в постраничной навигации
      $page_link = 3;
      // Объявляем объект постраничной навигации
      $obj = new pager_mysql($tbl_news,
                             "",
                             "ORDER BY putdate DESC",
                             $pnumber,
                             $page_link);
  
      // Подключаем верхний шаблон
      $pagename = "НОВОСТИ";
      $keywords = "новости";
      require_once ("templates/top.php");
  
      // Получаем содержимое текущей страницы
      $news = $obj->get_page();
      // Если имеется хотя бы одна запись - выводим
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
                   подробнее
                 </a>
                <br></div>";
        }
        // Выводим ссылки на другие страницы
        echo "<div class=main_txt>";
        echo $obj;
        echo "</div>";
      }
    }
    // Если GET-параметр id_news передан - выводим полную 
    // версию новостного сообщения
    else
    {
      // Проверяем, является ли параметр id_news числом
      $_GET['id_news'] = intval($_GET['id_news']); 
      // Выводим выбранное новостное сообщение
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
                                "Ошибка при извлечении 
                                 текущей позиции");
      }
      $news = mysql_fetch_array($res);
  
      // Подключаем верхний шаблон
      $pagename = $news['name'];
      $keywords = "новости";
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
        $news_url = "<br><b>Ссылка:</b> 
                     <a href='".print_page($news['url']).">".
                                print_page($news['urltext'])."</a>";
        if(empty($news['urltext']))
        {
          $news_url = "<br><b>Ссылка:</b> 
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

    //Подключаем нижний шаблон
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
