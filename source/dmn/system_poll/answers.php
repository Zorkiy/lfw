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

  try
  {
    // Защищаемся от SQL-инъекции
    $_GET['id_catalog'] = intval($_GET['id_catalog']);

    // Извлекаем параметры текущего голосования
    $query = "SELECT * FROM $tbl_poll
              WHERE id_catalog = $_GET[id_catalog]";
    $pol = mysql_query($query);
    if(!$pol)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка извлечения
                               информации об голосовании");
    }
    if(mysql_num_rows($pol)) $poll = mysql_fetch_array($pol);
    // Данные переменные определяют название страницы и подсказку.
    $title = $poll['name'];
    $pageinfo = '<p class=help>Здесь можно добавить, отредактировать или
                 удалить вопросы для текущего голосования.</p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");

    // Количество ссылок в постраничной навигации
    $page_link = 3;
    // Количество позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_poll_answer,
                           "",
                           "ORDER BY pos",
                           $pnumber,
                           $page_link);
  
    // Добавить позицию
    echo "<a href=anwadd.php?page=$_GET[page]&id_catalog=$_GET[id_catalog]
             title='Добавить вариант ответа'>
             Добавить вариант ответа</a><br><br>";
  
    // Получаем содержимое текущей страницы
    $answer = $obj->get_page();
    // Если имеется хотя бы одна запись - выводим 
    if(!empty($answer))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>Вариант ответа</td>
          <td width=40>Хиты</td>
          <td width=40>Поз.</td>
          <td width=100>Действия</td>
        </tr>
      <?php
      for($i = 0; $i < count($answer); $i++)
      {
        // Если позиция отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимая (hide='show') - "скрыть"
        $colorrow = "";
        $url = "?id_catalog={$answer[$i][id_catalog]}&".
               "id_position={$answer[$i][id_position]}&".
               "page=$_GET[page]";

        // Выводим позицию
        echo "<tr $colorrow >
                <td>".print_page($answer[$i]['name'])."</td>
                <td align=center>{$answer[$i][hits]}</td>
                <td align=center>{$answer[$i][pos]}</td>
                <td align=center>
                   <a href=anwup.php$url>Вверх</a><br>
                   <a href=anwedit.php$url
                      title='Редактировать позицию'>Редактировать</a><br>
                   <a href=# onClick=\"delete_position('anwdel.php$url',".
                     "'Вы действительно хотите удалить позицию?');\">Удалить</a><br>
                   <a href=anwdown.php$url>Вниз</a><br></td>
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