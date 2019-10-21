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
  // Подключаем функцию изменения размера изображения
  require_once("../utils/utils.resizeimg.php");

  // Защита от SQL-инъекции
  $_GET['id_catalog']   = intval($_GET['id_catalog']);

  if(empty($_POST)) $_REQUEST['hide'] = true;
  try
  {
    $name = new field_text("name",
                           "Название",
                           false,
                           $_POST['name']);
    $alt = new field_text("alt",
                           "ALT-тег",
                           false,
                           $_POST['alt']);
    $big   = new field_file("big",
                            "Изображение",
                             false,
                             $_FILES,
                            "../../files/photo/");
    $pollnumber = new field_text_int("pollnumber",
                                     "Количество проголосовавших",
                                      false,
                                     $_POST['pollnumber']);
    $pollmark = new field_text_int("pollmark",
                                   "Количество голосов",
                                    false,
                                    $_POST['pollmark']);
    $countwatch = new field_text_int("countwatch",
                                     "Количество просмотров",
                                      false,
                                      $_POST['countwatch']);
    $hide = new field_checkbox("hide",
                               "Отображать",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name"       => $name,
                           "alt"        => $alt, 
                           "big"        => $big,
                           "pollnumber" => $pollnumber,
                           "pollmark"   => $pollmark,
                           "countwatch" => $countwatch,
                           "hide"       => $hide, 
                           "id_catalog" => $id_catalog,
                           "page"       => $page), 
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
        $query = "SELECT MAX(pos) FROM $tbl_photo_position
                  WHERE id_catalog={$form->fields['id_catalog']->value}";
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
        // Изображения
        $var = $form->fields['big']->get_filename();
        if(!empty($var))
        {
          $big = "files/photo/".$var;
          $small = "files/photo/s_".$var;
        }
        else $big = "";
        // Извлекаем параметры галереи
        $query = "SELECT * FROM $tbl_photo_settings LIMIT 1";
        $set = mysql_query($query);
        if(!$set)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при извлечении 
                                   параметров галереи");
        }
        if(mysql_num_rows($set))
        {
          $settings = mysql_fetch_array($set);
        }
        else
        {
          $settings['width'] = 150;
          $settings['height'] = 133;
        }
        // Формируем малое изображение
        resizeimg($big, $small, $settings['width'], $settings['height']);
        // Формируем SQL-запрос на добавление позиции
        $query = "INSERT INTO $tbl_photo_position
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[alt]->value}',
                          '$small',
                          '$big',
                          '{$form->fields[pollnumber]->value}',
                          '{$form->fields[pollmark]->value}',
                          '{$form->fields[countwatch]->value}',
                          '$showhide',
                           $pos,
                          {$form->fields[id_catalog]->value})";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при добавлении 
                                   позиции");
        }
        // Осуществляем редирект на главную страницу
        header("Location: photos.php?".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Добавление изображения';
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