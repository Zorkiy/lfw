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
  $_GET['id_catalog']   = intval($_GET['id_catalog']);
  $_GET['id_position']  = intval($_GET['id_position']);
  $_GET['id_paragraph'] = intval($_GET['id_paragraph']);

  if(empty($_POST)) $_REQUEST['hide'] = true;
  try
  {
    $name = new field_text("name",
                           "Название",
                           false,
                           $_POST['name']);
    $alt = new field_text("alt",
                           "ALT-тэг",
                           false,
                           $_POST['alt']);
    $small   = new field_file("small",
                                 "Малое изображение",
                                 true,
                                 $_FILES,
                                 "../../files/article/");
    $big   = new field_file("big",
                                 "Крупное изображение",
                                 false,
                                 $_FILES,
                                 "../../files/article/");
    $hide = new field_checkbox("hide",
                               "Отображать",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
    $id_paragraph = new field_hidden_int("id_paragraph",
                                 true,
                                 $_REQUEST['id_paragraph']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name" => $name,
                           "alt" => $alt, 
                           "small" => $small, 
                           "big" => $big, 
                           "hide" => $hide, 
                           "id_catalog" => $id_catalog,
                           "id_position" => $id_position,
                           "id_paragraph" => $id_paragraph,
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
        $query = "SELECT MAX(pos) FROM $tbl_paragraph_image
                  WHERE id_catalog={$form->fields['id_catalog']->value} AND
                        id_position={$form->fields['id_position']->value} AND
                        id_paragraph={$form->fields['id_paragraph']->value}";
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
        $var = $form->fields['small']->get_filename();
        if(!empty($var)) $small = "files/article/".$var;
        else $small = "";
        $var = $form->fields['big']->get_filename();
        if(!empty($var)) $big = "files/article/".$var;
        else $big = "";
        // Формируем SQL-запрос на добавление позиции
        $query = "INSERT INTO $tbl_paragraph_image
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[alt]->value}',
                          '$small',
                          '$big',
                          '$showhide',
                           $pos,
                          {$form->fields[id_position]->value},
                          {$form->fields[id_catalog]->value},
                          {$form->fields[id_paragraph]->value})";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при добавлении 
                                   изображения");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: image.php?".
               "id_paragraph={$form->fields[id_paragraph]->value}&".
               "id_position={$form->fields[id_position]->value}&".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Добавление изображение';
    $pageinfo  = '<p class=help>Малое изображение выводится непосредственно на страницу сайта. Если
                  загружается большое изображение, то малое изображение становится гиперссылкой, при
                  переходе по которой открывается большое изображение.</p>';
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