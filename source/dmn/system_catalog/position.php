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
  require_once("../../config/config.php");
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Навигационное меню
  require_once("../utils/utils.navigation.php");
  // Подключаем блок отображения текста в окне браузера
  require_once("../utils/utils.print_page.php");

  $title = $titlepage = 'Администрирование каталога продукции';  
  $pageinfo = '<p class=help>Здесь осуществляется добавление позиций, 
               удаление или редактирование уже существующих позиций</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  $_GET['id_catalog'] = intval($_GET['id_catalog']);

  try
  {
    // Извлекаем параметры текущего каталога
    $query = "SELECT * FROM $tbl_cat_catalog
              WHERE id_catalog = $_GET[id_catalog]
              LIMIT 1";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка извлечения
                               параметров каталога");
    }
    $catalog = mysql_fetch_array($cat);
    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellspacing="0" cellspacing="0" border=0>
          <tr valign="top"><td height="25"><p>';
    echo "<a class=menu href=index.php?".
        "id_parent=0&page=$_GET[page]>Корневое меню</a>-&gt;".
             menu_navigation($_GET['id_catalog'], "", $tbl_cat_catalog).
         "<a class=menu href=posadd.php?id_catalog=$_GET[id_catalog]".
            "&page=$_GET[page]>Добавить позицию</a>";
    echo "&nbsp;&nbsp;&nbsp;<a href=catcsvimport.php?id_catalog=$_GET[id_catalog]".
         "&page=$_GET[page]>Импортировать из CSV-формата</a>";
    echo "</td></tr></table>";

    // Число ссылок в постраничной навигации
    $page_link = 3;
    // Число позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_cat_position,
                           "WHERE id_catalog=$_GET[id_catalog]",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link,
                           "&id_catalog=$_GET[id_catalog]");

    // Получаем содержимое текущей страницы
    $position = $obj->get_page();

    // Если имеется хотя бы одна запись - выводим
    if(!empty($position))
    {
      // Выводим заголовок таблицы
      echo '<table width="100%" 
                   class="table" 
                   border="0" 
                   cellpadding="0" 
                   cellspacing="0">
              <tr class="header" align="center">
                <td width=150>район/адрес</td>
                <td>примечание</td>
                <td width=100>Действия</td>
              </tr>';
      for($i = 0; $i < count($position); $i++)
      {
        $url = "id_position={$position[$i][id_position]}&".
               "id_catalog={$_GET['id_catalog']}&".
               "page={$_GET[page]}";
        // Выясняем скрыта позиция или нет
        if($position[$i]['hide'] == 'hide')
        {
          $strhide = "<a href=posshow.php?$url>Отобразить</a>";
          $style = " class=hiddenrow ";
        }
        else
        {
          $strhide = "<a href=poshide.php?$url>Скрыть</a>";
          $style = "";
        }

        // Определяем район
        $distr = "Канавинский";
        switch ($position[$i]['district'])
        {
          case 'kanavinskii':
            $distr = "Канавинский";
            break;
          case 'nizhegorodskii':
            $distr = "Нижегородский";
            break;
          case 'sovetskii':
            $distr = "Советский";
            break;
          case 'priokskii':
            $distr = "Приокский";
            break;
          case 'moskovskii':
            $distr = "Московский";
            break;
          case 'avtozavodskii':
            $distr = "Автозаводский";
            break;
          case 'leninskii':
            $distr = "Ленинский";
            break;
          case 'sormovskii':
            $distr = "Сормовский";
            break;
        }
        
        // Выводим позиции
        echo "<tr $style>
                <td>
                  <a href=# onclick=\"show_detail('posdetail.php".
                    "?id_position={$position[$i][id_position]}',400,350);".
                    " return false\"
                    title=\"Подробнее\">
                    $distr<br>
                    {$position[$i][address]}
                  </a>
                </td>
                <td>{$position[$i][note]}</td>";
        echo "  <td>
                  <a href=posup.php?$url>Вверх</a><br>
                  $strhide<br>
                  <a href=posedit.php?$url>Редактировать</a><br>
                  <a href=# onClick=\"delete_position('posdel.php?$url',".
                  "'Вы действительно хотите удалить позицию?');\">Удалить</a><br>
                  <a href=posdown.php?$url>Вниз</a>
                </td>
             </tr>";
      }
      echo "</table><br>";
      // Выводим ссылки на другие страницы
      echo $obj;
    }
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>
<script language='JavaScript1.1' type='text/javascript'>
<!--
  function show_detail(url,width,height)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a=(screen.height-vidWindowHeight)/5;
    b=(screen.width-vidWindowWidth)/2;
    features = "top=" + a + ",left=" + b + 
               ",width=" + vidWindowWidth + 
               ",height=" + vidWindowHeight + 
               ",toolbar=no,menubar=no,location=no" +
               ",directories=no,scrollbars=no,resizable=no";
    window.open(url,'',features,true);
  }
//-->
</script>