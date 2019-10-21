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
  // Навигационное меню
  require_once("../utils/utils.navigation.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Обработка текста перед выводом
  require_once("../utils/utils.print_page.php");

  // Защита от SQL-инъекции
  $_GET['id_catalog']   = intval($_GET['id_catalog']);
  $_GET['id_position']  = intval($_GET['id_position']);
  $_GET['id_paragraph'] = intval($_GET['id_paragraph']);

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
  
    $title = $titlepage = 'Администрирование блока Изображений ('.$catalog['name'].' - '.$position['name'].')';
    $helppage = 'Здесь осуществляется администрирование блока Изображений('.$catalog['name'].' - '.$position['name'].')';
  
    // Включаем заголовок страницы
    require_once("../utils/top.php");
  
    // Если это не корневой каталог выводим ссылки для возврата
    // и для добавления подкаталога
    if($_GET['id_catalog'] != 0)
    {
      echo '<table cellspacing="0" cellspacing="0" border=0><tr>
      <tr valign="top"><td height="25"><p>';
      echo "<a class=menu href=index.php?id_parent=0>Корневой каталог</a>-&gt;".
               menu_navigation($_GET['id_catalog'], "", $tbl_catalog)."
            <a href=paragraph.php?id_position=$_GET[id_position]&id_catalog=$_GET[id_catalog]&page=$_GET[page]>$position[name]</a>";
      echo "</td></tr></table>";
    }
  
    // Добавить изображение
    echo "<a class=menu href=imgadd.php?id_catalog=$_GET[id_catalog]&".
                            "id_position=$_GET[id_position]&".
                            "id_paragraph=$_GET[id_paragraph]>Добавить изображение</a><br><br>";
  
    // Число ссылок в постраничной навигации
    $page_link = 3;
    // Число позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_paragraph_image,
                           "WHERE id_position = $_GET[id_position] AND 
                                  id_catalog=$_GET[id_catalog] AND
                                  id_paragraph = $_GET[id_paragraph]",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link,
                           "&id_position=$_GET[id_position]&".
                           "id_catalog=$_GET[id_catalog]&".
                           "id_paragraph=$_GET[id_paragraph]");

    // Получаем содержимое текущей страницы
    $image = $obj->get_page();

    if(!empty($image))
    {
      // Выводим заголовок таблицы изображений
      echo '<table width="100%" class="table" border="0" cellpadding="0" cellspacing="0">
              <tr class="header" align="center">
                <td align=center>Название</td>
                <td align=center>ALT-тэг</td>
                <td width=20 align=center>Поз.</td>
                <td width=50>Действия</td>
              </tr>';
      for($i = 0; $i < count($image); $i++)
      {
        $url = "id_image={$image[$i][id_image]}&".
               "id_paragraph=$_GET[id_paragraph]&".
               "id_position=$_GET[id_position]&".
               "id_catalog=$_GET[id_catalog]&".
               "page=$_GET[page]";
        // Выясняем скрыта позиция или нет
        if($image[$i]['hide'] == 'hide')
        {
          $strhide = "<a href=imgshow.php?$url>Отобразить</a>";
          $style=" class=hiddenrow ";
        } 
        else
        {
          $strhide = "<a href=imghide.php?$url>Скрыть</a>";
          $style="";
        }
  
        $image_print = "<img src=../../".$image[$i]['small']." border=0 vspace=3>";
        if(!empty($image[$i]['big']))
        {
          if(file_exists("../../".$image[$i]['big']))
          {
            // Определяем размер большого изображения
            $size = @getimagesize("../../".$image[$i]['big']);
            $image_print = "<a href=# 
                               onclick=\"show_img('../../{$image[$i][big]}',".$size[0].",".$size[1]."); return false \" >
                               <img src=../../".$image[$i]['small']." border=0 vspace=3></a>";
          }
        }
        
        echo "<tr $style>
              <td align=center>$image_print<br>
              ".print_page($image[$i]['name'])."&nbsp;</td>
              <td align=center>".print_page($image[$i]['alt'])."&nbsp;</td>
              <td>".$image[$i]['pos']."</td>
              <td>
                <a href=imgup.php?$url>Вверх</a><br>
                $strhide<br>
                <a href=# onClick=\"delete_position('imgdel.php?$url','Вы действительно хотите удалить изображение?');\">Удалить</a><br>
                <a href=imgdown.php?$url>Вниз</a>
              </td>
            </tr>";
      }
      echo "</table>";
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
  function show_img(img,width,height,adm)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a=(screen.height-vidWindowHeight)/5;
    b=(screen.width-vidWindowWidth)/2;
    features = "top=" + a + ",left=" + b + ",width=" + 
                vidWindowWidth + ",height=" + 
                vidWindowHeight + 
                ",toolbar=no,menubar=no,location=no,directories=no,scrollbars=no,resizable=no";
    url="show.php?img=" + img;
    if (adm=='true') url = "../" + url;
    window.open(url,'',features,true);
  }
//-->
</script>