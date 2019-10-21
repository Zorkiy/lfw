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
  // Подключаем блок отображения текста в окне браузера
  require_once("../utils/utils.print_page.php");

  // Данные переменные определяют название страницы и подсказку.
  $title = 'Управление блоком "Гостевая книга"';
  $pageinfo = '<p class=help>Здесь можно отредактировать или
               удалить сообщение в гостевой книге.</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  try
  {
    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_guestbook,
                           "",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);
  
    // Получаем содержимое текущей страницы
    $guest = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим 
    if(!empty($guest))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>Сообщение</td>
          <td>Ответ</td>
          <td>Дата</td>
          <td>Действия</td>
        </tr>
      <?php
      for($i = 0; $i < count($guest); $i++)
      {
        // Если позиция отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $colorrow = "";
        $url = "?id_position={$guest[$i][id_position]}&page=$_GET[page]";
        if($guest[$i]['hide'] == 'show')
        {
          $showhide = "<a href=guesthide.php$url 
                          title='Скрыть позицию'>
                       Скрыть</a>";
        }
        else
        {
          $showhide = "<a href=guestshow.php$url 
                          title='Отобразить позицию'>
                       Отобразить</a>";
          $colorrow = "class='hiddenrow'";
        }

        // Если указан город - выводим его
        if(!empty($guest[$i]['city']))
        $city = "(".print_page($guest[$i]['city']).")";
        else $city = "";
        // Выводим позицию
        echo "<tr $colorrow >
                <td><b>".print_page($guest[$i]['name']).
                    " $city</b><br>".
                    nl2br(print_page($guest[$i]['msg']))."</td>
                <td>".nl2br(print_page($guest[$i]['answer']))."&nbsp</td>
                <td align=center>{$guest[$i][putdate]}</td>
                <td align=center>
                   $showhide<br>
                   <a href=guestedit.php$url
                      title='Редактировать позицию'>Редактировать</a><br>
                   <a href=# onClick=\"delete_position('guestdel.php$url',".
                   "'Вы действительно хотите удалить позицию?');\">Удалить</a></td>
              </tr>";
      }
      echo "</table><br><br>";
    }
  
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