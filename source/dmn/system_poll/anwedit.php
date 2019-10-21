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
    if(empty($_POST))
    {
      // Предотвращаем SQL-инъекцию
      $_GET['id_catalog']  = intval($_GET['id_catalog']);
      $_GET['id_position'] = intval($_GET['id_position']);
      // Извлекаем параметры редактируемой позиции
      $query = "SELECT * FROM $tbl_poll_answer
                WHERE id_position = $_GET[id_position] AND
                      id_catalog = $_GET[id_catalog]
                LIMIT 1";
      $ans = mysql_query($query);
      if(!$ans)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении 
                                 к позиции");
      }
      $_REQUEST = mysql_fetch_array($ans);
      $_REQUEST['page'] = $_GET['page'];
    }
    $name = new field_textarea("name",
                               "Ответ",
                                true,
                                $_REQUEST['name']);
    $hits = new field_text_int("hits",
                               "Хиты",
                                true,
                                $_REQUEST['hits']);
    $pos = new field_text_int("pos",
                               "Позиция",
                                true,
                                $_REQUEST['pos']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
  
    $form = new form(array("name"        => $name, 
                           "hits"        => $hits,
                           "pos"         => $pos,
                           "page"        => $page,
                           "id_catalog"  => $id_catalog,
                           "id_position" => $id_position), 
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
        // Формируем SQL-запрос на добавление
        // новостного сообщения
        $query = "UPDATE $tbl_poll_answer
                  SET name = '{$form->fields[name]->value}',
                      pos = {$form->fields[pos]->value},
                      hits = {$form->fields[hits]->value}
                  WHERE id_catalog = {$form->fields[id_catalog]->value} AND
                        id_position = {$form->fields[id_position]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка редактирования позиции");
        }
        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: answers.php?".
               "id_catalog={$form->fields[id_catalog]->value}&".
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
    // Выводим сообщения об ошибках, если они имеются
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
