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
  // Постраничная навигация
  require_once("../utils/utils.pager.php");
  // Выполнение SQL-запроса
  require_once("utils.query_result.php");
  // Подключаем функции для работы со временем
  require_once("../../utils/utils.time.php");
  // Подключаем функции для работы с пользователями
  require_once("../../utils/utils.users.php");
  // Настройки форума
  require_once("../../utils/utils.settings.php");

  try
  {
    // Извлекаем название форума из строки запроса
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $id_post  = intval($_GET['id_post']);
    // Запрашиваем информацию из базы данных
    $query = "SELECT * FROM $tbl_posts
              WHERE id_post = $id_post
              LIMIT 1";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении 
                               к сообщению");
    }
    $posts = mysql_fetch_array($pst);
    if(empty($_POST))
    {
      $_REQUEST = $posts;
    }

    $author = new field_text("author",
                           "Автор",
                            true,
                            $_REQUEST['author']);
    $name = new field_textarea("name",
                           "Сообщение",
                            true,
                            $_REQUEST['name'],
                            60,
                            15);
    $delete = new field_checkbox("delete",
                               "Удалить вложение",
                               $_REQUEST['delete']);
    $id_forum = new field_hidden_int("id_forum",
                            true,
                            $_REQUEST['id_forum']);
    $id_theme = new field_hidden_int("id_theme",
                            true,
                            $_REQUEST['id_theme']);
    $id_post = new field_hidden_int("id_post",
                            true,
                            $_REQUEST['id_post']);

    if(!empty($posts['putfile']) && $posts['putfile'] != '-')
    {
      $array = array("author"     => $author, 
                     "name"       => $name,
                     "delete"     => $delete,
                     "id_theme"   => $id_theme,
                     "id_forum"   => $id_forum,
                     "id_post"    => $id_post);
    }
    else
    {
      $array = array("author"     => $author, 
                     "name"       => $name,
                     "id_theme"   => $id_theme,
                     "id_forum"   => $id_forum,
                     "id_post"    => $id_post);
    }
    $form = new form($array, 
                     "Редактировать",
                     "field");

    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Удаляем вложение
        $update_path = '';
        if($form->fields['delete']->value)
        {
          // Удаляем прикрпелённое сообщение
          @unlink("../../forum/".$posts['putfile']);
          $update_path = "putfile = '',";
        }
        // Обновляем сообщение
        $query = "UPDATE $tbl_posts 
                  SET $update_path
                      name = '{$form->fields[name]->value}',
                      author = '{$form->fields[author]->value}'
                  WHERE id_post = {$form->fields[id_post]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка обновления сообщения");
        }
        $header = "Location: posts.php?".
                  "id_forum={$form->fields[id_forum]->value}&".
                  "id_theme={$form->fields[id_theme]->value}";
        header($header);
        exit();
      }
    }

    $title = 'Редактирование сообщения';
    $pageinfo = '';
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