<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  // Бешкенадзе А.Г. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  Error_Reporting(E_ALL & ~E_NOTICE); 
  // Помещаем всё в буффер
  ob_start();
  // Подключаем SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Функции для работы со временем
  require_once("../utils/utils.time.php");
  // Функции для работы с сообщениями
  require_once("../utils/utils.posts.php");
  // Функции для работы с файлами
  require_once("../utils/utils.files.php");
  // Настройки форума
  require_once("../utils/utils.settings.php");

  try
  {
    // Загружаем настройки форума
    $settings = get_settings();

    // Извлекаем параметры из строки запроса
    $id_forum = intval($_GET['id_forum']);
    $id_theme = intval($_GET['id_theme']);
    $page     = intval($_GET['page']);
    // Извлекаем имя посетителя из cookie
    $current_author = $_COOKIE['current_author'];
    // Извлекаем вид форума из cookie
    $lineforum = $_COOKIE['lineforum'];
    $lineforumdown = $_COOKIE['lineforumdown'];

    // Защищаемся от SQL-инъекции
    if (!get_magic_quotes_gpc())
    {
      $current_author = mysql_escape_string($current_author);
      $lineforum = mysql_escape_string($lineforum);
      $lineforumdown = mysql_escape_string($lineforumdown);
    }

    // Выводим название темы
    $query = "SELECT * FROM $tbl_themes WHERE id_theme = $id_theme";
    $thm = mysql_query($query);
    if (!$thm)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "Ошибка при обращении к теме");
    }
    if(mysql_num_rows($thm))
    {
      $themes = mysql_fetch_array($thm);
      $theme = theme_work_up($themes['name']);
    }
    // Отображаем блок переключения между "линейным" и "структурным" форумами
    $show_switch = true; 
    // Показывать ссылку "Список тем"
    $showlisttopics = true;
    // Вывод линейки новых сообщений
    $showforumsline = true;
    $readforumline = true;
    $title = strip_tags($theme);
    // Выводим "шапку" страницы
    require_once("../utils/topforum.php");
  
    // Аутентификация
    if(isset($_COOKIE['current_author']))
    {
      $current_author = $_COOKIE['current_author'];
      $wrdp = $_COOKIE['wrdp'];
      if (!get_magic_quotes_gpc())
      {
        $current_author = mysql_escape_string($current_author);
        $wrdp = mysql_escape_string($wrdp);
      }
      // Если включены личные сообщения, проверяем,
      // не включены ли они
      if($settings['show_personally'] == 'yes')
      {
        // Если личные сообщения включены - проверяем
        // имеется ли для данного посетителя новые сообщения
        // предварительно проводим авторизацию
        $query = "SELECT * FROM $tbl_authors 
                  WHERE name = '$current_author' AND
                        passw = ".get_password($wrdp)." AND
                        statususer != 'wait'";
        $ath = mysql_query($query);
        if(!$ath)
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "Ошибка аутентификации");
        }
        // Если имеется запись, следовательно, посетитель зарегистрирован
        // и необходимо сверить пароли
        if(mysql_num_rows($ath))
        {
          $auth = mysql_fetch_array($ath);
          // Проверяем имеет ли право текущий автор читать данную тему
          $query = "SELECT * FROM $tbl_personally 
                    WHERE id_theme = $id_theme AND
                         (id_first = $auth[id_author] OR id_second = $auth[id_author])";
          $aut = mysql_query($query);
          if(!$aut)
          {
             throw new ExceptionMySQL(mysql_error(), 
                                      $query,
                                     "Ошибка аутентификации");
          }
          if(mysql_num_rows($aut)) define("AUTHOR", 1);
        }
      }
    }
    if(defined("AUTHOR"))
    {
    ?>
    <table class=readmenu border="0" width="100%" cellpadding="4" cellspacing="0" >
    <tr>
    <td class="headertable" width="70%" valign="middle">
      <div class=nametemaread>
      <em style="font-size: 11px">тема: </em><?php echo $theme; ?>
      </div>  
      <div class=nextback>
      </div>
    </td>
    </tr>
    </table>
    <table class=fonposts width="100%" border="0" cellspacing="1" cellpadding="0">
    <?php
    // Выбираем все сообщения текущей темы
    $query = "SELECT * FROM $tbl_posts 
              WHERE id_theme = $id_theme AND 
                    hide != 'hide' 
              ORDER BY time";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при выборке сообщений темы");
    }
    if(mysql_num_rows($pst))
    {
      unset($post_arr);
      while($posts = mysql_fetch_array($pst))
      {
        $post_arr[$posts['id_post']]['name']        = $posts['name'];
        $post_arr[$posts['id_post']]['url']         = $posts['url'];
        $post_arr[$posts['id_post']]['putfile']     = $posts['putfile'];
        $post_arr[$posts['id_post']]['author']      = $posts['author'];
        $post_arr[$posts['id_post']]['id_author']   = $posts['id_author'];
        $post_arr[$posts['id_post']]['hide']        = $posts['hide'];
        $post_arr[$posts['id_post']]['time']        = $posts['time'];
        $post_arr[$posts['id_post']]['parent_post'] = $posts['parent_post'];

        $post_par[$posts['parent_post']][]= $posts['id_post'];
      }
    }
    
    // Извлекаем время последнего посещения форума
    $forum_lasttime = get_last_time($current_author, $id_forum);
    // Выводим сообщения темы
    putpost_arr(0, 
                $id_theme, 
                $post_arr, 
                $post_par,
                2,
                $forum_lasttime,
                $current_author,
                $id_forum,
                $lineforum,
                $lineforumdown,
                $skin,
                $themes['hide']);
    // Рекурсивно выводим все подчинённые сообщения
/*    @putpost($posts['id_post'],
             $id_theme,
             2,
             $lasttime,
             $current_author,
             $id_forum,
             $lineforum,
             $lineforumdown,
             $skin,
             $themes['hide'],
             "posts",
             "themes");*/
    }
    else
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "У вас нет прав на просмотр этой темы");
    }
    echo "</table>";
    // Выводим завершение страницы
    include "../utils/bottomforum.php";
    // Помещаем страницу из буффера в переменную $buffer
    $buffer = ob_get_contents();  
    // Очищаем буффер
    ob_end_clean();
    // Отправляем страницу клиенту
    echo $buffer;
  }
  catch(ExceptionObject $exc) 
  {
    require_once("exception_object_debug.php"); 
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