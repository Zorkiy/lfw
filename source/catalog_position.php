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

  // Выводим таблицу с товарными позициями
  // Формируем ассоциативный массив, где в качестве ключей выступают,
  // параметры, передаваемые в строке запроса, а в качестве значений,
  // имена полей в таблице product
  $order = array();
  $order['roomsorder'] = "rooms";
  $order['districtorder'] = "district";
  $order['square_o_order'] = "square_o";
  $order['square_j_order'] = "square_j";
  $order['square_k_order'] = "square_k";
  $order['price_metr_order'] = "pricemeter";
  $order['price_order'] = "price";
  $order['floor_order'] = "floor";
  $order['floor_house_order'] = "floorhouse";
  $order['currency_order'] = "currency";
  $order['su_order'] = "su";
  $order['balcony_order'] = "balcony";
  $order['material_order'] = "material";
  // Формируем временную переменную $strtmp, которая далее
  // используется для сортировки результатов SQL-запроса при извлечении
  // товарных позиций из таблицы product
  // По умолчанию сортируем товарные позиции по полю pos
  $strtmp = "pos";
  // Если через параметр строки запроса задана прямая или обратная
  // сортировка по одному из полей таблицы order изменяем значение
  // временной переменной $strtmp
  foreach($order as $parametr => $field)
  {
    if(isset($_GET[$parametr]))
    {
      if($_GET[$parametr] == "up")
      {
        $_GET[$parametr] = "down";
        $strtmp = $field;
      } 
      else 
      {
        $_GET[$parametr] = "up";
        $strtmp = "$field DESC";
      }
    }
    else $_GET[$parametr] = "up";
  }
  // Выбираем из таблицы product
  $query = "SELECT * FROM $tbl_cat_position 
            WHERE id_catalog=$_GET[id_catalog] 
            ORDER BY $strtmp";
  $pos = mysql_query($query);
  if(!$pos)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "Ошибка при извлечении 
                             параметров текущего раздела");
  }
  // количество рядов в наборе должно быть больше нуля
  if (mysql_num_rows($pos)>0)
  {
     // формируем ссылку с помощью которой можно сортировать товарные
     // позиции по выводимым полям таблицы
     $href = "catalog.php?id_catalog=$_GET[id_catalog]";
     echo "<table width=100% 
                  border=0 
                  cellspacing=1 
                  cellpadding=1><tr class=stable_tr_ttl_clr>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&roomsorder=$_GET[roomsorder]>Кол.комн.</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&districtorder=$_GET[districtorder]>Район</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><span class=main_txt>Адрес</span></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&square_o_order=$_GET[square_o_order]>(О)</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&square_j_order=$_GET[square_j_order]>(Ж)</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&square_k_order=$_GET[square_k_order]>(К)</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&floor_order=$_GET[floor_order]>Этаж</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&floor_house_order=".
                 "$_GET[floor_house_order]>Эт.дома</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&material_order=$_GET[material_order]>Хар</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&su_order=$_GET[su_order]>С/У</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&balcony_order=$_GET[balcony_order]>Л/Б</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&price_metr_order=".
                 "$_GET[price_metr_order]>Цена,<br>м.кв.</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&price_order=$_GET[price_order]>Цена, общ.</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&currency_order=$_GET[currency_order]>Валюта</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><span class=main_txt>Прим.</span></b>
             </td>
          </tr>";
    $i = 0;
    while($position = mysql_fetch_array($pos))
    {
      // Определяем район
      switch ($position['district'])
      {
        case 'kanavinskii':
          $distr = "Канавинский.";
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
        default: $distr="&nbsp";              
      }
      // Определяем материал дома
      switch ($position['material'])
      {
        case 'brick':
          $material = "кирп.";
          break;
        case 'concrete':
          $material = "панел.";
          break;
        case 'reconcrete':
          $material = "монолит.";
          break;
        default: $material="&nbsp";               
      }
      // Определяем тип сан.узла
      switch ($position['su'])
      {
        case 'separate':
          $su = "сов.";
          break;
        case 'combined':
          $su = "разд.";
          break;
        default: $su="&nbsp";                         
      }
      // Определяем наличие балкона
      switch ($position['balcony'])
      {
        case 'balcony':
          $balcony = "балкон";
          break;
        case 'loggia':
          $balcony = "лоджия";
          break;
        default: $balcony="&nbsp";  
      }
      if($i++ % 2) $class = "stable_tr_clr2";
      else $class = "stable_tr_clr1";
      // Выводим строку таблицы
      echo "<tr class=\"$class\">
            <td align=center class=stable_txt>
              $position[rooms]
            </td>
            <td class=stable_txt>
              $distr
            </td>
            <td class=stable_txt>
              $position[address]
            </td>
            <td align=center class=stable_txt>
              $position[squareo]
            </td>
            <td align=center class=stable_txt>
              $position[squarej]
            </td>
            <td align=center class=stable_txt>
              $position[squarek]
            </td>
            <td align=center class=stable_txt>
              $position[floor]
            </td>
            <td align=center class=stable_txt>
              $position[floorhouse]
            </td>
            <td align=center class=stable_txt>
              $material
            </td>
            <td align=center class=stable_txt>
              $su
            </td>
            <td align=center class=stable_txt>
              $balcony
            </td>
            <td align=center class=stable_txt>
              $position[pricemeter]
            </td>
            <td align=center class=stable_txt>
              $position[price]
            </td>
            <td align=center class=stable_txt>
              $position[currency]
            </td>
            <td class=stable_txt>
              $position[note]
            </td>
          </tr>";
    }
    echo "</table>";
  }
?>