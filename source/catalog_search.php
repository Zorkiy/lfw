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
    // Подключаем верхний шаблон
    $pagename = "Поиск по каталогу";
    $keywords = "Поиск по каталогу";
    require_once ("templates/top.php");

    // Заголовок страницы
    echo title($pagename);

    ?>
     <form method=post>
     <input type="hidden" 
            name="id_parent" 
            value="<? echo $id_parent ?>">
     <table>
     <tr><td>
     <table width=100%>
     <tr class=main_txt>
     <td>Район :</td>
     <td><select type=text name=district>
        <option value='none' 
          <?php if($_POST['district'] == 'none')
             echo "selected"; ?>>
          не имеет значения</option>
        <option value='kanavinskii' 
          <?php if($_POST['district'] == 'kanavinskii')
                echo "selected"; ?>>
          Канавинский</option>
        <option value='nizhegorodskii' 
          <?php if($_POST['district'] == 'nizhegorodskii')
                echo "selected"; ?>>
          Нижегородский</option>
        <option value='sovetskii' 
          <?php if($_POST['district'] == 'sovetskii') 
                echo "selected"; ?>>
          Советский</option>
        <option value='priokskii' 
          <?php if($_POST['district'] == 'priokskii')
                echo "selected"; ?>>
          Приокский</option>
        <option value='moskovskii' 
          <?php if($_POST['district'] == 'moskovskii')
                echo "selected"; ?>>
          Московский</option>
        <option value='avtozavodskii' 
          <?php if($_POST['district'] == 'avtozavodskii')
                echo "selected"; ?>>
          Автозаводский</option>
        <option value='leninskii' 
          <?php if($_POST['district'] == 'leninskii') 
                echo "selected"; ?>>
          Ленинский</option>
        <option value='sormovskii' 
          <?php if($_POST['district'] == 'sormovskii') 
                echo "selected"; ?>>
          Сормовский</option>
      </select></td></tr>
      <tr class=main_txt>
      <td>количество комнат :</td>
      <td><select type=text name=rooms>
        <option value=0 
        <?php if($_POST['rooms'] == 0) echo "selected"; ?>>
        не имеет значения</option>
      <option value=1 
        <?php if($_POST['rooms'] == 1) echo "selected"; ?>>1</option>
      <option value=2 
        <?php if($_POST['rooms'] == 2) echo "selected"; ?>>2</option>
      <option value=3 
        <?php if($_POST['rooms'] == 3) echo "selected"; ?>>3</option>
      <option value=4 
        <?php if($_POST['rooms'] == 4) echo "selected"; ?>>4</option>
      <option value=5 
        <?php if($_POST['rooms'] == 5) echo "selected"; ?>>5</option>
      <option value=6 
        <?php if($_POST['rooms'] == 6) echo "selected"; ?>>6</option>
      </select></td></tr>
      <tr class=main_txt>
      <td>цена общая, руб. :</td>
      <td>от <input type=text 
                name=price_min 
                value="<?= $_POST['price_min'] ?>"><br>
      до <input type=text 
                name=price_max 
                value="<?= $_POST['price_max'] ?>"></td></tr>
      <tr class=main_txt>
      <td>цена за кв.метр, руб. :</td>
      <td>от <input type=text name=pricemeter_min 
          value="<?php echo $_POST['pricemeter_min']; ?>"><br>
      до <input type=text name=pricemeter_max 
          value="<?php echo $_POST['pricemeter_max']; ?>"></td></tr>
      </table>
   </td><td valign=top>
     <table width=100%>
     <tr class=main_txt>
     <td>этаж :</td>
     <td><input type=text name=floor 
          value="<?php echo $_POST['floor']; ?>"></td></tr>
     <tr class=main_txt>
     <td>сан. узел :</td>
     <td><select type=text name=su>
      <option value='none' 
         <?php if($_POST['su'] == 'none') echo "selected"; ?>>
         не имеет значения
      <option value='separate' 
         <?php if($_POST['su'] == 'separate')
         echo "selected"; ?>>раздельный</option>
      <option value='combined' 
         <?php if($_POST['su'] == 'combined') 
         echo "selected"; ?>>совмещённый</option>
    </select></td></tr>
     <tr class=main_txt>
    <td>лоджия/балкон :</td>
    <td><select type=text name=balcony>
      <option value='none' 
         <?php if($_POST['balcony'] == 0) 
         echo "none"; ?>>не имеет значения</option>
      <option value='balcony' 
         <?php if($_POST['balcony'] == 'balcony') 
         echo "selected"; ?>>балкон</option>
      <option value='loggia' 
         <?php if($_POST['balcony'] == 'loggia') 
         echo "selected"; ?>>лоджия</option>
    </select></td></tr>
    <tr class=main_txt>
    <td>Характеристика :</td>
    <td><select type=text name=material>
    <option value='none' 
      <?php if($_POST['material'] == 'none') 
      echo "selected"; ?>>
      не имеет значения</option>
    <option value='brick' 
      <?php if($_POST['material'] == 'brick') 
      echo "selected"; ?>>кирпичный</option>
    <option value='concrete' 
      <?php if($_POST['material'] == 'concrete')
      echo "selected"; ?>></option>
      панельный</option>
    <option value='reconcrete' 
      <?php if($_POST['material'] == 'reconcrete')
      echo "selected"; ?>>
      монолитный</option>
    </select></td></tr>
    </table>
  </td>
  </tr><tr>
  <td colspan=2>
  <input class=buttonpoll type=submit value=Искать>
  </td></tr>
  </table>
  <input type=hidden name=search value=search>
  </form>
  <?php
    // Cкрипт-обработчик поискового запроса
    // из формы
    if(isset($_POST['search']))
    {
      echo title("Результаты поиска");
      echo "<br>";
      // Флаг равен true, если есть хотя бы один критерий поиска
      $is_query = false;
      // Проверяем наличие и число параметров поиска
      $tmp1 = $tmp2 = $tmp3 = $tmp3 = $tmp4 = $tmp5 = $tmp6 = $tmp7 = $tmp8 =
      $tmp9 = $tmp10 = $tmp11 = $tmp12 = $tmp13 = $tmp14 = $tmp15 = "";

      // Защищаем данные от SQL-инъекции
      if (!get_magic_quotes_gpc())
      {
        $_POST['district'] = mysql_escape_string($_POST['district']);
        $_POST['material'] = mysql_escape_string($_POST['material']);
      }
      $_POST['square_o_min'] = intval($_POST['square_o_min']);
      $_POST['square_o_max'] = intval($_POST['square_o_max']);
      $_POST['square_j_min'] = intval($_POST['square_j_min']);
      $_POST['square_j_max'] = intval($_POST['square_j_max']);
      $_POST['square_k_min'] = intval($_POST['square_k_min']);
      $_POST['square_k_max'] = intval($_POST['square_k_max']);
      $_POST['rooms']        = intval($_POST['rooms']);
      $_POST['floor']        = intval($_POST['floor']);
      $_POST['su']           = intval($_POST['su']);
      $_POST['price_min']    = intval($_POST['price_min']);
      $_POST['price_max']    = intval($_POST['price_max']);
      $_POST['pricemeter_min'] = intval($_POST['pricemeter_min']);
      $_POST['pricemeter_max'] = intval($_POST['pricemeter_max']);
      // Район
      if(!empty($_POST['district']) && $_POST['district'] != 'none') 
         $tmp1 = " AND district='$_POST[district]'";
      // Площадь
      if(!empty($_POST['square_o_min'])) 
         $tmp2 = " AND squareo > $_POST[square_o_min]";
      if(!empty($_POST['square_o_max'])) 
         $tmp3 = " AND squareo < $_POST[square_o_max]";
      if(!empty($_POST['square_j_min'])) 
         $tmp4 = " AND squarej > $_POST[square_j_min]";
      if(!empty($_POST['square_j_max'])) 
         $tmp5 = " AND squarej < $_POST[square_j_max]";
      if(!empty($_POST['square_k_min'])) 
         $tmp6 = " AND squarek > $_POST[square_k_min]";
      if(!empty($_POST['square_k_max'])) 
         $tmp7 = " AND squarek < $_POST[square_k_max]";
      // Количество комнат
      if(!empty($_POST['rooms'])) $tmp8 = " AND rooms=$_POST[rooms]";
      // Этаж
      if(!empty($_POST['floor'])) $tmp9 = " AND floor=$_POST[floor]";
      // Сан. узел
      if(!empty($_POST['su']) && $_POST['su'] != 'none')
         $tmp10 = " AND su='".$_POST['su']."'";
      // Характеристика
      if(!empty($_POST['material']) && $_POST['material'] != 'none')
        $tmp11 = " AND material='$_POST[material]'";
      // Цена
      if(!empty($_POST['price_min'])) 
        $tmp12 = " AND price > $_POST[price_min]";
      if(!empty($_POST['price_max'])) 
        $tmp13 = " AND price < $_POST[price_max]";
      if(!empty($_POST['pricemeter_min'])) 
        $tmp14 = " AND pricemeter > $_POST[pricemeter_min]";
      if(!empty($_POST['pricemeter_max'])) 
        $tmp15 = " AND pricemeter < $_POST[pricemeter_max]";
      // Формируем запрос из переданных данных
      $query = "SELECT * FROM $tbl_cat_position 
                WHERE hide='show'
                ".$tmp11.$tmp1.$tmp2.$tmp3.
                  $tmp4.$tmp5.$tmp6.$tmp7.
                  $tmp8.$tmp9.$tmp10.$tmp12.
                  $tmp13.$tmp14.$tmp15." 
                ORDER BY pos";
      // Выполняем SQL-запрос
      $pos = mysql_query($query);
      if(!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении к 
                                 таблице риэлторских услуг");
      }
      // количество рядов в наборе должно быть больше нуля
      if (mysql_num_rows($pos) > 0)
      {
        ?>
        <table width=100% 
               border=0 
               cellspacing=1 
               cellpadding=1>
         <tr class=stable_tr_ttl_clr>
           <td align=center class=stable_txt>Кол.комн.</td>
           <td align=center class=stable_txt>Район</td>
           <td align=center class=stable_txt>Адрес</td>
           <td align=center class=stable_txt>(О)</td>
           <td align=center class=stable_txt>(Ж) </td>
           <td align=center class=stable_txt>(К)</td>
           <td align=center class=stable_txt>Этаж</td>
           <td align=center class=stable_txt>Эт.дома</td>
           <td align=center class=stable_txt>Материал</td>
           <td align=center class=stable_txt>С/У</td>
           <td align=center class=stable_txt>Лоджия/Балкон</td>
           <td align=center class=stable_txt>Цена,м.кв.</td>
           <td align=center class=stable_txt>Цена, общ. </td>
           <td align=center class=stable_txt>Валюта</td>
           <td align=center class=stable_txt>Прим.</td>
         </tr>
        <?
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
              $distr = "Лененский";
              break;
            case 'sormovskii':
              $distr = "Сормовский";
              break;
          }
          // Определяем материал дома
          $material = "кирп.";
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
          }
          // Определяем тип сан.узла
          $su = "разд.";
          switch ($position['su'])
          {
            case 'separate':
              $su = "разд.";
              break;
            case 'combined':
              $su = "сов.";
              break;
          }
          // Определяем наличие балкона
          $balcony = "балкон";
          switch ($position['balcony'])
          {
            case 'balcony':
              $balcony = "балкон";
              break;
            case 'loggia':
              $balcony = "лоджия";
              break;
          }
          if($i++ % 2) $class = "stable_tr_clr2";
          else $class = "stable_tr_clr1";
          echo "<tr class=\"$class\">
                  <td align=center class=stable_txt>
                    $position[rooms]</td>
                  <td class=stable_txt>
                    $distr</td>
                  <td class=stable_txt>
                    $position[address]</td>
                  <td align=center class=stable_txt>
                    $position[squareo]</td>
                  <td align=center class=stable_txt>
                    $position[squarej]</td>
                  <td align=center class=stable_txt>
                    $position[squarek]</td>
                  <td align=center class=stable_txt>
                    $position[floor]</td>
                  <td align=center class=stable_txt>
                    $position[floorhouse]</td>
                  <td align=center class=stable_txt>
                    $material</td>
                  <td align=center class=stable_txt>
                    $su</td>
                  <td align=center class=stable_txt>
                    $balcony</td>
                  <td align=center class=stable_txt>
                    $position[pricemeter]</td>
                  <td align=center class=stable_txt>
                    $position[price]</td>
                  <td align=center class=stable_txt>
                    $position[currency]</td>
                  <td align=center class=stable_txt>
                    $position[note]</td>
                </tr>";
        }
      }
      else echo "Поиск не дал результатов.
                 Попробуйте изменить критерии поиска.";
    echo "</table>";
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