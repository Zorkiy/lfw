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
  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");

  // Данные переменные определяют название страницы и подсказку.
  $title = 'Управление пользователями';
  $pageinfo = '<p class=help>Данная страница позволяет 
               управлять регистрационной информацией 
               зарегистрированных пользователей</p>';
  // Включаем заголовок страницы
  require_once("../utils/top.php");

  // Добавить аккаунт
  echo "<a href=usradd.php?page=$_GET[page]
           title='Добавить пользователя'>
           Добавить пользователя</a><br><br>";

  // Выводим форму управления фильтрами
  require_once("filter.php");

  $url = "&begin_date=$_GET[begin_date]".
         "&end_date=$_GET[end_date]";

  $where = "WHERE 1=1";
  if(!empty($_GET['begin_date']))
  {
    $where .= " AND dateregister >= '".
              date("Y-n-d H:i:s", $_GET['begin_date'])."'";
  }
  if(!empty($_GET['end_date']))
  {
    $where .= " AND dateregister <= '".
              date("Y-n-d H:i:s", $_GET['end_date'])."'";
  }

  try
  {
    // Число ссылок в постраничной навигации
    $page_link = 3;
    // Число позиций на странице
    $pnumber = 10;
    // Объявляем объект постраничной навигации
    $obj = new pager_mysql($tbl_users,
                           $where,
                           "ORDER BY dateregister DESC",
                           $pnumber,
                           $page_link,
                           $url);
  
    // Получаем содержимое текущей страницы
    $users = $obj->get_page();

    // Если имеется хотя бы одна запись - выводим
    if(!empty($users))
    {
      ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td align=center width=120>Дата регистрации</td>
          <td align=center>Ник</td>
          <td align=center>E-mail</td>
          <td align=center width=50>Действия</td>
        </tr>
      <?php
      for($i = 0; $i < count($users); $i++)
      {
        $url = "?id_position={$users[$i][id_position]}&page=$page{$url}";

        // Заблокирован пользователь или нет
        $colorrow = "";
        if($users[$i]['block'] == 'block')
        {
          $blk = "<a href=usrunblock.php$url 
                          title='Разблокировать пользователя'>
                       Разблокировать</a>";
          $colorrow = "class='hiddenrow'";
        }
        else
        {
          $blk = "<a href=usrblock.php$url 
                          title='Заблокировать пользователя'>
                       Блокировать</a>";
        }

        // Преобразуем дату регистрации
        list($date, $time)        = explode(" ", $users[$i]['dateregister']);
        list($year, $month, $day) = explode("-", $date);
        $time = substr($time, 0, 5);

        // Выводим позицию
        echo "<tr $colorrow>
                <td align=center>$day.$month.$year $time</td>
                <td align=center>
                  <a href=# 
                     onclick=\"show_detail('usrdetail.php?id_position={$users[$i][id_position]}',400,350); return false\">".
                     htmlspecialchars($users[$i]['name'])."</a></p></td>
                <td align=center>
                  <a href=mailto:".htmlspecialchars($users[$i]['email']).">".
                     htmlspecialchars($users[$i]['email'])."</a>$address_print</td>
                <td align=center>
                  $blk<br>
                  <a href=usredit.php$url>Редактировать</a><br>
                  <a href=# onClick=\"delete_user('usrdel.php$url',".
                  "'Вы действительно хотите удалить пользователя?');\">Удалить</a>
                </td>
              </tr>";
      }
      echo "</table><br>";
    }
    // Выводим ссылки на другие страницы
    echo $obj;
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>
<script language="JavaScript">
<!--
  function show_detail(url,width,height)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a=(screen.height-vidWindowHeight)/5;
    b=(screen.width-vidWindowWidth)/2;
    features = "top=" + a + ",left=" + b + 
               ",width=" + vidWindowWidth + 
               ",height=" + vidWindowHeight + 
               ",toolbar=no,menubar=no,location=no," + 
               "directories=no,scrollbars=no,resizable=no";
    window.open(url,'',features,true);
  }
//-->
</script>