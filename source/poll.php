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

  session_start();
  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Подключаем функцию вывода текста с bbCode
  require_once("dmn/utils/utils.print_page.php");
  // Подключаем заголовок 
  require_once("utils.title.php");

  try
  {
    // Запрашиваем текущий опрос
    $query = "SELECT * FROM $tbl_poll
              WHERE archive = 'active' AND 
      	            hide = 'show'";
    $pol = mysql_query($query);
    if(!$pol)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении 
                               к параграфам позиции");
    }
    if(mysql_num_rows($pol)) $poll = mysql_fetch_array($pol);
    // Учитываем голос
    if(!empty($_POST))
    {
      // Удаляем старые записи из таблицы $tbl_poll_session
      $query = "DELETE FROM $tbl_poll_session
                WHERE putdate < NOW() - INTERVAL 1 HOUR";
      if(!mysql_query($query))
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка очистки журнала
                                 посещений");
      }
      // Проверяем не голосовал ли текущий посетитель ранее
      $query = "SELECT COUNT(*) FROM $tbl_poll_session
                WHERE session = '".session_id()."'";
      $ses = mysql_query($query);
      if(!$ses)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка обращения к журналу
                                 посещений");
      }
      if(!mysql_result($ses, 0))
      {
        $query = "INSERT INTO $tbl_poll_session
                  VALUES (NULL, '".session_id()."', NOW())";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка обращения к журналу
                                   посещений");
        }
        $_POST['id_answer'] = intval($_POST['id_answer']);
        $query = "UPDATE $tbl_poll_answer
                  SET hits = hits + 1 
                  WHERE id_position = $_POST[id_position] AND
                        id_catalog = $poll[id_catalog]";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка во время голосования");
        }
      }
    }
    // Подключаем верхний шаблон
    $pagename = $poll['name'];
    $keywords = $poll['name'];
    require_once ("templates/top.php");

    // Выводим результаты голосования
    echo title($poll['name']);

    // Подсчитываем сумму всех проголосовавших в текущем голосовании
    $query = "SELECT SUM(hits) FROM $tbl_poll_answer
              WHERE id_catalog = $poll[id_catalog]";
    $tot = mysql_query($query);
    if(!$tot)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка извлечения
                               результатов голосования");
    }
    // Общее количество отданых голосов
    $total = mysql_result($tot, 0);
    // Предотвращаем деление на ноль
    if($total == 0) $total = 1;

    // Извлекаем варианты ответов и количество голосов,
    // отданных за них
    $query = "SELECT * FROM $tbl_poll_answer
              WHERE id_catalog = $poll[id_catalog]
              ORDER BY pos";
    $ans = mysql_query($query);
    if(!$ans)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка извлечения
                               результатов голосования");
    }
    if(mysql_num_rows($ans))
    {
      // Выводим заголовок таблицы с результатами голосования
      echo "<table width=100% 
                   border=0 
                   cellspacing=1 
                   cellpadding=1>
            <tr class=stable_tr_ttl_clr>
              <td align=center class=stable_txt>
                <b>Ответ</b>
              </td>
              <td align=center class=stable_txt>
                <b>Проголосовало</b>
              </td>
              <td align=center class=stable_txt>
                <b>%</b>
              </td>
            </tr>";
      $i = 0;
      while($answer = mysql_fetch_array($ans))
      {
        if($i++ % 2) $class = "stable_tr_clr2";
        else $class = "stable_tr_clr1";
        // Выводим результаты голосования
        echo "<tr class=\"$class\">
                <td class=stable_txt>$answer[name]</td>
                <td class=stable_txt align=center>$answer[hits]</td>
                <td class=stable_txt align=center>".sprintf("%01.1f%s", 
                      $answer['hits']/$total*100,'%')."</td>
              </tr>";
      }
      echo "</table>";
      echo "<div class=main_txt>Общее количество проголосовавших составляет: $total</div>";
    }

    //Подключаем нижний шаблон
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
