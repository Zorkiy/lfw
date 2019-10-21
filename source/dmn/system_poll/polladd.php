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

  if(empty($_POST))
  {
    // Отмечаем флажки
    $_REQUEST['hide']   = true;
    $_REQUEST['active'] = true;
  }
  try
  {
    $name = new field_textarea("name",
                               "Вопрос",
                                true,
                                $_POST['name']);
    $hide = new field_checkbox("hide",
                               "Отображать",
                                $_REQUEST['hide']);
    $active = new field_checkbox("active",
                                 "Сделать активным",
                                  $_REQUEST['active']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
  
    $form = new form(array("name"   => $name, 
                           "hide"   => $hide,
                           "active" => $active,
                           "page"   => $page), 
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
        // Скрытая или открытая позиция
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // Активное голосование или архивное
        if($form->fields['active']->value)
        {
          // Голосование активное - все существующие
          // голосования переводим в пассивный режим
          $query = "UPDATE $tbl_poll SET archive = 'archive'";
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка обновления 
                                     статуса позиций");
          }
          $status = "active";
        }
        else $status = "archive";
        // Формируем SQL-запрос на добавление
        // новостного сообщения
        $query = "INSERT INTO $tbl_poll
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '$status',
                          '$showhide',
                          NOW())";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка добавления позиции");
        }
        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: index.php?page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Добавление позиции';
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
