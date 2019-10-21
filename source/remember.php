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
    // Если пользователь уже авторизован - высылаем
    // ему на почту пароль
    if(!empty($_SESSION['name']))
    {
      // Отправляем пароль пользователю
      remember($_SESSION['name']);
      // Переходим на страницу, сообщающую об успешной отправке пароля
      header("Location: remember_success.php");
      exit();
    }

    // Комментарий
    $text = "Поля, отмеченные звёздочкой *, являются ".
            "обязательными к заполнению";
    $form_comment = new field_paragraph($text);
  
    $name = new field_text("name",
                           msg("Ник"),
                           true,
                           $_REQUEST['name']);
    $form = new form(array("form_comment" => $form_comment,
                           "name"         => $name),
                     "Выслать пароль",
                     "main_txt",
                     "",
                     "in_input");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
  
      // Проверяем не зарегистрирован ли пользователь
      // с аналогичным именем ранее
      $query = "SELECT COUNT(*) FROM $tbl_users 
                WHERE name = '{$form->fields[name]->value}'";
      $usr = mysql_query($query);
      if(!$usr) 
      {
        throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при восстановлении пароля");
      }
      if(!mysql_result($usr, 0))
      {
        $error[] = msg("Пользователь с таким именем не существует");
      }
  
      if(empty($error))
      {
        // Отправляем пароль пользователю
        remember($form->fields['name']->value);
  
        // Переходим на страницу, сообщающую об успешной отправке пароля
        header("Location: remember_success.php");
        exit();
      }
    }

    // Подключаем верхний шаблон
    $pagename = "Вспомнить пароль";
    $keywords = "Вспомнить пароль";
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

    // Подключаем нижний шаблон
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