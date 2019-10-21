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
  $title = 'Управление блоком "Вопросы и Ответы"';
  $pageinfo = '<p class=help>Здесь можно добавить
               блок "вопрос-ответ", отредактировать или
               удалить уже существующий.</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  try
  {
    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_faq,
                           "",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link);
  
    // Добавить позицию
    echo "<a href=faqadd.php?page=$_GET[page]
             title='Добавить блок вопрос-ответ'>
             Добавить блок вопрос-ответ</a><br><br>";
  
    // Получаем содержимое текущей страницы
    $faq = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим 
    if(!empty($faq))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>Вопрос</td>
          <td>Ответ</td>
          <td width=40>Поз.</td>
          <td>Действия</td>
        </tr>
      <?php
      for($i = 0; $i < count($faq); $i++)
      {
        // Если позиция отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $colorrow = "";
        $url = "?id_position={$faq[$i][id_position]}&page=$_GET[page]";
        if($faq[$i]['hide'] == 'show')
        {
          $showhide = "<a href=faqhide.php$url 
                          title='Скрыть позицию'>
                       Скрыть</a>";
        }
        else
        {
          $showhide = "<a href=faqshow.php$url 
                          title='Отобразить позицию'>
                       Отобразить</a>";
          $colorrow = "class='hiddenrow'";
        }

        // Выводим позицию
        echo "<tr $colorrow >
                <td>".nl2br(print_page($faq[$i]['question']))."</td>
                <td>".nl2br(print_page($faq[$i]['answer']))."</td>
                <td align=center>{$faq[$i][pos]}</td>
                <td align=center>
                   <a href=faqup.php$url>Вверх</a><br>
                   $showhide<br>
                   <a href=faqedit.php$url
                      title='Редактировать позицию'>Редактировать</a><br>
                   <a href=# onClick=\"delete_position('faqdel.php$url',".
                    "'Вы действительно хотите удалить позицию?');\" 
                      title='Удалить позицию'>Удалить</a><br>
                   <a href=faqdown.php$url>Вниз</a><br></td>
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