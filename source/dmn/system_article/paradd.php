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

  // Защита от SQL-инъекции
  $_GET['id_position'] = intval($_GET['id_position']);
  $_GET['id_catalog']  = intval($_GET['id_catalog']);

  if(empty($_POST)) $_REQUEST['hide'] = true;
  try
  {
    $name = new field_textarea("name",
                           "Содержимое",
                           true,
                           $_REQUEST['name'],
                           50,
                           15);
    $type      = new field_select("type",
                                  "Тип параграфа",
                                  array("text" => "Параграф",
                                        "title_h1" => "Заголовок H1",
                                        "title_h2" => "Заголовок H2",
                                        "title_h3" => "Заголовок H3",
                                        "title_h4" => "Заголовок H4",
                                        "title_h5" => "Заголовок H5",
                                        "title_h6" => "Заголовок H6",
                                        "list" => "Список"),
                                  $_REQUEST['type']);
    $align      = new field_select("align",
                                  "Тип параграфа",
                                  array("left" => "Слева",
                                        "center" => "По центру",
                                        "right" => "Справа"),
                                  $_REQUEST['align']);
    $hide = new field_checkbox("hide",
                               "Отображать",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                                 true,
                                 $_REQUEST['id_catalog']);
    $id_position = new field_hidden_int("id_position",
                                 true,
                                 $_REQUEST['id_position']);
    $pos = new field_hidden("pos",
                                 false,
                                 $_REQUEST['pos']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name" => $name,
                           "type" => $type, 
                           "align" => $align, 
                           "hide" => $hide,
                           "id_catalog" => $id_catalog,
                           "id_position" => $id_position,
                           "pos" => $pos,
                           "page" => $page), 
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
        // Скрытый или открытая позиция
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        if(empty($form->fields['pos']->value))
        {    
          // Вставляем параграф в конец статьи
          // Извлекаем текущую максимальную позицию
          $query = "SELECT MAX(pos) FROM $tbl_paragraph
                    WHERE id_catalog={$form->fields[id_catalog]->value} AND
                          id_position={$form->fields[id_position]->value}";
          $pos = mysql_query($query);
          if(!$pos)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при извлечении 
                                   текущей позиции");
          }
          $pos = mysql_result($pos, 0) + 1;
        }
        else if($position_form->fields['pos']->value < 0)
        {
          // Вставляем параграф в начало статьи
          $query = "UPDATE $tbl_paragraph 
                    SET pos = pos + 1
                    WHERE id_catalog={$form->fields[id_catalog]->value} AND
                          id_position={$form->fields[id_position]->value}";
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании 
                                   позиции параграфа");
          }
          $pos = 1;
        }
        else
        {
          // Вставляем параграф в середину статьи
          $query = "UPDATE $tbl_paragraph 
                    SET pos = pos + 1
                    WHERE id_catalog={$form->fields[id_catalog]->value} AND
                          id_position={$form->fields[id_position]->value} AND
                          pos > {$form->fields[pos]->value}";
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании 
                                   позиции параграфа");
          }
          $pos = $form->fields['pos']->value + 1;
        }
        // Формируем SQL-запрос на добавление позиции
        $query = "INSERT INTO $tbl_paragraph 
                  VALUES (NULL,
                          '{$form->fields['name']->value}',
                          '{$form->fields['type']->value}',
                          '{$form->fields['align']->value}',
                          '$showhide',
                          $pos,
                          {$form->fields['id_position']->value},
                          {$form->fields['id_catalog']->value})";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при добавлении 
                                 нового параграфа");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: paragraph.php?".
               "id_position={$form->fields[id_position]->value}&".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Добавление параграфа';
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
    ?>
      <p class=help>
      Курсивый: <a href=# onClick="javascript:tag('[i]', '[/i]'); return false;">[i][/i]</a><br>
      Жирный: <a href=# onClick="javascript:tag('[b]', '[/b]'); return false;">[b][/b]</a><br>
      URL: <a href=# onClick="javascript:tag('[url]', '[/url]'); return false;" >[url][/url]</a></p>
    <?php
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
<script language='JavaScript' type='text/javascript'>
<!--
  function tag(text1, text2)
  {
     if ((document.selection))
     {
       document.form.name.focus();
       document.form.document.selection.createRange().text = text1+document.form.document.selection.createRange().text+text2;
     } else document.form.name.value += text1+text2;
  }
//-->
</script>