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

  // Пытаемся снять ограничение на время выполнения архивации
  @set_time_limit(0);

  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");
  // Выполнение SQL-запроса
  require_once("utils.query_result.php");

  try
  {
    // Загружаем первичный ключ темы, которая последняя в 
    // архивной таблице
    $query = "SELECT id_theme FROM $tbl_archive_number 
              LIMIT 1";
    $id_theme_archive = query_result($query);
    // Все темы, которые имеют первичный ключ ниже $id_theme_archive
    // находятся в архиве, все, что выше - в "живом форуме"

    // Извлекаем последний номер темы в "живом форуме"
    $query = "SELECT MAX(id_theme) AS id_theme FROM $tbl_themes";
    $id_theme = query_result($query);

    if(empty($_POST))
    {
      $_REQUEST['idthemearchive'] = $id_theme_archive;
    }

    $idthemearchive = new field_text_int("idthemearchive",
                           "Количество тем в архиве",
                            true,
                            $_REQUEST['idthemearchive']);

    $form = new form(array("idthemearchive" => $idthemearchive), 
                     "Переместить в архив",
                     "field");

    if(!empty($_POST))
    {
      // Проверяем не является ли число архивируемых тем
      // больше 
      if($form->fields['idthemearchive']->value > $idtheme)
      {
        $error[] = "Вероятно, вы ошиблись, столько тем нет в форуме";
      }
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Перемещаем сообщения форума
        $query = "INSERT IGNORE INTO $tbl_archive_posts 
                  SELECT * FROM $tbl_posts 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при перемещении сообщений форума");
        }
        // Темы форума
        $query = "SELECT * FROM $tbl_themes 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        $thm = mysql_query($query);
        if(!$thm)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при извлечении тем форума");
        }
        if(mysql_num_rows($thm))
        {
          while($themes = mysql_fetch_array($thm))
          {
            $id_theme = $themes['id_theme'];
            $name = mysql_escape_string($themes['name']);
            $author = mysql_escape_string($themes['author']);
            $id_author = $themes['id_author'];
            $last_author = mysql_escape_string($themes['last_author']);
            $id_last_author = $themes['id_last_author'];
            $hide = $themes['hide'];
            $time = $themes['time'];
            $id_forum = $themes['id_forum'];
            $query = "SELECT COUNT(*) FROM $tbl_posts
                      WHERE id_theme = $id_theme AND 
                            hide != 'hide'";
            $posts_in_topic = query_result($query);
            $val[] = "($id_theme,'$name','$author',$id_author,".
                     "'$last_author',$id_last_author,'$hide',".
                     "'$time',$posts_in_topic,$id_forum)";
          }
          $query = "INSERT INTO $tbl_archive_themes VALUES ".implode(",",$val);
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при перемещении тем форума");
          }
        }
        // Удаляем старые сообщения
        $query = "DELETE FROM $tbl_posts 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при удалении сообщений форума");
        }
        // Темы форума
        $query = "DELETE FROM $tbl_themes 
                  WHERE id_theme <= {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при удалении тем форума");
        }
        // Закрываем все архивные темы, которые не скрыты
        $query = "UPDATE $tbl_archive_themes 
                  SET hide = 'lock' 
                  WHERE hide = 'show'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при закрытии тем форума");
        }
        // Обновляем последний номер архивной темы
        $query = "UPDATE $tbl_archive_number 
                  SET id_theme = {$form->fields[idthemearchive]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при изменении номера последней архивной темы");
        }
        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: archive.php");
        exit();
      }
    }

    $title = 'Архивирование форума';  
    $pageinfo = '<p class=help>Темы в форуме нумеруются от 1 до N, 
    где N - количество тем в форуме. Часть тем можно переместить в 
    архив. Поле "Количество тем в форуме" отражает, сколько тем уже 
    находится в архиве. Исправьте число, находящееся в данном поле, 
    в сторону увеличения и нажмите кнопку "Переместить в архив". 
    Перемещение тем в архив занимает некоторое время, и скрипту 
    может не хватить стандартных 30 секунд, поэтому рекомендуется 
    архивировать за один раз небольшое количество тем. Кроме этого, 
    после перемещения в архив в теме уже нельзя оставлять ответы, 
    а некоторые темы чрезвычайно популярны и могут еще обсуждаться 
    участниками, поэтому следует оставлять в живом форуме до двухсот 
    тем.</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");
    
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".implode("<br>", $error)."</span><br>";
    }
    // Выводим HTML-форму 
    $form->print_form();

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