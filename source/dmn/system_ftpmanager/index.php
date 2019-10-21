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
  $title = 'Панель доступа к FTP-серверу';
  $pageinfo = '<p class=help>Панель доступа к FTP-серверу 
  позволяет загружать на сервер файлы и создавать каталоги; 
  кроме того, допускается переименовывать, удалять, изменять 
  права доступа к уже существующим файлам и директориям.</p>';

  // Устанавливаем соединение с базой данных
  require_once("../../config/config.php");
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Включаем заголовок страницы
  require_once("../utils/top.php");
  // Устанавливаем соединение с FTP-сервером
  require_once("../../config/ftp_connect.php");

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
          <td align=center>права доступа</td>
          <td align=center>размер, байты</td>
          <td align=center>время создания</td>
          <td align=center colspan=2>действия</td>
        </tr>
    <?php
    $i = 0;
    $dir = array();
    $fil = array();
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

      $eng = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); 
      $rus = array("Янв", "Фев", "Мар", "Апр", "Май", "Июн", 
                   "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек");
      $month = str_replace($eng, $rus, $month);
      $url = urlencode(str_replace("//","/",$directory."/".$file));
      if($acc[0] == 'd')
      {
        // Директория
        $dir[$i]['acc']    = $acc;
        $dir[$i]['bloks']  = $bloks;
        $dir[$i]['group']  = $group;
        $dir[$i]['user']   = $user;
        $dir[$i]['size']   = $size;
        $dir[$i]['month']  = $month;
        $dir[$i]['day']    = $day;
        $dir[$i]['year']   = $year;
        $dir[$i]['file']   = "<b><a href=index.php?dir=$url 
                              title='Открыть директорию'>$file</a></b>";
        $dir[$i]['delete'] = "<p><a href=# 
                              onClick=\"delete_position('rmdir.php?dir=$url',".
                             "'Вы действительно хотите удалить эту директорию?');\"
                              >Удалить</a></p>";
        $dir[$i]['edit']   = "<p><a href=chdirform.php?dir=$url&acc=$acc
                              >Редактировать</a></p>";
        $dir[$i]['size']   = "&lt;DIR&gt;";
      }
      else
      { 
        // Файл
        $fil[$i]['acc']    = $acc;
        $fil[$i]['bloks']  = $bloks;
        $fil[$i]['group']  = $group;
        $fil[$i]['user']   = $user;
        $fil[$i]['size']   = $size;
        $fil[$i]['month']  = $month;
        $fil[$i]['day']    = $day;
        $fil[$i]['year']   = $year;
        $fil[$i]['file']   = "<a href=download.php?dir=$url 
                              title='Загрузить файл'>$file</a>";
        $fil[$i]['delete'] = "<p><a href=# 
                              onClick=\"return delete_position('rmfile.php?dir=$url',".
                              "'Вы действительно хотите удалить этот файл?');\"
                              >Удалить</a></p>";
        $fil[$i]['edit']   = "<p><a href=chdirform.php?dir=$url&acc=$acc&file=file
                              >Редактировать</a></p>";
      }
      $i++;
    }
    // Выводим директории
    foreach($dir as $name)
    {
      echo "<tr>
              <td align=right>$name[file]</td>
              <td align=center>$name[acc]</td>
              <td align=center>$name[size]</td>
              <td align=center>$name[day]&nbsp;&nbsp;
                               $name[month]&nbsp;&nbsp;
                               $name[year]</td>
              <td align=center>$name[delete]</td>
              <td align=center>$name[edit]</td>
            </tr>";
    }
    // Выводим файлы
    foreach($fil as $name)
    {
      echo "<tr>
              <td align=right>$name[file]</td>
              <td align=center>$name[acc]</td>
              <td align=center>$name[size]</td>
              <td align=center>$name[day]&nbsp;&nbsp;
                               $name[month]&nbsp;&nbsp;
                               $name[year]</td>
              <td align=center>$name[delete]</td>
              <td align=center>$name[edit]</td>
            </tr>";
    }
    echo "</table><br><br>";
  }
?>
  <table><tr><td>
<?php
  ftp_close($ftp_handle);

  // Загрузка файла на сервер
  $action = "upload.php";
  $button = "Загрузить";
  // Права доступа по умолчанию для
  // пользователя
  $ur = "checked";
  $uw = "checked";
  $ux = "";
  // Права доступа по умолчанию для
  // группы
  $gr = "checked";
  $gw = "";
  $gx = "";
  // Права доступа по умолчанию для
  // остальных пользователей (не входящих в группу)
  $or = "checked";
  $ow = "";
  $ox = "";

  $ur_hint = 'Чтение файлов директории для владельца';
  $uw_hint = 'Создание и редактирование файлов в директории для владельца';
  $ux_hint = 'Чтение содержимого директории для владельца';
  $gr_hint = 'Чтение файлов директории для группы';
  $gw_hint = 'Создание и редактирование файлов в директории для группы';
  $gx_hint = 'Чтение содержимого директории для группы';
  $or_hint = 'Чтение файлов директории для пользователей не входящих в группу владельца';
  $ow_hint = 'Создание и редактирование файлов в директории для пользователей не входящих в группу владельца';
  $ox_hint = 'Чтение содержимого директории для пользователей не входящих в группу владельца';
?>
<form enctype='multipart/form-data' action=<?php echo htmlspecialchars($action); ?> method=post>
<table>
<tr>
  <td class=field>Файл:</td>
  <td><input type=file name=name value=''></td>
</tr>
<tr>
  <td class=field>Права доступа:</td>
  <td>
    <input type=checkbox 
           title='<?php echo $ur_hint; ?>' 
           name=ur <?php echo $ur; ?>>
    <input type=checkbox 
           title='<?php echo $uw_hint; ?>' 
           name=uw <?php echo $uw; ?>>
    <input type=checkbox 
           title='<?php echo $ux_hint; ?>' 
           name=ux <?php echo $ux; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $gr_hint; ?>' 
           name=gr <?php echo $gr; ?>>
    <input type=checkbox 
           title='<?php echo $gw_hint; ?>' 
           name=gw <?php echo $gw; ?>>
    <input type=checkbox 
           title='<?php echo $gx_hint; ?>' 
           name=gx <?php echo $gx; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $or_hint; ?>' 
           name=or <?php echo $or; ?>>
    <input type=checkbox 
           title='<?php echo $ow_hint; ?>' 
           name=ow <?php echo $ow; ?>>
    <input type=checkbox 
           title='<?php echo $ox_hint; ?>' 
           name=ox <?php echo $ox; ?>>
  </td>
</tr>
<tr><td>&nbsp;</td>
<td><input class=button 
           type=submit 
           value=<?php echo htmlspecialchars($button);?>></td></tr>
<input type=hidden 
       name=dir 
       value=<?php echo htmlspecialchars($directory);?>>
</table>
</form>

</td><td>

<?php
  // Если не переданы параметры - настраиваем
  // форму на добавление директории
  $action = "mkdir.php";
  $button = "Создать";
  // Права доступа по умолчанию для
  // пользователя
  $ur = "checked";
  $uw = "checked";
  $ux = "checked";
  // Права доступа по умолчанию для
  // группы
  $gr = "checked";
  $gw = "";
  $gx = "checked";
  // Права доступа по умолчанию для
  // остальных пользователей (не входящих в группу)
  $or = "checked";
  $ow = "";
  $ox = "checked";

  $ur_hint = 'Чтение файла для владельца';
  $uw_hint = 'Редактирование файла для владельца';
  $ux_hint = 'Выполнение файла для владельца';
  $gr_hint = 'Чтение файла для группы';
  $gw_hint = 'Редактирование файла для группы';
  $gx_hint = 'Выполнение файла для группы';
  $or_hint = 'Чтение файла для пользователей не входящих в группу владельца';
  $ow_hint = 'Редактирование файла для пользователей не входящих в группу владельца';
  $ox_hint = 'Выполнение файла для пользователей не входящих в группу владельца';
?>
<form action=<?php echo htmlspecialchars($action); ?> method=post>
<table>
<tr>
  <td class=field>Название директории:</td>
  <td><input size=31 type=text name=name value=''></td>
</tr>
<tr>
  <td class=field>Права доступа:</td>
  <td>
    <input type=checkbox 
           title='<?php echo $ur_hint; ?>' 
           name=ur <?php echo $ur; ?>>
    <input type=checkbox 
           title='<?php echo $uw_hint; ?>' 
           name=uw <?php echo $uw; ?>>
    <input type=checkbox 
           title='<?php echo $ux_hint; ?>' 
           name=ux <?php echo $ux; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $gr_hint; ?>' 
           name=gr <?php echo $gr; ?>>
    <input type=checkbox 
           title='<?php echo $gw_hint; ?>' 
           name=gw <?php echo $gw; ?>>
    <input type=checkbox 
           title='<?php echo $gx_hint; ?>' 
           name=gx <?php echo $gx; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $or_hint; ?>' 
           name=or <?php echo $or; ?>>
    <input type=checkbox 
           title='<?php echo $ow_hint; ?>' 
           name=ow <?php echo $ow; ?>>
    <input type=checkbox 
           title='<?php echo $ox_hint; ?>' 
           name=ox <?php echo $ox; ?>>
  </td>
</tr>
<tr><td>&nbsp;</td>
<td><input class=button 
           type=submit 
           value=<?php echo htmlspecialchars($button);?>></td></tr>
<input type=hidden 
       name=dir 
       value=<?php echo htmlspecialchars($directory);?>>
</table>
</form>

</td></tr></table>

<?php
  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>