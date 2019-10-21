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

  $title = $titlepage = 'Администрирование содержимого сайта';  
  $pageinfo = '<p class=help>Здесь осуществляется администрирование 
                             разделов сайта, добавление новых подразделов
                             их позиций</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  $_GET['id_parent'] = intval($_GET['id_parent']);

  try
  {
    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    echo '<table cellspacing="0" cellspacing="0" border=0>
    <tr valign="top"><td height="25"><p>';
    echo "<a class=menu href=index.php?id_parent=0>Корневое меню</a>-&gt;".
             menu_navigation($_GET['id_parent'], "", $tbl_catalog).
         "<a class=menu 
             href=catadd.php?id_catalog=$_GET[id_parent]&id_parent=$_GET[id_parent]>
             Добавить меню</a>";
    echo "</td></tr></table>";
  
    // Выводим список каталогов
    $query = "SELECT * FROM $tbl_catalog
              WHERE id_parent=$_GET[id_parent]
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
                  <td width=20 align=center>Поз.</td>
                  <td width=50 align=center>Действия</td>
                </tr>';
      while($catalog = mysql_fetch_array($ctg))
      {
        $url = "id_catalog=$catalog[id_catalog]&".
               "id_parent=$catalog[id_parent]";
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
        
        // Выводим список каталогов
        echo "<tr $style >
              <td>
                <a href=index.php?id_parent=$catalog[id_catalog]>$catalog[name]</a>
              </td>
              <td>".nl2br(print_page($catalog['description']))."&nbsp;</td>
              <td align=center>$catalog[pos]</td>
              <td>
              <a href=catup.php?$url>Вверх</a><br>
              $strhide<br>
              <a href=catedit.php?$url>Редактировать</a><br>
              <a href=# onClick=\"delete_position('catdel.php?$url',".
              "'Вы действительно хотите удалить раздел?');\">Удалить</a><br>
              <a href=catdown.php?$url>Вниз</a><br></td>
            </tr>";
      }
      echo "</table>";
    }
  
    // Выводим позиции текущего раздела
    if(isset($_GET['id_parent']) && $_GET['id_parent'] != 0)
    {
      // Выводим позиции текущего каталога
      include "position.php";
    }
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>