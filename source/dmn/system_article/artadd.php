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
  // Подключаем классы формы
  require_once("../../config/class.config.dmn.php");

  // Защита от SQL-инъекции
  $_GET['id_parent'] = intval($_GET['id_parent']);

  if(empty($_POST)) $_REQUEST['hide'] = true;
  try
  {
    $name = new field_text("name",
                           "Название",
                           true,
                           $_POST['name']);
    $description = new field_textarea("description",
                                 "Содержимое статьи",
                                 true,
                                 $_POST['description']);
    $keywords = new field_text("keywords",
                               "Ключевые слова",
                               false,
                               $_POST['keywords']);
    $modrewrite = new field_text_english("modrewrite",
                               "Название для<br>ReWrite",
                               false,
                               $_POST['modrewrite']);
    $hide = new field_checkbox("hide",
                               "Отображать",
                               $_REQUEST['hide']);
    $id_parent = new field_hidden_int("id_parent",
                                 true,
                                 $_REQUEST['id_parent']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name" => $name,
                           "description" => $description, 
                           "keywords" => $keywords, 
                           "modrewrite" => $modrewrite, 
                           "hide" => $hide,
                           "modrewrite" => $modrewrite,
                           "id_parent" => $id_parent,
                           "page" => $page), 
                     "Добавить",
                     "field");
  
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Извлекаем текущую максимальную позицию
        $query = "SELECT MAX(pos) FROM $tbl_position
                  WHERE id_catalog = {$form->fields[id_parent]->value}";
        $pos = mysql_query($query);
        if(!$pos)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при извлечении 
                                   текущей позиции");
        }
        $pos = mysql_result($pos, 0) + 1;
        // Скрытый или открытая позиция
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // Формируем SQL-запрос на добавление позиции
        $query = "INSERT INTO $tbl_position
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          'article',
                          '{$form->fields[keywords]->value}',
                          '{$form->fields[modrewrite]->value}',
                           $pos,
                          '$showhide',
                          {$form->fields[id_parent]->value})";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при добавлении
                                   новой позиции");
        }
        // Извлекаем значение первичного ключа, только
        // что вставленной записи, назначенного механизмом
        // AUTO_INCREMENT
        $id_position = mysql_insert_id();
        // Разбиваваем текст на параграфы
        $par = preg_split("|\r\n|", 
                          $form->fields['description']->value);
        if(!empty($par))
        {
          $i = 0;
          foreach($par as $parag)
          {
            $i++;
            $sql[] = "(NULL,
                       '$parag',
                       'text',
                       'left',
                       'show',
                       $i,
                       $id_position, 
                       {$form->fields[id_parent]->value})";
          }
          $query = "INSERT INTO $tbl_paragraph 
                    VALUES ".implode(",",$sql);
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при добавлении 
                                   новой позиции");
          }
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: index.php?".
               "id_parent={$form->fields[id_parent]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Добавление позиции';
    $pageinfo  = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках если они имеются
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\">$err</span><br>";
      }
    }
    // Выводим HTML-форму 
    $form->print_form();
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