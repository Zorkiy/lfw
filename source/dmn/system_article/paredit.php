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
  $_GET['id_catalog']   = intval($_GET['id_catalog']);
  $_GET['id_position']  = intval($_GET['id_position']);
  $_GET['id_paragraph'] = intval($_GET['id_paragraph']);
  try
  {
    if(empty($_POST))
    {
      $query = "SELECT * FROM $tbl_paragraph
                WHERE id_catalog=$_GET[id_catalog] AND
                      id_position=$_GET[id_position] AND
                      id_paragraph=$_GET[id_paragraph]
                LIMIT 1";
      $par = mysql_query($query);
      if(!$par)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении 
                                 к позиции");
      }
      $_REQUEST = mysql_fetch_array($par);
      $_REQUEST['page'] = $_GET['page'];
      if($_REQUEST['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }

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
    $id_paragraph = new field_hidden_int("id_paragraph",
                                 true,
                                 $_REQUEST['id_paragraph']);
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);
    $form = new form(array("name" => $name,
                           "type" => $type, 
                           "align" => $align, 
                           "hide" => $hide,
                           "id_catalog" => $id_catalog,
                           "id_position" => $id_position,
                           "id_paragraph" => $id_paragraph,
                           "page" => $page), 
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
        // Формируем SQL-запрос на редактирование позиции
        $query = "UPDATE $tbl_paragraph
                  SET name = '{$form->fields['name']->value}',
                      type = '{$form->fields['type']->value}',
                      align = '{$form->fields['align']->value}',
                      hide = '$showhide'
                  WHERE id_position = {$form->fields['id_position']->value} AND
                        id_catalog = {$form->fields['id_catalog']->value} AND
                        id_paragraph = {$form->fields['id_paragraph']->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при редактировании 
                                   параграфа");
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
    $title     = 'Редактирование параграфа';
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