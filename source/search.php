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

  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Подключаем функцию навигации
  require_once("utils.navigation.php");
  // Заголовок
  require_once("utils.title.php");
  // Подключаем функцию постраничной навигации
  require_once("utils.pager.php");

  try
  {
    // Подключаем верхний шаблон
    $pagename = "Поиск по сайту";
    $keywords = "Поиск по сайту";
    require_once ("templates/top.php");

    // Заголовок страницы
    echo title($pagename);

    if(empty($_GET['name']))
    {
      echo "<div class=\"main_txt\">Введите фразу для поиска.</div>";
    }      
    else
    {
      // Проверяем введённые данные на предмет SQL-инъекций
      if (!get_magic_quotes_gpc())
      {
        $_GET['name'] = mysql_escape_string($_GET['name']);
      }
    
      $words = preg_split("|[\s]+|",$_GET['name']);
      // Формируем вспомогательный массив
      foreach($words as $line)
      {
        $search_cms[] = "($tbl_paragraph.name RLIKE '".$line."')";
        $search_news[] = "(($tbl_news.name RLIKE '".$line."') OR 
                           ($tbl_news.body RLIKE '".$line."'))";
      }
        
      // Элемент постраничной навигация
      if(empty($_GET['page'])) $page = 1;
      else $page = $_GET['page'];
        
      // Число ссылок в постраничной навигации
      $page_link = 3;
      // Число позиций на странице
      $pnumber = 10;
      // Постраничная навигация
      $first = ($page - 1)*$pnumber;
    
      // Подсчитываем количество найденных позиций $total
      $total = 0;
      $query = "SELECT COUNT($tbl_position.id_position)
                FROM $tbl_paragraph, $tbl_position 
                WHERE ".implode(" AND ", $search_cms)." AND 
                      $tbl_position.hide = 'show' AND
                      $tbl_paragraph.hide = 'show' AND
                      $tbl_position.id_position = 
                      $tbl_paragraph.id_position";
      $tot = mysql_query($query);
      if(!$tot)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при извлечении 
                                 количества позиций");
      }
      $total += mysql_result($tot, 0);
      $query = "SELECT COUNT($tbl_news.id_news)
                FROM $tbl_news
                WHERE ".implode(" AND ", $search_news)." AND 
                      $tbl_news.hide = 'show'";
      $tot = mysql_query($query);
      if(!$tot)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при извлечении 
                                 количества позиций");
      }
      $total += mysql_result($tot, 0);
    
      // Выводим содержимое текущего каталога
      $query = "SELECT $tbl_position.id_position AS id_position,
                       $tbl_position.id_catalog AS id_catalog,
                       $tbl_position.name AS name,
                       'art' AS link
                FROM $tbl_paragraph, $tbl_position
                WHERE ".implode(" AND ", $search_cms)." AND 
                      $tbl_position.hide = 'show' AND
                      $tbl_paragraph.hide = 'show' AND
                      $tbl_position.id_position = 
                      $tbl_paragraph.id_position
                GROUP BY $tbl_position.id_position
                UNION
                SELECT $tbl_news.id_news AS id_position,
                       0,
                       $tbl_news.name AS name,
                       'news' AS link
                FROM $tbl_news
                WHERE ".implode(" AND ", $search_news)." AND 
                      $tbl_news.hide = 'show'
                ORDER BY name
                LIMIT $first, $pnumber";
    
      $pos = mysql_query($query);
      if(!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при формировании 
                                 списка позиций");
      }
      // Если имеется хотя бы одна позиция
      // выводим результирующий список
      if(mysql_num_rows($pos) > 0)
      {
        while($position = mysql_fetch_array($pos))
        {
          if($position['link'] == "art")
          {
            echo "<div class=main_txt><a class=\"main_txt_lnk\" 
                  href=index.php?id_catalog=$position[id_catalog]".
                 "&id_position=$position[id_position]>".
                 "$position[name]</a></div>";
          }
          if($position['link'] == "news")
          {
            echo "<div class=main_txt><a class=\"main_txt_lnk\" 
                  href=news.php?id_news=$position[id_position]>".
                 "$position[name]</a></div>";
          }
        }
        echo "<div class=\"main_txt\">";
        pager($page, 
              $total, 
              $pnumber, 
              $page_link, 
              "&name=".urlencode($_GET['name']));
        echo "</div>";
      }
      else
      {
        echo "<div class=\"main_txt\">По Вашему запросу ничего 
              не найдено. Попробуйте изменить запрос.</div>";
      }
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
