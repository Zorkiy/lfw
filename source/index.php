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

  // Инициируем сессию
  session_start();
  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Заголовок
  require_once("utils.title.php");

  // Определяем параметр для статей
  define("ARTICLE", 1);

  try
  {
    // Если не передан параметр id_position - выводим список статей
    if(empty($_GET['id_position']))
    {
      // Проверяем GET-параметры, предотвращая SQL-инъекцию
      $_GET['page']       = intval($_GET['page']);
      $_GET['id_catalog'] = intval($_GET['id_catalog']);
  
      if(empty($_GET['id_catalog']))
      {
        // Запрашиваем параметры текущего раздела
        $query = "SELECT * FROM $tbl_catalog 
                  WHERE id_catalog = $_GET[id_catalog]";
        $cat = mysql_query($query);
        if(!$cat)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при извлечении 
                                   параметров текущего раздела");
        }
        $catalog = mysql_fetch_array($cat);
      }
  
      //Подключаем верхний шаблон
      if(empty($catalog['name'])) $pagename = $catalog['name'];
      else $pagename = "Статьи";
      if(empty($catalog['keywords'])) $keywords = $catalog['keywords'];
      else $pagename = "Ключевые слова";
  
      // Запрашиваем подразделы текущего раздела
      $query = "SELECT * FROM $tbl_catalog
                WHERE hide = 'show' AND id_parent = $_GET[id_catalog]
                ORDER BY pos";
      $sub = mysql_query($query);
      if (!$sub)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении к 
                                 блоку статей");
      }
      if(mysql_num_rows($sub))
      {
        // Верхний шаблон
        require_once ("templates/top.php");
        // Название
        echo title($pagename);
        echo "<div class=\"main_txt\">";
        while($subcatalog = mysql_fetch_array($sub))
        {
          echo "<a href=\"".$_SERVER['PHP_SELF']."?id_catalog=".$subcatalog['id_catalog']."\" 
                       class=\"menu_lnk\"><h3>".
                       htmlspecialchars($subcatalog['name'])."</a></h3>";
        }
        echo "</div>";
      }
  
      // Запрашиваем статьи текущего раздела
      $query = "SELECT * FROM $tbl_position
                WHERE hide = 'show' AND id_catalog = ".$_GET['id_catalog']."
                ORDER BY pos";
      $pos = mysql_query($query);
      if (!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении к 
                                 блоку статей");
      }
      if(mysql_num_rows($pos) > 0)
      {
        // Статься одна и подразделов нет
        if(mysql_num_rows($pos) == 1 && !mysql_num_rows($sub))
        {
          // Получаем параметры текущей статьи
          $position = mysql_fetch_array($pos);
          // Если статья на самом деле является ссылкой - осуществляем редирект
          if($position['url'] != 'article')
          {
            echo "<HTML><HEAD>
                  <META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'>
                  </HEAD></HTML>";
            exit();
          }
          // Статья одна и нет подразделов - выводим содержимое статьи
          $_GET['id_position'] = $position['id_position'];
          // Название и ключевые слова
          $pagename = $position['name'];
          if(empty($pagename)) $pagename = "БИПСИ";
          $_GET['id_catalog'] = $position['id_catalog'];
          $keywords = $position['keywords'];
          // Верхний шаблон
          require_once ("templates/top.php");
          // Название
          echo title($pagename);
          require_once("article_print.php");
        }
        // Статей несколько или имеются также подразделы
        else
        {
          echo "<div class=\"main_txt\">";
          while($position = mysql_fetch_array($pos))
          {
            if($position['url'] != 'article')
            {
              echo "<a href=\"".htmlspecialchars($position['url'])."\" 
                        class=\"main_txt_lnk\">
                     ".htmlspecialchars($position['name'])."</a><br>";
            }
            else
            {
              echo "<a href=\"$_SERVER[PHP_SELF]?id_catalog=$_GET[id_catalog]&".
                   "id_position=$position[id_position]\" 
                     class=\"main_txt_lnk\">".htmlspecialchars($position['name'])."</a><br>";
            }
          }
          echo "</div>";
        }
      }
    }
    else
    {
      // Проверяем GET-параметры, предотвращая SQL-инъекцию
      $_GET['id_position'] = intval($_GET['id_position']);
      // Получаем параметры текущей статьи
      $query = "SELECT * FROM $tbl_position
                WHERE hide = 'show' AND 
                      id_position = $_GET[id_position]";
      $pos = mysql_query($query);
      if (!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении к 
                                 блоку статей");
      }
      if(mysql_num_rows($pos))
      {
        $position = mysql_fetch_array($pos);
        // Если статья на самом деле является ссылкой - осуществляем редирект
        if($position['url'] != 'article')
        {
          echo "<HTML><HEAD>
                <META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'>
                </HEAD></HTML>";
          exit();
        }
        //Подключаем верхний шаблон
        $pagename = $position['name'];
        if(empty($pagename)) $pagename = "БИПСИ";
        $_GET['id_catalog'] = $position['id_catalog'];
        $keywords = $position['keywords'];
        require_once ("templates/top.php");
  
        // Название
        echo title($pagename);
        // Выводим статью
        require_once("article_print.php");
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