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

  $_GET['id_catalog'] = intval($_GET['id_catalog']);

  try
  {
    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_photo_position,
                           "WHERE id_catalog = $_GET[id_catalog]",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link,
                           "&id_catalog=$_GET[id_catalog]");
    echo "<a class=menu 
             href=phtadd.php?id_catalog=$_GET[id_catalog]&".
             "page=$_GET[page]>Добавить позицию</a><br><br>";

    // Получаем записи базы данных в виде массива
    $photo = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим 
    if(!empty($photo))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>Изображение</td>
          <td>Описание</td>
          <td width=20 align=center>Поз.</td>
          <td>Действия</td>
        </tr>
      <?php
      for($i = 0; $i < count($photo); $i++)
      {
        // Формируем URL для управляющих ссылок
        $url = "?id_position={$photo[$i][id_position]}".
               "&id_catalog=$_GET[id_catalog]&".
               "page=$_GET[page]";
        // Выясняем скрыта фотография или нет
        $colorrow = "";
        if($photo[$i]['hide'] == "hide")
        {
          $showhide = "<a href=phtshow.php$url>Отобразить</a>";
          $colorrow = "class='hiddenrow'";
        } 
        else
        {
          $showhide = "<a href=phthide.php$url>Скрыть</a>";
        }
        $size = @getimagesize("../../".$photo[$i]['big']);

        // Выводим позицию
        echo "<tr $colorrow >
                <td align=center>
                  <a href=# 
                     onclick=\"show_img('{$photo[$i][id_position]}',".
                     $size[0].",".$size[1]."); return false \">
                    <img src=../../{$photo[$i][small]} 
                         border=1 
                         style=\"border-color:#000000\" 
                         vspace=3></a>
                </td>
                <td valign=top>Название : {$photo[$i][name]}<br>
                               ALT-тег: {$photo[$i][alt]}</td>
                <td align=center>{$photo[$i][pos]}</td>
                <td align=center>
                  <a href=phtup.php$url>Вверх</a><br>
                  $showhide<br>
                  <a href=# onClick=\"delete_position('phtdel.php$url',".
                  "'Вы действительно хотите удалить позицию?');\">Удалить</a><br>
                  <a href=phtedit.php$url
                      title='Редактировать позицию'>Редактировать</a><br>
                  <a href=phtdown.php$url>Вниз</a>
                </td>
              </tr>";
      }
      echo "</table><br>";
    }
    echo $obj;
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
  function show_img(id_position,width,height)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a = (screen.height-vidWindowHeight)/5;
    b = (screen.width-vidWindowWidth)/2;
    features = "top=" + a + ",left=" + b + 
               ",width=" + vidWindowWidth + 
               ",height=" + vidWindowHeight + 
               ",toolbar=no,menubar=no,location=no," + 
               "directories=no,scrollbars=no,resizable=no";
    url = "../../show.php?id_position=" + id_position;
    window.open(url,'',features,true);
  }
//-->
</script>