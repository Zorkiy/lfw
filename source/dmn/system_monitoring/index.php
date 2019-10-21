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

  // Снимаем ограничение на время выполнения скрипта
  @set_time_limit(0);
  // Устанавливаем соединение с базой данных
  require_once("../../config/config.php");
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем классы формы
  require_once("../../config/class.config.dmn.php");
  // Вспомогательные функции yandex(), google(), rambler(), aport()
  require_once("utils.php");
  
  try
  {
    $keywords = new field_text("keywords",
                           "Ключевые слова",
                            true,
                            $_REQUEST['keywords']);
    $site = new field_text("site",
                           "Сайт",
                            true,
                            $_REQUEST['site']);
    $search = new field_select("search",
                           "Поисковая система",
                            array("all"     => "Все",
                                  "yandex"  => "Яндекс",
                                  "google"  => "Google",
                                  "rambler" => "Рамблер",
                                  "aport"   => "Апорт"),
                            $_REQUEST['search']);

    $form = new form(array("keywords" => $keywords,
                           "site"     => $site, 
                           "search"   => $search), 
                     "Искать",
                     "field");

    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
    }

    // Начало страницы
    $title     = 'Мониторинг позиции сайта в поисковых системах';
    $pageinfo  = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
  
    // Выводим сообщения об ошибках если они имеются
    if(!empty($error))
    {
        echo "<span style=\"color:red\">".
             implode("<br>", $error).
             "</span><br>";
    }
    // Выводим HTML-форму 
    $form->print_form();

    if(empty($error) && !empty($_POST))
    {
      echo "<p class=help>";
      if($form->fields['search']->value != 'all')
      {
        echo search($form->fields['keywords']->value,
                    $form->fields['site']->value,
                    $form->fields['search']->value);
      }
      else
      {
        $array = array('yandex', 'google', 'rambler', 'aport');
        $keywords = $form->fields['keywords']->value;
        $site = $form->fields['site']->value;
        foreach($array as $value)
        {
          echo search($keywords,
                      $site,
                      $value);
          echo "<br>";
        }
      }
      echo "</p>";
    }
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