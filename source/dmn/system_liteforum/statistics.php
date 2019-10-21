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

  try
  {
    $title = 'Статистика форума';  
    $pageinfo = '<p class=help>Здесь собрана статистическая информация
                 по форуму</p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");

    // Количество зарегистрированных полетителей
    $query = "SELECT COUNT(*) FROM $tbl_authors";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении к 
                               таблице посетителей");
    }
    $number_author = mysql_result($ath, 0);
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center width=50>&nbsp;</td>
      <td align=center>Количество зарегистрированных авторов</td>
    </tr>
    <tr>
      <td align=center class="header">&nbsp;</td>
      <td align=center><?php echo $number_author; ?></td>
    </tr>
    </table><br><br>
    <?php
      // Количество доступных, скрытых и закрытых тем и постов
      // в живом форуме
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE hide = 'hide'";
      $theme_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE hide = 'show'";
      $theme_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE hide = 'lock'";
      $theme_lock = query_result($query);

      // Количество доступных, скрытых и закрытых тем и постов
      // в архивном форуме
      $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                WHERE hide = 'hide'";
      $theme_archive_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                WHERE hide = 'show'";
      $theme_archive_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                WHERE hide = 'lock'";
      $theme_archive_lock = query_result($query);
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center widht=300>&nbsp;</td>
      <td align=center>Доступные</td>
      <td align=center>Скрытые</td>
      <td align=center>Закрытые</td>
    </tr>
    <tr>
      <td align=center class="header">Темы живого форума</td>
      <td align=center><?php echo $theme_show; ?></td>
      <td align=center><?php echo $theme_hide; ?></td>
      <td align=center><?php echo $theme_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">Темы архивного форума</td>
      <td align=center><?php echo $theme_archive_show; ?></td>
      <td align=center><?php echo $theme_archive_hide; ?></td>
      <td align=center><?php echo $theme_archive_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">Всего</td>
      <td align=center><?php echo ($theme_show + $theme_archive_show); ?></td>
      <td align=center><?php echo ($theme_hide + $theme_archive_hide); ?></td>
      <td align=center><?php echo ($theme_lock + $theme_archive_lock); ?></td>
    </tr>
    </table><br><br>
    <?php
      $query = "SELECT COUNT(*) FROM $tbl_posts
                WHERE hide = 'hide'";
      $post_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_posts
                WHERE hide = 'show'";
      $post_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_posts
                WHERE hide = 'lock'";
      $post_lock = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                WHERE hide = 'hide'";
      $post_archive_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                WHERE hide = 'show'";
      $post_archive_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                WHERE hide = 'lock'";
      $post_archive_lock = query_result($query);
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center widht=300>&nbsp;</td>
      <td align=center>Доступные</td>
      <td align=center>Скрытые</td>
      <td align=center>Закрытые</td>
    </tr>
    <tr>
      <td align=center class="header">Сообщения живого форума</td>
      <td align=center><?php echo $post_show; ?></td>
      <td align=center><?php echo $post_hide; ?></td>
      <td align=center><?php echo $post_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">Сообщения архивного форума</td>
      <td align=center><?php echo $post_archive_show; ?></td>
      <td align=center><?php echo $post_archive_hide; ?></td>
      <td align=center><?php echo $post_archive_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">Всего</td>
      <td align=center><?php echo ($post_show + $post_archive_show); ?></td>
      <td align=center><?php echo ($post_hide + $post_archive_hide); ?></td>
      <td align=center><?php echo ($post_lock + $post_archive_lock); ?></td>
    </tr>
    </table><br><br>

    <?php
      // Извлекаем помесячную статистику из актуальных таблиц
      $query = "SELECT UNIX_TIMESTAMP(MIN(`time`)) AS min_putdate,
                       UNIX_TIMESTAMP(MAX(`time`)) AS max_putdate
                FROM $tbl_posts";
      $dat = mysql_query($query);
      if(!$dat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при извлечении
                                 начальной и конечной дат");
      }
      if(mysql_num_rows($dat))
      {
        ?>
          <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr class="header">
            <td align=center width=100>Живой форум</td>
            <td align=center>Темы</td>
            <td align=center>Посты</td>
          </tr>
        <?php
        $date = mysql_fetch_array($dat);
        $min = $date['min_putdate'];
        $max = $date['max_putdate'];
        for($i = date("Ym",$min); $i <= date("Ym",$max); $i++)
        {
          if(substr($i,4,2) > 12) $i = (substr($i,0,4) + 1)."01";
          $month[] = substr($i,0,4)."-".substr($i,4,2);
        }

        for($i = count($month) - 1; $i >=0; $i--)
        {
          list($year, $mon) = explode("-", $month[$i]);

          $query = "SELECT COUNT(*) FROM $tbl_posts
                    WHERE YEAR(`time`) = $year AND 
                    MONTH(time) = $mon";
          $posts = query_result($query);
          $query = "SELECT COUNT(*) FROM $tbl_themes
                    WHERE YEAR(`time`) = $year AND 
                    MONTH(time) = $mon";
          $thmemes = query_result($query);
          ?>
          <tr>
            <td align=center class="header"><?php echo $month[$i]; ?></td>
            <td align=center><?php echo $thmemes; ?></td>
            <td align=center><?php echo $posts; ?></td>
          </tr>
          <?php
        }
        echo "</table><br><br>";
      }

      // Извлекаем помесячную статистику из архивных таблиц
      $query = "SELECT UNIX_TIMESTAMP(MIN(`time`)) AS min_putdate,
                       UNIX_TIMESTAMP(MAX(`time`)) AS max_putdate
                FROM $tbl_archive_posts";
      $dat = mysql_query($query);
      if(!$dat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при извлечении
                                 начальной и конечной дат");
      }
      if(mysql_num_rows($dat))
      {
        ?>
        <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="header">
          <td align=center width=100>Архивный форум</td>
          <td align=center>Темы</td>
          <td align=center>Посты</td>
        </tr>
        <?php
        $date = mysql_fetch_array($dat);
        $min = $date['min_putdate'];
        $max = $date['max_putdate'];
        unset($month);
        for($i = date("Ym",$min); $i <= date("Ym",$max); $i++)
        {
          if(substr($i,4,2) > 12) $i = (substr($i,0,4) + 1)."01";
          $month[] = substr($i,0,4)."-".substr($i,4,2);
        }

        for($i = count($month) - 1; $i >=0; $i--)
        {
          list($year, $mon) = explode("-", $month[$i]);

          $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                    WHERE YEAR(time) = $year AND 
                    MONTH(time) = $mon";
          $posts = query_result($query);
          $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                    WHERE YEAR(time) = $year AND 
                    MONTH(time) = $mon";
          $thmemes = query_result($query);
          ?>
          <tr>
            <td align=center class="header"><?php echo $month[$i]; ?></td>
            <td align=center><?php echo $thmemes; ?></td>
            <td align=center><?php echo $posts; ?></td>
          </tr>
          <?php
        }
        echo "</table>";
      }

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