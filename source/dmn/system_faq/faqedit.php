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
    // Защищаем GET-параметр от SQL-инъекции
    $_GET['id_position'] = intval($_GET['id_position']);
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_faq
                WHERE id_position=$_GET[id_position]
                LIMIT 1";
      $cat = mysql_query($query);
      if(!$cat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении 
                                 к позиции");
      }
      $_REQUEST = mysql_fetch_array($cat);
      $_REQUEST['page'] = $_GET['page'];
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }
  
    $question = new field_textarea("question",
                                   "Вопрос",
                                   true,
                                   $_REQUEST['question']);
    $answer = new field_textarea("answer",
                                 "Ответ",
                                 true,
                                 $_REQUEST['answer']);
    $pos = new field_text_int("pos",
                              "Позиция",
                              true,
                              $_REQUEST['pos']);
    $hide        = new field_checkbox("hide",
                                      "Отображать",
                                      $_REQUEST['hide']);
    $page    = new field_hidden_int("page",
                                    false,
                                    $_REQUEST['page']);
    $id_position = new field_hidden_int("id_position",
                                        true,
                                        $_REQUEST['id_position']);

    $form = new form(array("question" => $question,
                           "answer" => $answer, 
                           "pos" => $pos,
                           "hide" => $hide,
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
        // Скрытая или открытая позиция
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // Формируем SQL-запрос на редактирование позиции
        $query = "UPDATE $tbl_faq
                  SET question = '{$form->fields[question]->value}',
                      answer   = '{$form->fields[answer]->value}',
                      hide     = '$showhide',
                      pos      = '{$form->fields[pos]->value}'
                WHERE id_position = {$form->fields[id_position]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании 
                                   позиции");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: index.php?".
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