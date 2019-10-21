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

  try
  {
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_photo_settings
                LIMIT 1";
      $set = mysql_query($query);
      if(!$set)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении 
                                 каталогу");
      }
      $_REQUEST = mysql_fetch_array($set);
    }
  
    $width = new field_text_int("width",
                       "Ширина",
                        true,
                        $_REQUEST['width'],
                        50,
                        300,
                        10,
                        10,
                       "",
                       "Ширина уменьшенной копии изображения
                        (от 50 до 300 пикселей)");
    $height = new field_text_int("height",
                       "Высота",
                        true,
                        $_REQUEST['height'],
                        50,
                        300,
                        10,
                        10,
                       "",
                       "Выста уменьшенной копии изображения
                        (от 50 до 300 пикселей)");
    $row = new field_text_int("row",
                       "Фото в ряду",
                        true,
                        $_REQUEST['row'],
                        1,
                        10,
                        3,
                        10,
                       "",
                       "Количество фотографий в ряду (от 1 до 10 штук)");
    $form = new form(array("width" => $width,
                           "height" => $height,
                           "row" => $row), 
                     "Сохранить",
                     "field");
  
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Формируем SQL-запрос на добавление каталога
        $query = "UPDATE $tbl_photo_settings
                  SET width = '{$form->fields[width]->value}',
                      height = '{$form->fields[height]->value}',
                      row = '{$form->fields[row]->value}'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании 
                                   галереи");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: index.php");
        exit(); 
      }
    }
    // Начало страницы
    $title     = 'Настройки фотогалереи';
    $pageinfo  = '<p class=help>Здесь можно установить параметры галереи</p>';
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