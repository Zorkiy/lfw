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

  try
  {
    // Извлекаем название форума из строки запроса
    $id_forum = intval($_GET['id_forum']);

    // Извлекаем из таблицы $tbl_forums
    // все разделы данного форума
    $query = "SELECT * FROM $tbl_forums 
              ORDER BY pos";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка извлечения разделов");
    }
    $arr = array();
    if(mysql_num_rows($frm))
    {
      while($forum = mysql_fetch_array($frm))
      {
        if($forum['id_forum'] != $id_forum)
        {
          $arr[$forum['id_forum']] = $forum['name'];
        }
      }
    }

    $forum = new field_select("forum",
                           "Переместить раздел в ",
                            $arr,
                            $_POST['forum']);
    $id_forum = new field_hidden_int("id_forum",
                            true,
                            $_REQUEST['id_forum']);
  
    $form = new form(array("forum" => $forum, 
                           "id_forum" => $id_forum), 
                     "Объединить",
                     "field");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Формируем SQL-запрос перемещения тем форума
        $query = "UPDATE $tbl_themes 
                  SET id_forum = {$form->fields[forum]->value} 
                  WHERE id_forum = {$form->fields[id_forum]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка переноса тем");
        }
        // Формируем SQL-запрос для удаления форума из таблицы forums
        $query = "DELETE FROM $tbl_forums 
                  WHERE id_forum = {$form->fields[id_forum]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка удаления форума");
        }
        // Редактируем таблицу новых сообщений last_time
        $query = "ALTER TABLE $tbl_last_time 
                  DROP now{$form->fields[id_forum]->value}, DROP last_time{$form->fields[id_forum]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка редактирования базы данных");
        }

        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: index.php");
        exit();
      }
    }

    // Начало страницы
    $title     = 'Объединение разделов';
    $pageinfo  = '<p class=help>Выберите раздел, в который 
    следует переместить текущий раздел, и нажмите кнопку "Переместить".</p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".implode("<br>", $error)."</span><br>";
    }
    // Выводим HTML-форму 
    $form->print_form();

    // Включаем завершение страницы
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