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
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем классы формы
  require_once("../../config/class.config.dmn.php");
  // Устанавливаем соединение с FTP-сервером
  require_once("../../config/ftp_connect.php");
  // Подключаем генератор пароля
  require_once("../utils/utils.password.php");
  // Подключаем функции для работы с 
  // файлами .htaccess и .htpasswd
  require_once("../utils/uitls.htfiles.php");

  try
  {
    // Генерируем новый пароль
    $pass_example = generate_password(10);

    $name = new field_text("name",
                           "Имя",
                            true,
                            $_REQUEST['name']);
    $pass = new field_password("pass",
                           "Пароль",
                            true,
                            $_REQUEST['pass'],
                            255,
                            41,
                           "",
                           "Например, $pass_example");
    $passagain = new field_password("passagain",
                           "Повтор",
                            true,
                            $_REQUEST['passagain'],
                            255,
                            41,
                           "",
                           "Например, $pass_example");
    $dir = new field_hidden("dir",
                            false,
                            $_REQUEST['dir']);
  
    $form = new form(array("name"      => $name, 
                           "pass"      => $pass,
                           "passagain" => $passagain,
                           "dir"       => $dir), 
                     "Добавить пользователя",
                     "field");

    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if($form->fields['pass']->value != 
         $form->fields['passagain']->value)
      {
        $error[] = "Пароли не равны";
      }
      // Проверяем имя и пароль на допустимые символы
      $pattern = "|^[-\w\d_\"\.\[\]\(\)]+$|";
      if(!preg_match($pattern, $form->fields['name']->value))
      {
        $error[] = "Недопустимые символы в имени";
      }
      // Проверяем корректность пароля
      if(!preg_match($pattern, $form->fields['pass']->value))
      {
        $error[] = "Недопустимые символы в пароле";
      }

      if(empty($error))
      {
        // Текущая директория
        $dir = $form->fields['dir']->value;
        ///////////////////////////////////////////////////////
        // .htaccess
        ///////////////////////////////////////////////////////
        $path = str_replace("//","/",
                $ftp_absolute_path.$dir."/.htpasswd");
        if(!is_htaccess($ftp_handle, $dir))
        {
          // Файла .htaccess в директории нет, создаём его
          // в директории files и загружаем по FTP
          $content = "AuthType Basic\n".
                     "AuthName \"Fill name and password\"\n".
                     "AuthUserFile $path\n".
                     "require valid-user";
          put_htaccess($ftp_handle, $dir, $content);
        }
        else
        {
          // Файл .htpasswd в директории присутствует
          // Загружаем содержимое файла
          $content = get_htaccess($ftp_handle, $dir);
          // Проверяем, имеется ли в файле фраза require valid-user, 
          // если имеется - ничего не добавляем, если неимется, добавляем
          // требование защиты
          $flag = (strpos($content, "require") !== false) && 
                  (strpos($content, "valid-user") !== false);
          if(!$flag)
          {
            $content .= "\nAuthType Basic\n".
                        "AuthName \"Fill name and password\"\n".
                        "AuthUserFile $path\nrequire valid-user";
            put_htaccess($ftp_handle, $dir, $content);
          }
          else
          {
            // Удаляем старую защиту
            $pattern = "#AuthType.*valid-user#is";
            $content = preg_replace($pattern, "", $content);
            // Ставим новую
            $content .= "\nAuthType Basic\n".
                        "AuthName \"Fill name and password\"\n".
                        "AuthUserFile $path\n".
                        "require valid-user";
            put_htaccess($ftp_handle, $dir, $content);
          }
        }

        // Имя и пароль
        $name = $form->fields['name']->value;
        $pass = $form->fields['pass']->value;
        ///////////////////////////////////////////////////////
        // .htpasswd
        ///////////////////////////////////////////////////////
        if(!is_htpasswd($ftp_handle, $dir))
        {
          // Файла .htpasswd в директории нет, создаём его
          // в директории files и загружаем по FTP
          $content = "$name:".crypt($pass)."\n";
          put_htpasswd($ftp_handle, $dir, $content);
        }
        else
        {
          // Файл .htpasswd в директории имеется, нужно добавить в него запись
          // Загружаем содержимое файла
          $content = get_htpasswd($ftp_handle, $dir);
          // Проверяем нет ли такого пользователя в .htpasswd
          if(strpos($content, $name.":") !== false)
          {
            // Пользователь существует, меняем пароль
            $pattern = "#".preg_quote($name).":[^\n]+\n#is";
            $content = preg_replace($pattern, 
            "$name:".crypt($pass)."\n", $content);
          }
          else
          {
            // Пользователь новый - добавляем аккаунт
            $content .= "$name:".crypt($pass)."\n";
          }
          // Создаём новый файл .htpasswd
          put_htpasswd($ftp_handle, $dir, $content);
        }
        // Осуществляем перенаправление
        // на главную страницу администрирования
        $dir = $form->fields['dir']->value;
        $url = "index.php?dir=".urlencode(substr($dir, 0, strrpos($dir, "/")));
        header("Location: $url");
        exit();
      }
    }
    // Начало страницы
    $title = 'Установка пароля на директорию '.urldecode($_GET['dir']);;
    $pageinfo = '<p class=help>Следует ввести имя пользователя 
                 и его пароль для защиты содержимого директории.
                 Если пользователь с такими именем уже 
                 существует, его пароль будет заменён на новый. 
                 Если в диретории уже находится файл .htaccess 
                 и .htpasswd, они не будут повреждены или 
                 заменны, новые директивы будут добавлены в уже 
                 существующие файл.</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках, если они имеются
    if(!empty($error))
    {
      echo "<span style=\"color:red\">".
           implode("<br>", $error).
           "</span><br>";
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