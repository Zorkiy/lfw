<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) Кузнецов М.В., Симдянов И.В.
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
  require_once("config.php");
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");

  try
  {
    if(empty($_POST))
    {
      $_REQUEST['hide']   = true;
      // Извлекаем максимальную позицию
      $query = "SELECT MAX(pos) FROM $tbl_forums";
      $pos = mysql_query($query);
      if(!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при извлечении 
                                 максимальной позиции");
      }
      $_REQUEST['pos'] = mysql_result($pos, 0) + 1;
    }
    $name = new field_text("name",
                           "Название",
                            true,
                            $_POST['name']);
    $rule = new field_textarea("rule",
                           "Правила форума",
                            true,
                            $_POST['rule']);
    $logo = new field_textarea("logo",
                           "Краткое описание",
                            true,
                            $_POST['logo']);
    $pos = new field_text_int("pos",
                           "Позиция",
                            true,
                            $_REQUEST['pos']);
    $hide = new field_checkbox("hide",
                           "Отображать",
                            $_REQUEST['hide']);
  
    $form = new form(array("name" => $name, 
                           "rule" => $rule,
                           "logo" => $logo,
                           "pos"  => $pos,
                           "hide" => $hide), 
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
        // Формируем SQL-запрос на добавление
        // новостного сообщения
        $query = "INSERT INTO $tbl_forums
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[rule]->value}',
                          '{$form->fields[logo]->value}',
                          {$form->fields[pos]->value},
                          '$showhide')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка добавления нового раздела");
        }
        // Извлекаем первичный ключ только что добавленного раздела
        $id_forum = mysql_insert_id();

        // Необходимо добавить два новых столбца в таблицу $tbl_last_time
        // для того, чтобы время последнего посещения отсчитывалось
        // корректно
        $query = "ALTER TABLE $tbl_last_time 
                  ADD now$id_forum datetime NOT NULL ,
                  ADD last_time$id_forum datetime NOT NULL";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка редактирование таблицы");
        }
        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: index.php");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Добавить раздел';
    $pageinfo  = '<p class=help>Для того чтобы добавить раздел, 
    введите информацию в текстовые поля и нажмите кнопку "Добавить"</p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".implode("<br>", $error)."</span><br>";
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