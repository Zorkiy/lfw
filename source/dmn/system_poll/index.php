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
  $title = 'Управление блоком "Голосование"';
  $pageinfo = '<p class=help>Здесь можно добавить, отредактировать 
  или удалить блок голосования</p>';
  // Включаем заголовок страницы
  require_once("../utils/top.php");

  try
  {
    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_poll,
                           "",
                           "ORDER BY putdate DESC",
                           $pnumber,
                           $page_link);
  
    // Добавить позицию
    echo "<a href=polladd.php?page=$_GET[page]
             title='Добавить новый опрос'>
             Добавить новый опрос</a><br><br>";
  
    // Получаем содержимое текущей страницы
    $poll = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим 
    if(!empty($poll))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>Вопрос</td>
          <td width=60>Статус</td>
          <td>Действия</td>
        </tr>
      <?php
      for($i = 0; $i < count($poll); $i++)
      {
        // Если позиция отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $colorrow = "";
        $url = "?id_catalog={$poll[$i][id_catalog]}&page=$_GET[page]";
        if($poll[$i]['hide'] == 'show')
        {
          $showhide = "<a href=pollhide.php$url 
                          title='Скрыть блок'>
                       Скрыть</a>";
        }
        else
        {
          $showhide = "<a href=pollshow.php$url 
                          title='Отобразить блок'>
                       Отобразить</a>";
          $colorrow = "class='hiddenrow'";
        }
        // Выясняем статус позиции
        if($poll[$i]['archive'] == 'archive') $status = "архивное";
        else $status = "активное";


        // Выводим позицию
        echo "<tr $colorrow >
                <td><a href=answers.php?id_catalog={$poll[$i][id_catalog]}&".
                       "page=$_GET[page]>".print_page($poll[$i]['name'])."</a></td>
                <td align=center>$status</td>
                <td align=center>
                   $showhide<br>
                   <a href=polledit.php$url
                      title='Редактировать позицию'>Редактировать</a><br>
                   <a href=# onClick=\"delete_position('polldel.php$url',".
                   "'Вы действительно хотите удалить блок?');\">Удалить</a></td>
              </tr>";
      }
      echo "</table><br><br>";
    }
  
    // Выводим ссылки на другие страницы
    echo $obj;
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>