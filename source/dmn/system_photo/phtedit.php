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
  // Подключаем функцию изменения размера изображения
  require_once("../utils/utils.resizeimg.php");

  try
  {
    // Защита от SQL-инъекции
    $_GET['id_catalog']   = intval($_GET['id_catalog']);
    $_GET['id_position']   = intval($_GET['id_position']);

    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_photo_position
                WHERE id_position = $_GET[id_position] AND
                      id_catalog = $_GET[id_catalog]";
      $pos = mysql_query($query);
      if(!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при извлечении 
                                 текущей позиции");
      }
      $_REQUEST = mysql_fetch_array($pos);
      $_REQUEST['page'] = $_GET['page'];
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else$_REQUEST['hide'] = false;
    }

    $name = new field_text("name",
                           "Название",
                           false,
                           $_REQUEST['name']);
    $ename = new field_text("ename",
                           "Название&nbsp;(en)",
                           false,
                           $_REQUEST['ename']);
    $alt = new field_text("alt",
                           "ALT-тэг",
                           false,
                           $_REQUEST['alt']);
    $ealt = new field_text("ealt",
                           "ALT-тег&nbsp;(en)",
                           false,
                           $_REQUEST['ealt']);
    $big   = new field_file("big",
                            "Изображение",
                             false,
                             $_FILES,
                            "../../files/photo/");
    $pollnumber = new field_text_int("pollnumber",
                                     "Количество проголосовавших",
                                      false,
                                     $_REQUEST['pollnumber']);
    $pollmark = new field_text_int("pollmark",
                                   "Количество голосов",
                                    false,
                                    $_REQUEST['pollmark']);
    $countwatch = new field_text_int("countwatch",
                                     "Количество просмотров",
                                      false,
                                      $_REQUEST['countwatch']);
    $hide = new field_checkbox("hide",
                               "Отображать",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name"       => $name,
                           "ename"      => $ename,
                           "alt"        => $alt, 
                           "ealt"       => $ealt,
                           "big"        => $big,
                           "pollnumber" => $pollnumber,
                           "pollmark"   => $pollmark,
                           "countwatch" => $countwatch,
                           "hide"       => $hide, 
                           "id_catalog" => $id_catalog,
                           "id_position" => $id_position,
                           "page"       => $page), 
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
        // Скрытый или открытая позиция
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // Обрабатываем изображения
        $big = $small = "";
        if(!empty($_FILES['big']['name']))
        {
          // Удаляем старые изображения
          $query = "SELECT * FROM $tbl_photo_position
                    WHERE id_position = $_GET[id_position] AND
                          id_catalog = $_GET[id_catalog]";
          $pos = mysql_query($query);
          if(!$pos)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при извлечении 
                                     текущей позиции");
          }
          $position = mysql_fetch_array($pos);
          if(file_exists("../../".$position['big']))
            @unlink("../../".$position['big']);
          if(file_exists("../../".$position['small']))
            @unlink("../../".$position['small']);
          // Новые изображения
          $var = $form->fields['big']->get_filename();
          if(!empty($var))
          {
            $big = "big = 'files/photo/$var',";
            $small = "small = 'files/photo/s_$var',";
          }
          // Извлекаем параметры галереи
          $query = "SELECT * FROM $tbl_photo_settings LIMIT 1";
          $set = mysql_query($query);
          if(!$set)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при извлечении 
                                     параметров галереи");
          }
          if(mysql_num_rows($set))
          {
            $settings = mysql_fetch_array($set);
          }
          else
          {
            $settings['width'] = 150;
            $settings['height'] = 133;
          }
          // Формируем малое изображение
          resizeimg("files/photo/$var", "files/photo/s_$var", $settings['width'], $settings['height']);
        }
        // Формируем SQL-запрос на обновление позиции
        $query = "UPDATE $tbl_photo_position
                  SET name  = '{$form->fields[name]->value}',
                      ename = '{$form->fields[ename]->value}',
                      alt = '{$form->fields[alt]->value}',
                      ealt = '{$form->fields[ealt]->value}',
                      $small
                      $big
                      pollnumber = '{$form->fields[pollnumber]->value}',
                      pollmark = '{$form->fields[pollmark]->value}',
                      countwatch = '{$form->fields[countwatch]->value}',
                      hide = '$showhide'
                  WHERE id_position = {$form->fields[id_position]->value} AND
                        id_catalog = {$form->fields[id_catalog]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при обновлении
                                   позиции");
        }
        // Осуществляем редирект на главную страницу
        header("Location: photos.php?".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Редактирование изображения';
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