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
    $query = "SELECT * FROM $tbl_contactaddress LIMIT 1";
    $cnt = mysql_query($query);
    if(!$cnt)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении к 
                               контактной информации");
    }
    $contact = mysql_fetch_array($cnt);
    if(empty($_POST)) $_REQUEST = $contact;
  
    // Телефон
    $phone   = new field_textarea("phone",
                                  "Телефоны",
                                  false,
                                  $_REQUEST['phone']);
    // Факс
    $fax     = new field_textarea("fax",
                                  "Факс",
                                  false,
                                  $_REQUEST['fax']);
    // Ссылка
    $email   = new field_textarea("email",
                                  "E-mail",
                                  false,
                                  $_REQUEST['email']);
    // Адрес
    $address = new field_textarea("address",
                                  "Адрес",
                                  false,
                                  $_REQUEST['address']);
    // Инициируем форму массивом из двух элементов
    // управления - поля ввода name и текстовой области
    // textarea
    $form = new form(array("phone" => $phone, 
                          "fax" => $fax, 
                          "email" => $email,
                          "address" => $address), 
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
        // Формируем SQL-запрос на добавление позиции
        $query = "UPDATE $tbl_contactaddress
                  SET phone = '{$form->fields[phone]->value}',
                      fax = '{$form->fields[fax]->value}',
                      email = '{$form->fields[email]->value}',
                      address = '{$form->fields[address]->value}'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании 
                                   контактной информации");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: index.php");
        exit();
      }
    }
    // Данные переменные определяют название страницы и подсказку.
    $title = "Редактирование контактной информации";
    $pageinfo='<p class="help"></p>';
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