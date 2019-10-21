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
  // Упрвление пользователями enter(), user(), remember()
  require_once("utils.users.php");

  try
  {
    // Если пользователь уже авторизован - иницируем 
    // элементы HTML-формы
    if(!empty($_SESSION['id_user_position']))
    {
      // Возвращаем данные для пользователя с первичным
      // ключом id_user_position
      $user = user($_SESSION['id_user_position']);
      // Инициируем элементы HTML-формы
      $_REQUEST['name'] = $user['name'];
      $_REQUEST['pass'] = $user['pass'];
    }
    // Если данные в cookie не пусты - проверяем их
    if(!empty($_COOKIE['name']) && !empty($_COOKIE['pass']))
    {
      // Экранируем кавычки для предотвращения
      // SQL-инъекции
      if (!get_magic_quotes_gpc())
      {
        $_COOKIE['name'] = mysql_escape_string($_COOKIE['name']);
        $_COOKIE['pass'] = mysql_escape_string($_COOKIE['pass']);
      }
      // Осуществляем попытку авторизации с данными
      // расположенными в cookie
      if(enter($_COOKIE['name'], $_COOKIE['pass']))
      {
        // Авторизация пройдена успешно - инициируем
        // элементы HTML-формы
        $_REQUEST['name'] = $_COOKIE['name'];
        $_REQUEST['pass'] = $_COOKIE['pass'];
      }
    }

    // Формируем HTML-форму
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
    $remember = new field_checkbox("remember", 
                                   "Запомнить", 
                                   $_REQUEST['remember']);

  
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name, 
                           "pass"         => $pass,
                           "remember"     => $remember),
                     "Войти",
                     "main_txt",
                     "",
                     "in_input");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      
      // Проверяем имеется ли в базе данных пользователь
      // с указанным именем
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка извлечения параметров
                               пользователя");
      }
      if(!mysql_result($usr, 0))
      {
        $error[] = "Пользователь с таким именем не существует";
      }

      if(empty($error))
      {
        // Проверяем соответствует ли логин паролю
        if(enter($form->fields['name']->value, 
                 $form->fields['pass']->value))
        {
          if($form->fields['remember']->value)
          {
            // Если отмечен флажок "Запомнить", устанавливаем 
            // cookie на одну неделю в которую помещаем имя
            // пользователя и его пароль
            @setcookie("name", 
                       urlencode($form->fields['name']->value),
                       time() + 7*24*3600);
            @setcookie("pass", 
                       urlencode($form->fields['pass']->value),
                       time() + 7*24*3600);
          }
        }
        // Перегружаем страницу
        header("Location: $_SERVER[PHP_SELF]");
        exit();
      }
    }

    // Подключаем верхний шаблон
    $pagename = "Вход на сайт";
    $keywords = "Вход на сайт";
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