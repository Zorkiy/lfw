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
    $id_author = intval($_GET['id_author']);
    // Запрашиваем информацию из базы данных
    $query = "SELECT * FROM $tbl_authors
              WHERE id_author = $id_author
              LIMIT 1";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении 
                               к позиции");
    }
    $author = mysql_fetch_array($ath);
    if(empty($_POST))
    {
      $_REQUEST = $author;
      // sendmail
      if($_REQUEST['sendmail'] == 'yes') $_REQUEST['sendmail'] = true;
      else $_REQUEST['sendmail'] = false;
      // Определяем статус пользователя
      if($_REQUEST['statususer'] == '') $_REQUEST['statususer'] = "user";
    }

    $name = new field_paragraph(htmlspecialchars($author['name'], ENT_QUOTES));
    $pass = new field_password("pass",
                           "Пароль",
                            false,
                            $_REQUEST['pass']);
    $passagain = new field_password("passagain",
                           "Повтор",
                            false,
                            $_REQUEST['passagain']);
    $email = new field_text_email("email",
                           "E-mail",
                            false,
                            $_REQUEST['email']);
    $sendmail = new field_checkbox("sendmail",
                           "Получать уведомления",
                            $_REQUEST['sendmail']);
    $url = new field_text("url",
                           "URL",
                            false,
                            $_REQUEST['url']);
    $icq = new field_text("icq",
                           "ICQ",
                            false,
                            $_REQUEST['icq']);
    $about = new field_textarea("about",
                           "О себе",
                            false,
                            $_REQUEST['about']);
    $photo = new field_checkbox("photo",
                           "Удалить фото?",
                            $_REQUEST['photo']);
    $themes = new field_text("themes",
                           "К-во сообщений",
                            false,
                            $_REQUEST['themes']);
    $statususer = new field_select("statususer",
                           "К-во сообщений",
                            array("moderator" => "Модератор",
                                  "admin" => "Администратор",
                                  "user" => "Пользователь"),
                            $_REQUEST['statususer']);
    $id_author = new field_hidden_int("id_author",
                            true,
                            $_REQUEST['id_author']);
    $page = new field_hidden_int("page",
                            false,
                            $_REQUEST['page']);
  
    $form = new form(array("name"       => $name, 
                           "pass"       => $pass,
                           "passagain"  => $passagain,
                           "email"      => $email,
                           "sendmail"   => $sendmail,
                           "url"        => $url,
                           "icq"        => $icq,
                           "about"      => $about,
                           "photo"      => $photo,
                           "themes"     => $themes,
                           "statususer" => $statususer,
                           "id_author"  => $id_author,
                           "page"       => $page), 
                     "Редактировать",
                     "field");

    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      // Если поля с паролями не пусты, проверяем равны ли они
      $pass      = $form->fields['pass']->value;
      $passagain = $form->fields['passagain']->value;
      if(!empty($pass) && 
         !empty($passagain))
      {
        if($pass != $passagain) $error[] = "Пароли не равны";
      }

      if(empty($error))
      {
        // Если поле с паролем не пусто
        if(!empty($pass))
          $password = "passw = ".get_password($pass).",";
        else
          $password = "";

        // Следует ли отправлять email
        $email = $form->fields['email']->value;
        if(!empty($email))
        {
          if($form->fields['sendmail']->value) $sendmail = 'yes';
          else $sendmail = 'no';
        } else $sendmail = 'no';
        // Проверяем не удаляется ли изображение
        $url_photo = "";
        if($form->fields['photo']->value)
        {
          @unlink('../../forum/$author[photo]');
          $url_photo = "photo = '',";
        }
        // Определяем статус пользователя
        $statususer = '';
        switch($form->fields['statususer']->value)
        {
          case '':
          case 'user':
            $statususer = '';
            break;
          case 'moderator':
            $statususer = 'moderator';
            break;
          case 'admin':
            $statususer = 'admin';
            break;
        }

        // Формируем SQL-запрос на добавление
        // новостного сообщения
        $query = "UPDATE $tbl_authors
                  SET name       = '{$form->fields[name]->value}',
                      $password
                      email      = '{$form->fields[email]->value}',
                      sendmail   = '$sendmail',
                      url        = '{$form->fields[url]->value}',
                      icq        = '{$form->fields[icq]->value}',
                      $url_photo
                      about      = '{$form->fields[about]->value}',
                      themes     = '{$form->fields[themes]->value}',
                      statususer = '$statususer'
                  WHERE id_author = {$form->fields[id_author]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка редактирования информации о пользователе");
        }

        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: authorslist.php?page={$form->fields[page]->value}");
        exit();
      }
    }

    // Начало страницы
    $title     = 'Редактирование пользователя';
    $pageinfo  = '<p class=help>Поля с паролями заполняются только в том случае, если имеется необходимость их смены</p>';
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