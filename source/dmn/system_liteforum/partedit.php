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
    // Извлекаем название форума из строки запроса
    $id_forum = intval($_GET['id_forum']);
    // Запрашиваем информацию из базы данных
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_forums
                WHERE id_forum = $id_forum
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
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }

    $name = new field_text("name",
                           "Название",
                            true,
                            $_REQUEST['name']);
    $rule = new field_textarea("rule",
                           "Правила форума",
                            true,
                            $_REQUEST['rule']);
    $logo = new field_textarea("logo",
                           "Краткое описание",
                            true,
                            $_REQUEST['logo']);
    $pos = new field_text_int("pos",
                           "Позиция",
                            true,
                            $_REQUEST['pos']);
    $id_forum = new field_hidden_int("id_forum",
                            true,
                            $_REQUEST['id_forum']);
    $hide = new field_checkbox("hide",
                           "Отображать",
                            $_REQUEST['hide']);
  
    $form = new form(array("name"     => $name, 
                           "rule"     => $rule,
                           "logo"     => $logo,
                           "pos"      => $pos,
                           "id_forum" => $id_forum,
                           "hide"     => $hide), 
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
        // Формируем SQL-запрос на добавление
        // новостного сообщения
        $query = "UPDATE $tbl_forums
                  SET name = '{$form->fields[name]->value}',
                      rule = '{$form->fields[rule]->value}',
                      logo = '{$form->fields[logo]->value}',
                      pos  = {$form->fields[pos]->value},
                      hide = '$showhide'
                  WHERE id_forum = {$form->fields[id_forum]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка редактирования раздела");
        }

        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: index.php");
        exit();
      }
    }

    // Начало страницы
    $title     = 'Редактирование раздела';
    $pageinfo  = '<p class=help>Для того, чтобы отредактировать раздел измените 
    информацию в текстовых полях и нажмите кнопку "Исправить"</p>';
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