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
  $title = 'Управление блоком "Блок новостей"';
  $pageinfo = '<p class=help>Здесь можно добавить
               новостной блок, отредактировать или
               удалить уже существующий блок.</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  try
  {
    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_news,
                           "",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);
  
    // Добавить блок
    echo "<a href=newsadd.php?page=$_GET[page]
             title='Добавить новостной блок'>
             Добавить новостной блок</a><br><br>";
  
    // Получаем содержимое текущей страницы
    $news = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим 
    if(!empty($news))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td width=200>Дата</td>
          <td width=60%>Новость</td>
          <td width=40>Избр-е</td>
          <td>Действия</td>
        </tr>
      <?php
      for($i = 0; $i < count($news); $i++)
      {
        // Если новость отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $colorrow = "";
        $url = "?id_news={$news[$i][id_news]}&page=$page";
        if($news[$i]['hide'] == 'show')
        {
          $showhide = "<a href=newshide.php$url 
                          title='Скрыть новость в блоке новостей'>
                       Скрыть</a>";
        }
        else
        {
          $showhide = "<a href=newsshow.php$url 
                          title='Отобразить новость в блоке новостей'>
                       Отобразить</a>";
          $colorrow = "class='hiddenrow'";
        }
        // Проверяем наличие изображения
        if($news[$i]['urlpict'] != '' && 
           $news[$i]['urlpict'] != '-' && 
           is_file("../../".$news[$i]['urlpict']))
        {
        $url_pict = "<b><a href=../../{$news[$i][urlpict]}>есть</a></b>";
        }
        else $url_pict = "нет";
        
        $news_url="";
        if (!empty($news[$i]['url']))
        {
          if(!preg_match("|^http://|i",$news[$i]['url']))
          {
            $news[$i]['url'] = "http://{$news[$i][url]}";
          }
          $news_url = "<br><b>Ссылка:</b> 
                       <a href='{$news[$i][url]}'>
                          {$news[$i][urltext]}</a>";
          if(empty($news[$i]['urltext']))
          {
            $news_url = "<br><b>Ссылка:</b> 
                         <a href='{$news[$i][url]}'>
                            {$news[$i][url]}</a>";
          }
        }

        // Преобразуем дату из формата MySQL YYYY-MM-DD hh:mm:ss
        // в формат DD.MM.YYYY hh:mm:ss
        list($date, $time) = explode(" ", $news[$i]['putdate']);
        list($year, $month, $day) = explode("-", $date);
        $news[$i]['putdate'] = "$day.$month.$year $time";

        // Выводим новость
        echo "<tr $colorrow >
                <td><p align=center>{$news[$i][putdate]}</td>
                <td>
                  <a title='Редактировать текст новости' 
                     href=newsedit.php$url>{$news[$i][name]}</a><br>
                 ".nl2br(print_page($news[$i]['body']))." $news_url </td>
                <td align=center>$url_pict</td>
                <td align=center>$showhide<br>
                   <a href=# onClick=\"delete_position('newsdel.php$url',".
                                      "'Вы действительно хотите удалить".
                                      " новостное сообщение?');\" 
                      title='Удалить новость'>Удалить</a><br>
                   <a href=newsedit.php$url} 
               title='Редактировать текст новости'>Редактировать</a></td>
              </tr>";
      }
      echo "</table><br>";
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