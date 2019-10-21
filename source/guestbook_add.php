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
  require_once("config/config.php");
  // Подключаем SoftTime FrameWork
  require_once("config/class.config.php");
  // Подключаем функцию вывода текста с bbCode
  require_once("dmn/utils/utils.print_page.php");
  // Подключаем заголовок 
  require_once("utils.title.php");

  try
  {
    $name = new field_text("name",
                           "Имя",
                           true,
                           $_POST['name']);
    $city = new field_text("city",
                           "Город",
                            false,
                            $_POST['city']);
    $msg = new field_textarea("msg",
                              "Сообщение",
                               true,
                               $_POST['msg'],
                               70,
                               10);
    $text = "С целью предотвращения спама (рекламных объявлений), сообщения содержащие ссылки блокируются.";
    $warning = new field_paragraph($text);
    $form = new form(array("name" => $name,
                           "city" => $city, 
                           "msg" => $msg,
                           "warning" => $warning), 
                     "Добавить сообщение",
                     "main_txt",
                     "",
                     "in_input");
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      // Исключаем наличие URL в сообщении
      if(preg_match("|http://|i", $form->fields['msg']->value) ||
         preg_match("|http://|i", $form->fields['city']->value) ||
         preg_match("|http://|i", $form->fields['name']->value)) 
      {
        $error[] = "В гостевой книге не допускается 
                    использование URL";
      }
      if(preg_match("|www\.|i", $form->fields['msg']->value) ||
         preg_match("|www\.|i", $form->fields['city']->value) ||
         preg_match("|www\.|i", $form->fields['name']->value)) 
      {
        $error[] = "В гостевой книге не допускается 
                    использование URL";
      }
      // Запрещаем сообщения исключительно на английском языке
      if(!preg_match("|[а-яё]|i", $form->fields['msg']->value))
      {
        $error[] = "В гостевой книге не допускается 
                   сообщения подобного формата";
      }
      // Если все проверки успешно пройдены - добавляем сообщение
      if(empty($error))
      {
        // Формируем SQL-запрос на добавление позиции
        $query = "INSERT INTO $tbl_guestbook
                  VALUES (NULL,
                          '{$form->fields[name]->value}',
                          '{$form->fields[city]->value}',
                          '{$form->fields[msg]->value}',
                          '',
                          NOW(),
                          'show')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка при добавлении
                                   новой позиции");
        }
        // Осуществляем редирект на основную страницу
        header("Location: guestbook.php");
        exit();
      }
    }

    // Подключаем верхний шаблон
    $pagename = "Гостевая книга (добавить сообщение)";
    $keywords = "Гостевая книга";
    require_once ("templates/top.php");

    // Название страницы
    echo title($pagename);

    ?>
    <p class=main_txt>
        Теги для выделения текста:
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[b]', '[/b]'); return false;" >
           [b]<b>Жирный</b>[/b]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[i]', '[/i]'); return false;">
           [i]<i>Наклонный</i>[/i]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[u]', '[/u]'); return false;" >
           [u]<u>Подчеркнутый</u>[/u]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[sup]', '[/sup]'); return false;" >
           [sup]<sup>Верхний индекс</sup>[/sup]</a>,
        <a class=main_txt_lnk href=# 
           onClick="javascript:tag('[sub]', '[/sub]'); return false;" >
           [sub]<sub>Нижний индекс</sub>[/sub]</a>
    </p>
    <?php
    // Выводим сообщения об ошибках если они имеются
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\" class=main_txt>$err</span><br>";
      }
    }
    // Выводим HTML-форму 
    $form->print_form();

    //Подключаем нижний шаблон
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
<script language='JavaScript1.1' type='text/javascript'>
<!--
  function tag(text1, text2)
  {
     if ((document.selection))
     {
       document.form.msg.focus();
       document.form.document.selection.createRange().text =
       text1+document.form.document.selection.createRange().text + text2;
     } else document.form.msg.value += text1+text2;
  }
//-->
</script>