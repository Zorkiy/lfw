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

  $title = $titlepage = 'Галерея';  
  $pageinfo = '<p class=help>Здесь осуществляется управление
                             галереями сайта</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  try
  {
    // Ссылка для добавления галереи
    echo "<a class=menu href=catadd.php>Добавить галерею</a>&nbsp;&nbsp;
          <a class=menu href=settings.php>Настройки</a><br><br>";
  
    // Выводим список каталогов
    $query = "SELECT * FROM $tbl_photo_catalog
              ORDER BY pos";
      
    $ctg = mysql_query($query);
    if(!$ctg)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении
                               к каталогу");
    }
    if(mysql_num_rows($ctg)>0)
    {
      // Выводим заголовок таблицы каталогов
      echo '<table width="100%" 
                   class="table" 
                   border="0" 
                   cellpadding="0" 
                   cellspacing="0">
                <tr class="header" align="center">
                  <td align=center>Название</td>
                  <td align=center>Описание</td>
                  <td width=50 align=center>Действия</td>
                </tr>';
      while($catalog = mysql_fetch_array($ctg))
      {
        $url = "id_catalog=$catalog[id_catalog]";
        // Выясняем скрыт каталог или нет
        if($catalog['hide'] == 'hide') {
          $strhide = "<a href=catshow.php?$url>Отобразить</a>";
          $style=" class=hiddenrow ";
        } 
        else
        {
          $strhide = "<a href=cathide.php?$url>Скрыть</a>";
          $style="";
        }
        // Извлекаем количество фотографий в разделе
        $query = "SELECT COUNT(*) FROM $tbl_photo_position
                  WHERE id_catalog = $catalog[id_catalog]";
        $cnt = mysql_query($query);
        if(!$cnt)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка извлечения к-ва
                                   изображений");
        }
        $total = mysql_result($cnt, 0);
        if($total) $total = "&nbsp;($total)";
        else $total = "";


        // Выводим список каталогов
        echo "<tr $style >
              <td><a href=photos.php?id_catalog=$catalog[id_catalog]>$catalog[name]$total</td>
              <td>".nl2br(print_page($catalog['name']))."</td>
              <td>
              <a href=catup.php?$url>Вверх</a><br>
              $strhide<br>
              <a href=catedit.php?$url>Редактировать</a><br>
              <a href=# onClick=\"delete_catalog('catdel.php?$url',".
              "'Вы действительно хотите удалить раздел?');\">Удалить</a><br>
              <a href=catdown.php?$url>Вниз</a><br></td>
            </tr>";
      }
      echo "</table><br>";
    }
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>