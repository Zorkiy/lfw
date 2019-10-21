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

  $_GET['id_position'] = intval($_GET['id_position']);
  $_GET['id_catalog']  = intval($_GET['id_catalog']);

  try
  {
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_position
                WHERE id_catalog=$_GET[id_catalog] AND
                      id_position=$_GET[id_position]
                LIMIT 1";
      $pos = mysql_query($query);
      if(!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении 
                               к позиции");
      }
      $_REQUEST = mysql_fetch_array($pos);
      $_REQUEST['page'] = $_GET['page'];
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }

    $name = new field_text("name",
                           "Название",
                           true,
                           $_REQUEST['name']);
    $url = new field_text("url",
                           "Название",
                           true,
                           $_REQUEST['url']);
    $keywords = new field_text("keywords",
                               "Ключевые слова",
                               false,
                               $_REQUEST['keywords']);
    $modrewrite = new field_text_english("modrewrite",
                               "Название для<br>ReWrite",
                               false,
                               $_REQUEST['modrewrite']);
    $hide = new field_checkbox("hide",
                               "Отображать",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name" => $name,
                           "url" => $url,
                           "keywords" => $keywords, 
                           "modrewrite" => $modrewrite, 
                           "hide" => $hide,
                           "modrewrite" => $modrewrite,
                           "id_catalog" => $id_catalog,
                           "id_position" => $id_position,
                           "page" => $page), 
                     "Редактировать",
                     "field");
  
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Скрытый или открытая позиция
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // Формируем SQL-запрос на добавление позиции
        $query = "UPDATE $tbl_position
                  SET name = '{$form->fields[name]->value}',
                      url = '{$form->fields[url]->value}',
                      keywords = '{$form->fields[keywords]->value}',
                      modrewrite = '{$form->fields[modrewrite]->value}',
                      hide = '$showhide'
                  WHERE id_position = {$form->fields[id_position]->value} AND
                        id_catalog ={$form->fields[id_catalog]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при редактировании 
                                 позиции");
        }

        // Осуществляем редирект на главную страницу администрирования
        header("Location: index.php?".
               "id_parent={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Редактирование позиции';
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