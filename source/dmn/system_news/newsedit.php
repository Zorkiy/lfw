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

  // Предотвращаем SQL-инъекцию
  $_GET['id_news'] = intval($_GET['id_news']);

  try
  {
    // Извлекаем из таблицы news запись, соответствующую
    // исправляемому новостному сообщению
    $query = "SELECT * FROM $tbl_news
              WHERE id_news=$_GET[id_news]";
    $new = mysql_query($query);
    if(!$new)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении
                               к таблице новостей");
    }
    $news = mysql_fetch_array($new);
    if(empty($_POST))
    {
      // Берем информацию для оставшихся переменных из базы данных
      $_REQUEST = $news;
      $_REQUEST['date']['month']  = substr($news['putdate'],5,2);
      $_REQUEST['date']['day']    = substr($news['putdate'],8,2);
      $_REQUEST['date']['year']   = substr($news['putdate'],0,4);
      $_REQUEST['date']['hour']   = substr($news['putdate'],11,2);
      $_REQUEST['date']['minute'] = substr($news['putdate'],14,2);
      // Определяем, скрыто поле или нет
      if($news['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }
  
    $name        = new field_text("name",
                                  "Название",
                                  true,
                                  $_REQUEST['name']);
    $body = new field_textarea("body",
                               "Содержимое",
                               true,
                               $_REQUEST['body']);
    $url         = new field_text("url",
                                  "Ссылка",
                                  false,
                                  $_REQUEST['url']);
    $urltext    = new field_text("urltext",
                                  "Текст ссылки",
                                  false,
                                  $_REQUEST['urltext']);
    $date        = new field_datetime("date",
                                  "Дата новости",
                                  $_REQUEST['date']);
    $hide        = new field_checkbox("hide",
                               "Отображать",
                               $_REQUEST['hide']);
    $filename   = new field_file("filename",
                                 "Изображение",
                                 false,
                                 $_FILES,
                                 "../../files/news/");
    $id_news    = new field_hidden_int("id_news",
                                       true,
                                       $_REQUEST['id_news']);
    $page       = new field_hidden_int("page",
                                       false,
                                       $_REQUEST['page']);
    // Инициируем форму массивом из двух элементов
    // управления - поля ввода name и текстовой области
    // textarea
    if(empty($news['urlpict']))
    {
      $form = new form(array("name" => $name, 
                            "body" => $body, 
                            "url" => $url,
                            "urltext" => $urltext,
                            "date" => $date,
                            "hide" => $hide,
                            "filename" => $filename,
                            "id_news" => $id_news,
                            "page" => $page), 
                    "Редактировать",
                    "field");
    }
    else
    {
      // Удаление изображения
      $delimg = new field_checkbox("delimg",
                               "Удалить изображение",
                               $_REQUEST['delimg']);
      $form = new form(array("name" => $name, 
                            "body" => $body, 
                            "url" => $url,
                            "urltext" => $urltext,
                            "date" => $date,
                            "hide" => $hide,
                            "delimg" => $delimg,
                            "filename" => $filename, 
                            "id_news" => $id_news,
                            "page" => $page), 
                    "Редактировать",
                    "field");
    }

    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Скрытый или открытый каталог
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // Удаляем старые файлы, если они имеются
        $url_pict = "";
        $str = $form->fields['delimg']->value;
        if(!empty($str) || !empty($_FILES['filename']['name']))
        {
          $path = str_replace("//","/","../../".$news['urlpict']);
          if(file_exists($path))
          {
            @unlink($path);
          }
          $url_pict = "urlpict = '',";
        }
        if(!empty($_FILES['filename']['name']))
        {
          $url_pict = "urlpict = 'files/news/".
                       $form->fields['filename']->get_filename()."',";
        }
        // Формируем SQL-запрос на добавление новости
        $query = "UPDATE $tbl_news 
                  SET name = '{$form->fields['name']->value}',
                      body = '{$form->fields['body']->value}',
                 putdate = '{$form->fields['date']->get_mysql_format()}',
                      url = '{$form->fields['url']->value}',
                      urltext = '{$form->fields['urltext']->value}',
                      $url_pict
                      hide = '{$showhide}'
                  WHERE id_news=".$form->fields['id_news']->value;
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании 
                                   новостного сообщения");
        }
        // Осуществляем переадресацию на главную страницу
        // администрирования
        header("Location: index.php?page={$form->fields[page]->value}");
        exit();
      }
    }

    // Данные переменные определяют название страницы и подсказку.
    $title = "Редактирование новости";
    $pageinfo='<p class="help"></p>';
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
