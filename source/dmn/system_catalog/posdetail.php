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

  $title = 'Подробная информация';  
  // Устанавливаем соединение с базой данных
  require_once("../../config/config.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><?php echo $title; ?></title>
<link rel="StyleSheet" type="text/css" href="../utils/cms.css">
</head>
<body leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" bottommargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" class="text">
  <tr valign="top">
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr valign=top>
    <td width=0>&nbsp;</td>
    <td class=main height=100%>

<?php
  // Проверяем GET-параметры
  $_GET['id_position'] = intval($_GET['id_position']);

  try
  {
    $query = "SELECT * FROM $tbl_cat_position
              WHERE id_position = $_GET[id_position]
              LIMIT 1";
    $pos = mysql_query($query);
    if(!$pos)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении к 
                               таблице позиций");
    }
    if(mysql_num_rows($pos))
    {
      $position = mysql_fetch_array($pos);
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>Параметр</td>
          <td>Значение</td>
        </tr>
      <?php
        // Определяем район
        $distr = "Канавинский";
        switch ($position['district'])
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
        // Определяем материал дома
        $material = "кирпичный";
        switch ($position['material'])
        {
          case 'brick':
            $material = "кирпичиный";
            break;
          case 'concrete':
            $material = "панельный";
            break;
          case 'reconcrete':
            $material = "монолит";
            break;
        }
        // Определяем тип сан.узла
        $su = "сов.";
        switch ($position['su'])
        {
          case 'separate':
            $su = "сов.";
            break;
          case 'combined':
            $su = "разд.";
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
        echo "<tr>
                <td align=right>Район</td>
                <td>$distr</td>
              </tr>";
        echo "<tr>
                <td align=right>Адрес</td>
                <td>$position[address]</td>
              </tr>";
        echo "<tr>
                <td align=right>Площадь(О/Ж/К)</td>
                <td>$position[squareo]/$position[squarej]/$position[squarek]</td>
              </tr>";
        echo "<tr>
                <td align=right>Кол. комнат</td>
                <td>$position[rooms]</td>
              </tr>";
        echo "<tr>
                <td align=right>Этаж</td>
                <td>$position[floor]</td>
              </tr>";
        echo "<tr>
                <td align=right>Этажность дома</td>
                <td>$position[floorhouse]</td>
              </tr>";
        echo "<tr>
                <td align=right>Материал</td>
                <td>$material</td>
              </tr>";
        echo "<tr>
                <td align=right>Сан. узел</td>
                <td>$su</td>
              </tr>";
        echo "<tr>
                <td align=right>Балкон</td>
                <td>$balcony</td>
              </tr>";
        echo "<tr>
                <td align=right>Цена</td>
                <td>$position[price]</td>
              </tr>";
        echo "<tr>
                <td align=right>Цена м.кв.</td>
                <td>$position[pricemeter]</td>
              </tr>";
        echo "<tr>
                <td align=right>Валюта</td>
                <td>$position[currency]</td>
              </tr>";
    }
    echo "</table><br><br>";
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

?>
</td>
<td width=10>&nbsp;</td>
</tr>
<tr class=authors>
  <td colspan="3"></td></tr>
</table>
</body>
</html>