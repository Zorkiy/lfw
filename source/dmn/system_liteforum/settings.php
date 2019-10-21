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
  // Настройки форума
  require_once("../../utils/utils.settings.php");

  try
  {
    if(empty($_POST))
    {
      // Загружаем настройки форума
      // из таблицы settings
      $settings = get_settings();
      $_REQUEST = $settings;
      $_REQUEST['nameforum'] = $settings['name_forum'];
      $_REQUEST['numberthemes'] = $settings['number_themes'];
      $_REQUEST['sizefile'] = $settings['size_file'];
      $_REQUEST['sizephoto'] = $settings['size_photo'];
      if($_REQUEST['send_mail'] == 'yes') $_REQUEST['sendmail'] = true;
      else $_REQUEST['sendmail'] = false;
      if($_REQUEST['show_struct_switch'] == 'yes') $_REQUEST['showstructswitch'] = true;
      else $_REQUEST['showstructswitch'] = false;
      if($_REQUEST['show_forum_switch'] == 'yes') $_REQUEST['showforumswitch'] = true;
      else $_REQUEST['showforumswitch'] = false;
      if($_REQUEST['show_personally'] == 'yes') $_REQUEST['showpersonally'] = true;
      else $_REQUEST['showpersonally'] = false;
      if($_REQUEST['user_email_required'] == 'yes') $_REQUEST['useremailrequired'] = true;
      else $_REQUEST['useremailrequired'] = false;
      if($_REQUEST['email_distribution'] == 'yes') $_REQUEST['emaildistribution'] = true;
      else $_REQUEST['emaildistribution'] = false;
      if($_REQUEST['registration_required'] == 'yes') $_REQUEST['registrationrequired'] = true;
      else $_REQUEST['registrationrequired'] = false;
    }

    $nameforum = new field_text("nameforum",
                           "Название форума",
                            true,
                            $_REQUEST['nameforum']);
    $numberthemes = new field_text("numberthemes",
                           "Количество позиций",
                            true,
                            $_REQUEST['numberthemes']);
    $sizefile = new field_text_int("sizefile",
                           "Максимальный размер прикрепляемого файла, байт",
                            true,
                            $_REQUEST['sizefile']);
    $sizephoto = new field_text_int("sizephoto",
                           "Максимальный размер фотографии, байт",
                            true,
                            $_REQUEST['sizephoto']);
    $sendmail = new field_checkbox("sendmail",
                                   "E-mail рассылка при добавлении новой темы (администратору)",
                                   $_REQUEST['sendmail']);
    $email = new field_text_email("email",
                           "E-mail администратора",
                            false,
                            $_REQUEST['email']);
    $showstructswitch = new field_checkbox("showstructswitch",
                                   "Переключение между \"линейным\" и \"структурным\" видами форума",
                                   $_REQUEST['showstructswitch']);
    $showforumswitch = new field_checkbox("showforumswitch",
                                   "Переключение между разделами форума",
                                   $_REQUEST['showforumswitch']);
    $hello = new field_text("hello",
                           "Приветствие",
                            true,
                            $_REQUEST['hello']);
    $cooktime = new field_text_int("cooktime",
                           "Срок действия cookie, суток",
                            true,
                            $_REQUEST['cooktime']);
    // Выясняем сколько скинов имеется в системе
    // Для этого открываем папку skins и читаем
    // её содержимое
    $skin_dir = opendir("../../skins");
    while(($dir = readdir($skin_dir)))
    {
      // Если очередной объект в папке skins
      // является директорией, заносим его в
      // массив $skin_list()
      if(@is_dir("../../skins/".$dir) && $dir != "." && $dir != "..") $skin_list[$dir] = $dir;
    }
    // Закрываем директорию
    closedir($skin_dir);

    $skin = new field_select("skin",
                             "Выбрать скин",
                              $skin_list,
                              $_REQUEST['skin']);
    $showpersonally = new field_checkbox("showpersonally",
                             "Личная переписка",
                              $_REQUEST['showpersonally']);
    $useremailrequired = new field_checkbox("useremailrequired",
                             "При регистрации e-mail обязателен",
                              $_REQUEST['useremailrequired']);
    $emaildistribution = new field_checkbox("emaildistribution",
                             "E-mail рассылка при добавлении новой темы (всем)",
                              $_REQUEST['emaildistribution']);
    $registrationrequired = new field_checkbox("registrationrequired",
                             "Регистрация обязательна",
                              $_REQUEST['registrationrequired']);
  
    $form = new form(array("nameforum"            => $nameforum, 
                           "numberthemes"         => $numberthemes,
                           "sizefile"             => $sizefile,
                           "sizephoto"            => $sizephoto,
                           "hello"                => $hello,
                           "cooktime"             => $cooktime,
                           "registrationrequired" => $registrationrequired,
                           "showstructswitch"     => $showstructswitch,
                           "showforumswitch"      => $showforumswitch,
                           "showpersonally"       => $showpersonally,
                           "useremailrequired"    => $useremailrequired,
                           "emaildistribution"    => $emaildistribution,
                           "sendmail"             => $sendmail,
                           "email"                => $email,
                           "skin"                 => $skin), 
                     "Сохранить",
                     "field");

    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Определяем нужно ли отправлять уведомление
        // по e-mail
        if($form->fields['sendmail']->value) $send_mail = "yes";
        else $send_mail = "no";
        // Определяем следует ли выводить окно
        // переключения между линейным и структурным форумами
        if($form->fields['showstructswitch']->value) $show_struct_switch = "yes";
        else $show_struct_switch = "no";
        // Определяем следует ли выводить строку
        // с новыми сообщеними в разделах форума
        // и выпадающий список переключения между разделами
        if($form->fields['showforumswitch']->value) $show_forum_switch = "yes";
        else $show_forum_switch = "no";
        // Определяем следует ли включать личные
        // сообщения
        if($form->fields['showpersonally']->value) $show_personally = "yes";
        else $show_personally = "no";
        // При регистрации нового пользователя обязательно
        // требовать e-mail
        if($form->fields['useremailrequired']->value) $user_email_required = "yes";
        else $user_email_required = "no";
        // При создании новой темы отсылать форумчанам уведомление
        // об этом на e-mail
        if($form->fields['emaildistribution']->value) $email_distribution = "yes";
        else $email_distribution = "no";
        // Проверяем обязательна ли регистрация на форуме
        // незарегистрированные посетители не смогут добавлять
        // темы и сообщения
        if($form->fields['registrationrequired']->value) $registration_required = "yes";
        else $registration_required = "no";
        // Проверяем требуется ли подтверждение регистрации
        // зарегистрированным участником
        //if($confirm_registration == "on") $flgcnf = "yes";
        //else $flgcnf = "no";
        $confirm_registration = "no";
        // Добавляем обновлённое сообщение
        $query = "UPDATE $tbl_settings 
                  SET name_forum            = '{$form->fields[nameforum]->value}',
                      number_themes         = '{$form->fields[numberthemes]->value}',
                      size_file             = '{$form->fields[sizefile]->value}',
                      size_photo            = '{$form->fields[sizephoto]->value}',
                      send_mail             = '$send_mail',
                      email                 = '{$form->fields[email]->value}',
                      show_struct_switch    = '$show_struct_switch',
                      show_forum_switch     = '$show_forum_switch',
                      show_personally       = '$show_personally',
                      user_email_required   = '$user_email_required',
                      email_distribution    = '$email_distribution',
                      registration_required = '$registration_required',
                      confirm_registration  = '$confirm_registration',
                      hello                 = '{$form->fields[hello]->value}',
                      cooktime              = '{$form->fields[cooktime]->value}',
                      skin                  = '{$form->fields[skin]->value}'";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "Ошибка редактирования настроек форума");
        }

        // Осуществляем перенаправление
        // на главную страницу администрирования
        header("Location: settings.php");
        exit();
      }
    }

    // Начало страницы
    $title = 'Настройки форума';  
    $pageinfo = '<p class=help>На данной странице можно настроить
                 выбранный форум</p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");
    
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".implode("<br>", $error)."</span><br>";
    }
    // Выводим HTML-форму 
    $form->print_form();

    // Выводим завершение страницы
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