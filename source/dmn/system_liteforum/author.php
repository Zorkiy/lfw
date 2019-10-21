<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) Кузнецов М.В., Симдянов И.В.
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
  require_once("config.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");
  // Постраничная навигация
  require_once("../utils/utils.pager.php");
  // Выполнение SQL-запроса
  require_once("utils.query_result.php");
  // Подключаем функции для работы со временем
  require_once("../../utils/utils.time.php");


  try
  {
    $title = 'Модерирование форума';  
    $pageinfo = '<p class=help>Информация об авторе</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");

    // Извлекаем информацию из строки запроса
    $id_author = intval($_GET['id_author']);
    $id_forum = intval($_GET['id_forum']);

    // Извлекаем информацию о посетителе
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_author";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при выборе информации о пользователе");
    }
    if(mysql_num_rows($ath))
    {
      $author = mysql_fetch_array($ath);
      echo '<a href="javascript: history.back()">Вернуться назад</a><br><br>';
      echo '<table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">';
      echo "<tr class='header'>
              <td align=center>Параметр</td>
              <td align=center>Значение</td>
            </tr>";
      echo "<tr><td>Имя</td><td>".htmlspecialchars($author['name'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>E-mail</td><td>".htmlspecialchars($author['email'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>URL</td><td>".htmlspecialchars($author['url'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>ICQ</td><td>".htmlspecialchars($author['icq'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>О себе</td><td>".nl2br(htmlspecialchars($author['icq'], ENT_QUOTES))."&nbsp;</td></tr>";
      echo "<tr><td>Порядковый номер</td><td>".nl2br(htmlspecialchars($author['id_author'], ENT_QUOTES))."&nbsp;</td></tr>";
      echo "<tr><td>Количество сообщений</td><td>".nl2br(htmlspecialchars($author['themes'], ENT_QUOTES))."&nbsp;</td></tr>";
      echo "<tr><td>Последнее посещение</td><td>".convertdate($author['time'])."&nbsp;</td></tr>";
      if(!empty($author['photo']) && $author['photo'] != "-" && is_file($author['photo']))
      {
        // Если фото не нулевой длины выводим его
        if(filesize($author['photo']) && $author['photo'] != "-" && is_file("../../forum/".$author['photo'])) 
        {
          echo "<tr><td>Фото</td><td><a href=../../forum/$author[photo]>имеется</a></td></tr>";
        }
      }
      echo "<tr><td>&nbsp;</td><td><a href=authorthmes.php?id_author=$author[id_author]&id_forum=1>Живой форум</a></td></tr>";
      echo "<tr><td>&nbsp;</td><td><a href=authorthmes.php?id_author=$author[id_author]&id_forum=1&arch=archiv>Архив</a></td></tr>";
      echo "</table>";
    }
    // Выводим завершение страницы
    require_once("../utils/bottom.php");
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
?>