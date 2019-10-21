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

  // Инициируем сессию
  session_start();
  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Подключаем функцию вывода текста с bbCode
  require_once("dmn/utils/utils.print_page.php");
  // Подключаем заголовок 
  require_once("utils.title.php");

  try
  {
    $text = "Поля, отмеченные звёздочкой *, являются 
             обязательными к заполнению";
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
  
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass, 
                           "passagain"    => $passagain,
                           "email"        => $email),
                     "Зарегистрироваться",
                     "main_txt",
                     "",
                     "in_input");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      // Проверяем равны ли пароли
      if($form->fields['pass']->value != $form->fields['passagain']->value)
      {
        $error[] = "Пароли не равны";
      }
  
      // Проверяем не зарегистрирован ли пользователь
      // с аналогичным именем ранее
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка добавления нового пользователя");
      }
      if(mysql_result($usr, 0))
      {
        $error[] = "Пользователь с таким именем уже существует";
      }
  
      if(empty($error))
      {
        // Формируем SQL-запрос на добавление позиции
        $query = "INSERT INTO $tbl_users
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[pass]->value}',
                          '{$form->fields[email]->value}',
                          'unblock',
                          NOW(),
                          NOW())";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                   "Ошибка добавления 
                                   нового пользователя");
        }
        // Вход на сайт
        $_SESSION['name'] = $form->fields['name']->value;
        $_SESSION['id_user'] = mysql_insert_id();
        // Осуществляем редирект на страницу, сообщающую
        // об успешной регистрации
        header("Location: register_success.php");
        exit();
      }
    }

    // Подключаем верхний шаблон
    $pagename = "Регистрация на сайте";
    $keywords = "Регистрация на сайте";
    require_once ("templates/top.php");

    // Название страницы
    echo title($pagename);

    // Выводим сообщения об ошибках если они имеются
    if(!empty($error))
    {
      echo "<br>";
      foreach($error as $err)
      {
        echo "<span style=\"color:red\" class=main_txt>$err</span><br>";
      }
    }
    // Выводим HTML-форму 
    $form->print_form();

    //Подключаем нижний шаблон
    require_once ("templates/bottom.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>