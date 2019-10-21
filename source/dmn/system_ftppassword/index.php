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

  // Данные переменные определяют название страницы и подсказку.
  $title = 'Установка пароля на директорию';
  $pageinfo = '<p class=help>Данная панель позволяет устанавить 
               пароль на директорию посредством конфигурационных 
               файлов .htaccess и .htpasswd. Файлы .htaccess и 
               .htpasswd, защищают содержимое директорий от 
               обращений через браузер.<br>
               PHP-скрипты могут обращаться используя локальный 
               или абсолютный путь к файлам без каких-либо
               ограничений, пароль будет запрашиваться только 
               если используется полный сетевой путь, например,
               http://www.site.ru/защищаемая_директория/</p>';

  // Устанавливаем соединение с базой данных
  require_once("../../config/config.php");
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Включаем заголовок страницы
  require_once("../utils/top.php");
  // Устанавливаем соединение с FTP-сервером
  require_once("../../config/ftp_connect.php");
  // Подключаем функции для работы с 
  // файлами .htaccess и .htpasswd
  require_once("../utils/uitls.htfiles.php");

  if(empty($_GET['dir'])) $directory = "/";
  else $directory = $_GET['dir'];

  $file_list = ftp_rawlist($ftp_handle, $directory);
  if(!empty($file_list))
  {
    // Выводим ссылки на предыдущие каталоги
    $_GET['dir'] = rtrim($_GET['dir'],"/");
    $prev = explode("/",$_GET['dir']);
    if(!empty($prev))
    {
      $prev_path = "";
      $link = array();
      for($i = 0; $i < count($prev); $i++)
      {
        $prev_pach .= "/".$prev[$i];
        $prev_pach = str_replace("//","/",$prev_pach);
        if(!empty($prev[$i]))
        {
          $link[] = "<a href=index.php?dir=".
          urlencode($prev_pach).">".$prev[$i]."</a>";
        }
        else
        {
          $link[] = "<a href=index.php?dir=".
          urlencode($prev_pach).">Корневая директория</a>";
        }
      }

      echo "<p class=help>".implode("-&gt;",$link)."</p>";
      echo "<br>";
    }
    ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>&nbsp;</td>
          <td align=center>пользователи</td>
          <td align=center colspan=3>действия</td>
        </tr>
    <?php
    $i = 0;
    foreach($file_list as $file_single)
    {
      // Разбиваем строку по пробельным символам
      list($acc,
           $bloks,
           $group,
           $user,
           $size, 
           $month, 
           $day, 
           $year, 
           $file) = preg_split("/[\s]+/", $file_single);

      if($file == ".." || $file == ".") continue;

      $url = urlencode(str_replace("//","/",$directory."/".$file));
      $dir = str_replace("//","/",$directory."/".$file);
      if($acc[0] == 'd')
      {
        // Проверяем имеется ли в директории 
        // файлы .htaccess и .htpasswd
        $flag = false;
        if(is_htaccess($ftp_handle, $dir))
        {
          $content = get_htaccess($ftp_handle, $dir);
          $flag = (strpos($content, "require") !== false) && 
                  (strpos($content, "valid-user") !== false);
        }
        if($flag)
        {
          $delete = "<p><a href=# 
            onClick=\"delete_position('pasdel.php?dir=$url',".
            "'Вы действительно хотите снять защиту с директории?');\"".
            ">Снять защиту</a></p>";
          $addcom = "<p><a href=pasglobset.php?dir=$url".
            "title=\"Защитить директорию глобальными паролями сайта\"".
            ">Защитить глобальными паролями</a></p>";
          if(is_htpasswd($ftp_handle, $dir))
          {
            $add = "<p><a href=pasadd.php?dir=$url ".
                   "title='Добавить ещё один аккаунт к существующим ".
                   "(для директории)'>Добавить пользователя</a></p>";
            // Получаем содержимое .htpasswd файла и 
            // извлекаем имена пользователей
            $edit = "";
            $content = get_htpasswd($ftp_handle, $dir);
            $pattern = "#([^\n:]+):#";
            preg_match_all($pattern, $content, $out);
            if(!empty($out[1]))
            {
              foreach($out[1] as $user)
              {
                $edit_arr[] = "$user (<a title='Изменить локальный пароль".
                " пользователя' href=pasadd.php?dir=$url&name=".urlencode($user).
                ">сменить пароль</a>,".
                "<a title='Удалить локального пользователя' ".
                "href=# onClick=\"delete_position('pasusrdel.php?dir=$url&name=".
                urlencode($user)."','Вы действительно хотите лишить".
                " пользователя доступа к данной директории?')\">удалить</a>)";
              }
            }
            if(!empty($edit_arr))
            {
              $users = implode("<br>", $edit_arr);
            }
            else $users .= "&nbsp";
          }
          else
          {
            $users .= "&nbsp";
            $add    = "<p><a href=pasadd.php?dir=$url 
              title='Защитить директорию локальным паролем'
              >Защитить локальным паролем</a></p>";
            $addcom = "<p>Директория защищена глобальными паролями</p>";
          }
        }
        else
        {
          $delete = "<p>Директория не защищена</p>";
          $add    = "<p><a href=pasadd.php?dir=$url 
             title='Защитить директорию локальным паролем'
             >Защитить локальным паролем</a></p>";
          $users = "&nbsp;";
          $addcom = "<p><a href=pasglobset.php?dir=$url 
             title=\"Защитить директорию глобальным паролем сайта\"
             >Защитить глобальными паролями</a></p>";
        }
        // Директория
        $file   = "<b><a href=index.php?dir=$url 
                   title='Открыть директорию'>$file</a></b>";
        echo "<tr>
                <td align=right>$file</td>
                <td align=center>$users</td>
                <td align=center>$add</td>
                <td align=center>$delete</td>
                <td align=center>$addcom</td>
              </tr>";
      }
    }
    echo "</table><br><br>";
  }

  echo '<p class=help>Часто не удобно иметь локальные пароли 
        для каждой из защищаемых директорий сайта, особенно 
        если таких директорий много. В этом случае удобно 
        иметь глобальные пароли для всех директорий сайта. 
        Такие пароли хранятся в файле .htpasswd на один 
        уровень выше корневой директории виртуального хоста. 
        Защитить директорию глобальными паролями можно при 
        помощи управляющей ссылки "Защитить глобальными паролями".<br><br>';
  echo '<a href=pasglobadd.php?dir='.$url.' 
           title="Добавить глобальный пароль для всего сайта"
        >Добавить глобальный пароль</a><br><br>';

  // Извлекаем соедрижимое файла .htpasswd корневой директории
  $content = get_htpasswd($ftp_handle, "/");
  $pattern = "#([^\n:]+):#";
  preg_match_all($pattern, $content, $out);
  if(!empty($out[1]))
  {
    ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td align=center>пользователь</td>
          <td align=center colspan=2>действия</td>
        </tr>
    <?php
    foreach($out[1] as $user_name)
    {
      echo "<tr>
          <td align=center>$user_name</td>
          <td align=center>
            <a title='Изменить пароль пользователя' 
                href=pasglobadd.php?dir=$url&name=".urlencode($user_name).
            ">сменить пароль</a>
          </td>
          <td align=center>
            <a title='Удалить пользователя' 
               href=# 
               onClick=\"delete_position(".
              "'pasglobdel.php?dir=$url&name=".urlencode($user_name)."',".
              "'Вы действительно хотите удалить пользователя".
              " из списка глобальных паролей на сайт?')\">удалить</a></td>
        </tr>";
    }
    echo "</table>";
  }

  // Закрываем соединение с FTP-сервером
  ftp_close($ftp_handle);

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>