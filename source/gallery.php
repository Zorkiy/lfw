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

  // Инициируем сессию
  session_start();

  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Подключаем функцию обработки текста
  // перед публикацией
  require_once("dmn/utils/utils.print_page.php");
  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Заголовок
  require_once("utils.title.php");

  // Голосование
  function poll($id_position, $user_rating)
  {
    // Объявляем название таблицы глобальным
    global $tbl_photo_position;
    // Регистрируем изображение, чтобы за
    // изображение не голосовали дважды.
    $_SESSION['user_poll_id'][] = $id_position;
    // Увеличиваем количество просмотров данного изображения
    $query = "UPDATE $tbl_photo_position
              SET pollnumber = pollnumber + 1,
                  pollmark = pollmark + $user_rating
              WHERE id_position = $id_position
              LIMIT 1";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при голосовании за
                               фотографию");
    }
  }

  try
  {
    if($_POST['user_rating'] > 0 && $_POST['user_rating'] <= 5)
    {
      // Обновляем параметры pollnumber и pollmark,
      // соответсвующих количествую проголосовавших и 
      // суммарной оценки (которая потом делится на количество
      // проголосовавших).
      if(is_array($_SESSION['user_poll_id']))
      {
        if(!in_array($_POST['id_position'], $_SESSION['user_poll_id']))
        { 
          poll($_POST['id_position'], $_POST['user_rating']);
        }
      }
      else poll($_POST['id_position'], $_POST['user_rating']);
    }

    // Предотвращаем SQL-инъекцию
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    // Извлекаем название галереи
    $query = "SELECT * FROM $tbl_photo_catalog
              WHERE id_catalog = $_GET[id_catalog]";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при извлечении 
                               галерей");
    }
    $catalog = mysql_fetch_array($cat);

    // Подключаем шапку
    $pagename = "Галерея - ".$catalog['name'];
    $keywords = "Галерея";
    require_once("templates/top.php");
    // Выводим заголовок страницы
    echo title($pagename);

    // Извлекаем параметры галерея
    $query = "SELECT * FROM $tbl_photo_settings LIMIT 1";
    $set = mysql_query($query);
    if(!$set)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при извлечении 
                               параметров галереи");
    }
    // Если имеется хотя бы одна запись в таблице
    // извлекаем количество фотографий в ряду
    if(mysql_num_rows($set))
    {
      $settings = mysql_fetch_array($set);
      $numphoto = $settings['row'];
    }
    // Если записи в таблице $tbl_photo_settigns
    // отсуствуют выводим по 3 фотографии в ряд
    else $numphoto = 3;

    // Выводим фотографии
    $query = "SELECT * FROM $tbl_photo_position
              WHERE id_catalog = $_GET[id_catalog] AND
                    hide = 'show'
              ORDER BY pos";
    $pht = mysql_query($query);
    if(!$pht)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка извлечения
                               изображений");
    }
    if(mysql_num_rows($pht))
    {
      $tr == 0;
      echo "<div class=\"main_txt\">";
      echo "<table width=100% border=0>";
      while($photo = mysql_fetch_array($pht))
      {
        $name = $photo['name'];
        $alt = $photo['alt'];
        // Определяем размеры изображений
        list($width_big, $height_big) = @getimagesize($photo['big']);
        list($width_small, $height_small) = @getimagesize($photo['small']);

        // Вычисляем рейтинг текущей фотографии
        $rating = "0.0";
        if(!empty($photo['pollnumber']))
        {
          $rating = floor($photo['pollmark']/$photo['pollnumber']);
          if($photo['pollmark'] % $photo['pollnumber'] >= 0.5) $rating += 0.5;
          $rating = sprintf("%0.01f", $rating);
        }
        // Определяем количество просмотров
        $countwatch = "";
        if(!empty($photo['countwatch'])) $countwatch = " ($photo[countwatch])";

        if ($tr == 0) echo "<tr class=\"main_txt\">";

        echo "<td class='gallery_txt' align='center'>
             <div style='padding-top:10px;'
                 ><img src='dataimg/rating_$rating.gif' 
                   align=center
                   border=0 
                   alt='$rating'
                   style='padding-top:10px;'></div>
              <a href=# 
                 onclick=\"show_img('$photo[id_position]', $width_big, $height_big); return false \"
              ><img src='$photo[small]' 
                    width='$width_small'
                    height='$height_small'
                    alt='$alt' 
                    style=\"border: 1px solid black\" 
                    vspace=3></a>
                    <div class=\"gallery_txt\" align=\"center\">$name $countwatch</div>";

        // Голосование
        ?>
         <br><br><table border=0 cellpadding=0 cellspacing=0>
         <form id=addvote name=addvote method="post">
           <tr>
             <td><input type=radio name=user_rating value=1></td>
             <td><input type=radio name=user_rating value=2></td>
             <td><input type=radio name=user_rating value=3 checked></td>
             <td><input type=radio name=user_rating value=4></td>
             <td><input type=radio name=user_rating value=5></td>
             <td rowspan=2 valign=top><input type=submit class=in_button value="Ok" /></td>
           </tr>
           <tr class=main_txt><td align=center>1</td><td align=center>2</td><td align=center>3</td><td align=center>4</td><td align=center>5</td></tr>
           <input type=hidden name=id_position value="<?= $photo['id_position']; ?>">
           </form>
         </table>
        <?php

        echo "</td>";
        if (++$tr == $numphoto)
        {
          echo "</tr>";
          $tr = 0;
        }
      }
      if($tr != 0)
      {
        for($i = $tr; $i < $numphoto; $i++)
        {
          echo "<td align=center>&nbsp;</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
      echo "</div>";
    }

    require_once("templates/bottom.php");
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
    features = "top=" + a + ",left=" + b + ",width=" + 
               vidWindowWidth + ",height=" + 
               vidWindowHeight + ",toolbar=no,menubar=no," +
               "location=no,directories=no,scrollbars=no," +
               "resizable=no";
    url = "show.php?id_position=" + id_position;
    window.open(url,'',features,true);
  }
//-->
</script>