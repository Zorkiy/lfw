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
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем классы формы
  require_once("../../config/class.config.dmn.php");

  try
  {
    // Отмечам флажок block
    if(empty($_POST)) $_REQUEST['block'] = false;

    $text = "Поля, отмеченные звёздочкой *, являются обязательными к заполнению";
    $form_comment = new field_paragraph($text);
  
    $name = new field_text("name",
                           "Ник",
                           true,
                           $_REQUEST['name']);
    $pass = new field_password("pass",
                               "Пароль",
                               true,
                               $_REQUEST['pass']);
    $passagain = new field_password("passagain",
                               "Повтор",
                               true,
                               $_REQUEST['passagain']);
    $email = new field_text_email("email",
                                 "E-mail",
                                  true,
                                  $_REQUEST['email']);
    $block = new field_checkbox("block",
                               "Блокировать",
                               $_REQUEST['block']);
    $dateregister  = new field_datetime("dateregister",
                                  "Дата регистрации",
                                  $_REQUEST['dateregister']);
    $lastvisit  = new field_datetime("lastvisit",
                                  "Дата последнего визита",
                                  $_REQUEST['lastvisit']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass, 
                           "passagain"    => $passagain,
                           "email"        => $email,
                           "block"        => $block,
                           "dateregister" => $dateregister,
                           "lastvisit"    => $lastvisit,
                           "page"         => $page),
                     "Добавить",
                     "field");

    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      // Проверяем равны ли пароли
      if($form->fields['pass']->value != 
         $form->fields['passagain']->value)
      {
        $error[] = "Пароли не равны";
      }

      // Проверяем не зарегистрирован ли пользователь
      // с аналогичным ником ранее
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка добавления 
                               нового пользователя");
      }
      if(mysql_result($usr, 0))
      {
        $error[] = "Пользователь с таким именем уже 
                    существует";
      }

      if(empty($error))
      {
        // Заблокирован пользователь или нет
        if($form->fields['block']->value) $block = "block";
        else $block = "unblock";
        // Формируем SQL-запрос на добавление позиции
        $query = "INSERT INTO $tbl_users
                  VALUES (NULL,
                         '{$form->fields[name]->value}',
                         '{$form->fields[pass]->value}',
                         '{$form->fields[email]->value}',
                         '$block',
                         '{$form->fields[dateregister]->get_mysql_format()}',
                         '{$form->fields[lastvisit]->get_mysql_format()}')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка добавления нового пользователя");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: index.php?page={$form->fields[page]->value}");
        exit();
      }
    }

    // Начало страницы
    $title     = 'Добавление пользователя';
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