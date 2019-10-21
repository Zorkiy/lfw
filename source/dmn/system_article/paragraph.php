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
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Навигационное меню
  require_once("../utils/utils.navigation.php");
  // Обработка текста перед выводом
  require_once("../utils/utils.print_page.php");

  // Защита от SQL-инъекции
  $_GET['id_position'] = intval($_GET['id_position']);
  $_GET['id_catalog']  = intval($_GET['id_catalog']);

  try
  {
    // Извлекаем информацию об каталоге
    $query = "SELECT * FROM $tbl_catalog
              WHERE id_catalog = $_GET[id_catalog] 
              LIMIT 1";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении 
                               к каталогу");
    }
    $catalog = mysql_fetch_array($cat);
  
    // Извлекаем информацию об позиции
    $query = "SELECT * FROM $tbl_position
              WHERE id_position = $_GET[id_position]";
    $pos = mysql_query($query);
    if(!$pos)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении 
                               к позиции");
    }
    $position = mysql_fetch_array($pos);
  
    $title = $titlepage = 'Позиция ('.$catalog['name'].
                          ' - '.$position['name'].')';
    $pageinfo = '<p class=help>Здесь осуществляется 
                 администрирование позиции ('.$catalog['name'].
                 ' - '.$position['name'].'). 
                 Параграф может представлять собой как обычный 
                 текстовый абзац, так и заголовок. Возможно 
                 использование шести заголовков
                 (H1, H2, H3, H4, H5, H6), H1 - самый крупный 
                 заголовок, применяемый обычно для названия 
                 страниц, далее заголовки уменьшаются в 
                 размере, т.е. H6 - это самый мелкий заголовок.</p>';


    // Число ссылок в постраничной навигации
    $page_link = 3;
    // Число позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_paragraph,
                           "WHERE id_position = $_GET[id_position] AND 
                                  id_catalog=$_GET[id_catalog]",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link,
                           "&id_position=$_GET[id_position]&".
                           "id_catalog=$_GET[id_catalog]");

    // Получаем содержимое текущей страницы
    $paragraph = $obj->get_page();

    // Включаем заголовок страницы
    require_once("../utils/top.php");

    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    if($_GET['id_catalog'] != 0)
    {
      echo '<table cellspacing="0" cellspacing="0" border=0><tr>
      <tr valign="top"><td height="25"><p>';
      echo "<a class=menu href=index.php?id_parent=0>
               Корневой каталог</a>-&gt;".
               menu_navigation($_GET['id_catalog'], 
                               "", 
                               $tbl_catalog).$position['name'];
      echo "</td></tr></table>";
    }

    // Добавить параграф
    echo "<form action=paradd.php>";
    echo "<input class=button type=submit value='Добавить параграф'><br><br>";
    echo "<input type=hidden name=page value='$_GET[page]'><br><br>";
      // Выводим заголовок таблицы каталогов
      echo "<input type=hidden 
                   name=id_catalog 
                   value=$_GET[id_catalog]>";
      echo "<input type=hidden 
                   name=id_position 
                   value=$_GET[id_position]>";
      echo '<table width="100%" 
                   class="table" 
                   border="0" 
                   cellpadding="0" 
                   cellspacing="0">
              <tr class="header" align="center">
                <td width=20 align=center>
                   <input type=radio name=pos value=-1 checked></td>
                <td align=center>Содержимое</td>
                <td width=100 align=center>Изображения<br> и файлы</td>
                <td width=100 align=center>Тип</td>
                <td width=20 align=center>Поз.</td>
                <td width=50>Действия</td>
              </tr>';
  
    if(!empty($paragraph))
    {
      for($i = 0; $i < count($paragraph); $i++)
      {
        $url = "id_paragraph={$paragraph[$i][id_paragraph]}".
               "&id_position=$_GET[id_position]&".
               "id_catalog=$_GET[id_catalog]".
               "&page=$_GET[page]";
        // Выясняем тип параграфа
        $type = "Параграф";
        switch($paragraph[$i]['type'])
        {
          case 'text':
            $type = "Параграф";
            break;
          case 'title_h1':
            $type = "Заголовок H1";
            break;
          case 'title_h2':
            $type = "Заголовок H2";
            break;
          case 'title_h3':
            $type = "Заголовок H3";
            break;
          case 'title_h4':
            $type = "Заголовок H4";
            break;
          case 'title_h5':
            $type = "Заголовок H5";
            break;
          case 'title_h6':
            $type = "Заголовок H6";
            break;
          case 'list':
            $type = "Список";
            break;
        }
        // Выясняем тип выравнивания параграфа
        $align = "";
        switch($paragraph[$i]['align'])
        {
          case 'left':
            $align = "align=left";
            break;
          case 'center':
            $align = "align=center";
            break;
          case 'right':
            $align = "align=right";
            break;
        }
        // Выясняем скрыта позиция или нет
        if($paragraph[$i]['hide'] == 'hide') 
        {
          $strhide = "<a href=parshow.php?$url>Отобразить</a>";
          $style=" class=hiddenrow ";
        } 
        else
        {
          $strhide = "<a href=parhide.php?$url>Скрыть</a>";
          $style="";
        }
        // Вычисляем сколько изображений у данной позиции
        $query = "SELECT COUNT(*) FROM $tbl_paragraph_image
                  WHERE id_paragraph = {$paragraph[$i][id_paragraph]} AND
                        id_position = $_GET[id_position] AND
                        id_catalog = $_GET[id_catalog]";
        $tot = mysql_query($query);
        if(!$tot)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при подсчёте 
                                   числа изображений");
        }
        $total_image = mysql_result($tot, 0);
        if($total_image) $print_image = " ($total_image)";
        else $print_image = "";
        
        echo "<tr $style>
              <td align=center>
                <input type=radio name=pos value=".$paragraph[$i]['pos'].">
              </td>
              <td><p $align>".
                 nl2br(print_page($paragraph[$i]['name'])).
             "</p></td>
              <td align=center>
                <a href=image.php?$url>Изображения$print_image</a>
              </td>
              <td align=center>".print_page($type)."</td>
              <td align=center>".$paragraph[$i]['pos']."</td>
              <td>
              <a href=parup.php?$url>Вверх</a><br>
              $strhide<br>
              <a href=paredit.php?$url>Редактировать</a><br>
              <a href=# onClick=\"delete_position('pardel.php?$url',".
              "'Вы действительно хотите удалить параграф');\">Удалить</a><br>
              <a href=pardown.php?$url>Вниз</a></td>
            </tr>";
      }
      echo "</table><br><br>";
    }
    echo "</form>";
    // Выводим ссылки на другие страницы
    echo $obj;
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>