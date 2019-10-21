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
  // Выполнение SQL-запроса
  require_once("utils.query_result.php");

  try
  {
    if(empty($_POST))
    {
      // Предотвращаем SQL-инъекцию
      $_GET['part'] = intval($_GET['part']);
      $_GET['id_link'] = intval($_GET['id_link']);
      $query = "SELECT * FROM $tbl_links 
                WHERE part = $_GET[part] AND
                      id_links = $_GET[id_link]
                LIMIT 1";
      $lnk = mysql_query($query);
      if(!$lnk)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка изменения статуса ссылки");
      }
      if(mysql_num_rows($lnk))
      {
        $_REQUEST = mysql_fetch_array($lnk);
        if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
        else $_REQUEST['hide'] = false;
      }
      $_REQUEST['part'] = $_GET['part'];
      $_REQUEST['id_link'] = $_GET['id_link'];
    }

    $name = new field_text("name",
                           "Название темы",
                            true,
                            $_REQUEST['name']);
    $url = new field_text("url",
                          "URL",
                           true,
                           $_REQUEST['url']);
    $pos = new field_text("pos",
                          "Позиция",
                           true,
                           $_REQUEST['pos']);
    $hide = new field_checkbox("hide",
                             "Отображать",
                             $_REQUEST['hide']);
    $part = new field_hidden_int("part",
                            true,
                            $_REQUEST['part']);
    $id_link = new field_hidden_int("id_link",
                            true,
                            $_REQUEST['id_link']);
    $page = new field_hidden_int("page",
                            false,
                            $_REQUEST['page']);

    $form = new form(array("name"    => $name, 
                           "url"     => $url,
                           "pos"     => $pos,
                           "part"    => $part,
                           "hide"    => $hide,
                           "id_link" => $id_link,
                           "page"    => $page), 
                     "Редактировать",
                     "field");

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

        // Формируем и выполняем запрос на добавление ссылки
        $query = "UPDATE $tbl_links
                  SET name = '{$form->fields[name]->value}',
                      url = '{$form->fields[url]->value}',
                      hide = '$showhide',
                      pos = {$form->fields[pos]->value}
                  WHERE part = {$form->fields[part]->value} AND
                        id_links = {$form->fields[id_link]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании позиции");
        }
        header("Location: links.php?part={$form->fields[part]->value}&page={$form->fields[page]->value}");
        exit();
      }
    }

    // Начало страницы
    $title     = 'Редактирование ссылки';
    $pageinfo  = '<p class=help></p>';
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