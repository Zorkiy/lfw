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

  try
  {
    // Проверяем GET-параметры, предотвращая SQL-инъекцию
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    $_GET['page'] = intval($_GET['page']);
  
    // Запрашиваем параметры текущего раздела
    $query = "SELECT * FROM $tbl_cat_catalog 
              WHERE hide = 'show' AND 
                    id_catalog = ".$_GET['id_catalog']."
              ORDER BY pos";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при извлечении 
                               параметров текущего раздела");
    }
    if(mysql_num_rows($cat))
    {
      $current = mysql_fetch_array($cat);
    }
    // Подключаем верхний шаблон
    $pagename = "Каталог";
    $keywords = "Каталог";
    require_once ("templates/top.php");

    // Заголовок страницы
    echo title($pagename);

    if($_GET['id_catalog'] != 0) 
    {
      echo "<div><b>
              <a href=\"catalog.php\" class=\"main_ttl\">Каталог</a>".
              menu_navigation($_GET['id_catalog'], "", $tbl_cat_catalog).
           "</b></div>";
      echo "<br>";
    }
    // Проверяем, нет ли подкаталогов, если есть - выводим
    $query = "SELECT * FROM $tbl_cat_catalog 
              WHERE hide = 'show' AND 
                    id_parent = ".$_GET['id_catalog']."
              ORDER BY pos";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при извлечении 
                               параметров текущего раздела");
    }
    if(mysql_num_rows($cat))
    {
      echo '<table width="100%" 
                   border="0" 
                   cellspacing="0" 
                   cellpadding="0">';
      $i = 0;
      while($catalog = mysql_fetch_array($cat))
      {
        echo '<tr>
              <td align="right">
                <td width="100%" class="table1_txt">
                  <a href="catalog.php?id_catalog='.$catalog['id_catalog'].'" 
                     class="main_ttl">'.$catalog['name'].'</a></td>
              </tr>';
      }
      echo '</table>';
    }

    if($_GET['id_catalog'] != 0) 
    {
      // Подключаем список товарных позиций
      require_once("catalog_position.php");
    }

    // Подключаем нижний шаблон
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