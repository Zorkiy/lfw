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
    if(empty($_POST))
    {
      $_GET['id_position'] = intval($_GET['id_position']);
      $query = "SELECT * FROM $tbl_users
                WHERE id_position=$_GET[id_position]
                LIMIT 1";
      $usr = mysql_query($query);
      if(!$usr)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                 "Ошибка редактирования пользователя");
      }
      unset($_REQUEST);
      $_REQUEST = mysql_fetch_array($usr);
      $_REQUEST['dateregister']['month']  = substr($_REQUEST['dateregister'], 5, 2);
      $_REQUEST['dateregister']['day']    = substr($_REQUEST['dateregister'], 8, 2);
      $_REQUEST['dateregister']['year']   = substr($_REQUEST['dateregister'], 0, 4);
      $_REQUEST['dateregister']['hour']   = substr($_REQUEST['dateregister'], 11, 2);
      $_REQUEST['dateregister']['minute'] = substr($_REQUEST['dateregister'], 14, 2);
      unset($_REQUEST['dateregister']);
      if($_REQUEST['block'] == 'block') $_REQUEST['block'] = true;
      else $_REQUEST['block'] = false;
      $_REQUEST['page']                   = $_GET['page'];
      $_REQUEST['id_position']            = $_GET['id_position'];
      $_REQUEST['begin_date']             = $_GET['begin_date'];
      $_REQUEST['end_date']               = $_GET['end_date'];
      $_REQUEST['lastvisit']['month']     = substr($_REQUEST['lastvisit']['lastvisit'], 5, 2);
      $_REQUEST['lastvisit']['day']       = substr($_REQUEST['lastvisit']['lastvisit'], 8, 2);
      $_REQUEST['lastvisit']['year']      = substr($_REQUEST['lastvisit']['lastvisit'], 0, 4);
      $_REQUEST['lastvisit']['hour']      = substr($_REQUEST['lastvisit']['lastvisit'], 11, 2);
      $_REQUEST['lastvisit']['minute']    = substr($_REQUEST['lastvisit']['lastvisit'], 14, 2);
      unset($_REQUEST['lastvisit']);
      $_REQUEST['passagain']              = $_REQUEST['pass'];
    }

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
    $email = new field_text("email",
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
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $begin_date = new field_hidden_int("begin_date",
                                 false,
                                 $_REQUEST['begin_date']);
    $end_date = new field_hidden_int("end_date",
                                 false,
                                 $_REQUEST['end_date']);

    $form = new form(array("form_comment"     => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass, 
                           "passagain"    => $passagain,
                           "email"        => $email,
                           "block"        => $block,
                           "dateregister" => $dateregister,
                           "lastvisit"    => $lastvisit,
                           "id_position"  => $id_position,
                           "page"         => $page,
                           "begin_date"   => $begin_date,
                           "end_date"     => $end_date),
                     "Редактировать",
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
      // с аналогичным именем ранее
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}' AND 
                      id_position != {$form->fields[id_position]->value}";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка редактирования пользователя");
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

        // Формируем SQL-запрос на редактирование позиции
        $query = "UPDATE $tbl_users
                  SET name = '{$form->fields[name]->value}',
                      pass = '{$form->fields[pass]->value}',
                      email = '{$form->fields[email]->value}',
                      block = '$block',
                      dateregister = '{$form->fields[dateregister]->get_mysql_format()}',
                      lastvisit = '{$form->fields[lastvisit]->get_mysql_format()}'
                 WHERE id_position={$form->fields[id_position]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка
                                   редактировании данных 
                                   пользователя");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: index.php?begin_date={$form->fields[begin_date]->value}&".
               "end_date={$form->fields[end_date]->value}");
        exit();
      }
    }

    // Начало страницы
    $title     = 'Редактирование данных пользователей';
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