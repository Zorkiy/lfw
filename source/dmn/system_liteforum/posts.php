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
  // Настройки форума
  require_once("../../utils/utils.settings.php");
  // Выполнение SQL-запроса
  require_once("utils.query_result.php");
  // Обработка тем 
  require_once("../../utils/utils.posts.php");

  try
  {
    // Предотвращаем SQL-инъекцию
    $id_theme = intval($_GET['id_theme']);
    $id_forum = intval($_GET['id_forum']);

    // Выводим название темы
    $query = "SELECT * FROM $tbl_themes 
              WHERE id_theme = $id_theme
              LIMIT 1";
    $thm = mysql_query($query);
    if(!$thm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обращения к теме");
    }
    if(mysql_num_rows($thm)) $themes = mysql_fetch_array($thm);
    // Предварительно обрабатываем скобки и ентеры в названии темы
    $theme = theme_work_up($themes['name']);

    $title = 'Модерирование темы: '.$theme;
    $pageinfo = '';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
?>
<style type="text/css">
@charset "windows-1251";

body, table{font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
a{color: #19308C}
a:hover{color: #010103}
.text{font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #2D2D2D; line-height: 20px; text-align: justify}
.texthelp{color: #5B5B5B; margin: 0px}
.authorreg{font-size: 12px; padding-left: 10px; padding-right: 10px; color: #446343; font-weight: bold}
a.authorreg{font-size: 12px; padding-left: 0px; padding-right: 0px; color: #446343; font-weight: bold}
a.authorreg:hover{color: #182D0B;}
.author{font-size: 12px; padding-left: 10px; padding-right: 10px; color: #446343; font-style: normal}
.button{background-color: #D6E1E2; font-size: 11px; color: #264973; padding: 1px; padding-left: 10px; padding-right: 10px}
.codeblock{background-color: #E3E5E3; border-style: solid; border-width: 1px;
	border-color: #B8C1B7; padding: 10px; padding-left: 35px; 
	background-image: url(images/code2.gif); background-repeat: repeat-y; font-size: 12px}
@charset "windows-1251";

.readmenu{border-top-style: solid; border-width: 1px; border-color: #6B8699; border-left-style: solid}

div.nametemaread{float: left; margin: 0px}
.nametemaread{padding: 3px; padding-left: 20px; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; font-weight: bold; font-style: oblique}
.tablenametemaread{height: 70; padding: 10px; background-color: #FFFFFF}
div.nextback{float: right; padding-right: 20px}
.posttext{font-size: 12px; color: #000000; line-height: 16px}
.postbody{background-color: #F8F8F8}
.postbodynew{background-color: #FFFFFF}

p.linkback{text-align: left; font-size: 11px; margin: 0px; background-image: url(images/backpage.gif); background-repeat: no-repeat; 
 background-position: left;  margin-left: 20px; padding-left: 20px;}
a.linkback{color: #21353D} 
p.linknext{text-align: left; font-size: 11px; margin: 0px; background-image: url(images/nextpage.gif); background-repeat: no-repeat; 
 background-position: left; margin-left: 60px; padding-left: 20px; color: #E3D41B}
a.linknext{color: #21353D} 
.fonposts{background-color: #D1D1D1;}
.infopost{font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #969696}
.postmenu{font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #969696; text-align: right}

.codeblock{background-color: #E3E5E3; border-style: solid; border-width: 1px;
	border-color: #B8C1B7; padding: 10px; padding-left: 35px; 
	background-image: url(images/code2.gif); background-repeat: repeat-y; font-size: 12px}
.attachfile{float: right;}
.toauthor{font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #969696;}</style>
<?php
    // Меню
    require_once("forummenu.php");

    // Формируем ссылку для перехода к другому разделу
    $query = "SELECT * FROM $tbl_forums 
              ORDER BY pos";
    $frm = mysql_query($query);
    if(!$frm)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обращения к разделам форума");
    }
    if(mysql_num_rows($frm))
    {
      ?>
      <table width=100% 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">
      <tr class="header" align="center" valign="middle">
      <?php
      while($forum = mysql_fetch_array($frm))
      {
        echo "<td><a href=themes.php?id_forum=$forum[id_forum]>$forum[name]</a></td>";
      }
      echo "</tr></table><br><br>";
    }

    ?>
    <table width="100%" border="0" bgcolor="silver" cellspacing="1" cellpadding="0" style='background-color: silver'>
    <tr>
    <?php
    // Выбираем первое сообщение темы
    $query = "SELECT * FROM $tbl_posts 
              WHERE id_theme = $id_theme
              ORDER BY parent_post
              LIMIT 1";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка обращения к сообщениям");
    }
    if(mysql_num_rows($pst))
    {
      $posts = mysql_fetch_array($pst);
      // Рекурсивно выводим все подчинённые сообщения
      put_post_admin($posts['id_post'],
              $id_theme,
              5,
              $id_forum,
              "../../skins/base/",
              "../../forum/");
            
    }
    echo "</table>";
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

  // Рекурсивная функция вывода сообщений
  // $id_post - идентификационный номер сообщения
  // $id_theme - идентификационный номер темы
  // $indent - процент отступа первый вызов всегда с 0,
  // $id_forum - текущий форум
  // $skin - путь к скину
  // $forum - путь к форуму
  // при рекурсивоном спуске функция автоматически вычисляет 
  // значение этого параметра
  function put_post_admin($id_post,
                          $id_theme,
                          $indent,
                          $id_forum,
                          $skin,
                          $forum)
  {
    // Объявляем название таблиц глобальным
    global $tbl_posts;
    // Выводим сообщение с id_post == $id_post
    $query = "SELECT * FROM $tbl_posts 
              WHERE id_post = $id_post";
    $pst = mysql_query($query);
    if(!$pst)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при выборке сообщений темы");
    }
    if(mysql_num_rows($pst))
    {
      $posts = mysql_fetch_array($pst);
      post_down_admin($id_post,
                $id_theme,
                $indent,
                $id_forum,
                $posts['id_author'],
                $posts['author'],
                $posts['time'],
                $posts['putfile'],
                $posts['name'],
                $posts['url'],
                $skin,
                $posts['hide'],
                $forum);
      // Выводим подчинённые сообщения
      $query = "SELECT * FROM $tbl_posts 
                WHERE parent_post = $id_post
                ORDER BY id_post";
      $pst = mysql_query($query);
      if(!$pst)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при выборке сообщений темы");
      }
      $num_rows = mysql_num_rows($pst);
      if($num_rows)
      {
        while($posts = mysql_fetch_array($pst))
        {
          $shap_indent=5;   
          if ($num_rows*$shap_indent>350) $shap_indent = 3;
          // Вычисляем отступ
          if($indent<70) $temp = ($shap_indent + $indent*(95)/100);
          else $temp = (5 + $indent*(100 - $indent)/100);
          // Рекурсивно вызываем функцию putpost для обработки подчинённых постов
          put_post_admin($posts['id_post'],
                  $id_theme,
                  $temp,
                  $id_forum,
                  $skin,
                  $forum);
        }
      }
    }
  }
  // Функция вывода поста на страницу
  function post_down_admin(
           $id_post,
           $id_theme,
           $indent,
           $id_forum,
           $id_author,
           $author,
           $time,
           $file,
           $name,
           $puturl,
           $skin,
           $posthide,
           $forum)
  {
    // Таблица сообщения - под каждое сообщение - своя таблица
    ?>
    <tr><td>
    <table border="0" width="100%" class="postbody" cellpadding="0" cellspacing="0">
    <?
    // Выводим заголовок сообщения: ник, время создания сообщения
    // Если id_author не равно 0 значит автор зарегистрирован - нужно выдать на него ссылку
    if($id_author != 0)
      echo "<tr>
              <td width='".$indent."%'>&nbsp;</td>
              <td class=infopost>автор: <a class=authorreg href=info.php?id_forum=$id_forum&id_author=$id_author>".htmlspecialchars($author)."</a>&nbsp;&nbsp;&nbsp;(".$time.")</td>
              <td width=50>&nbsp;</td>
            </tr>";
    else
      echo "<tr>
              <td width='".$indent."%'>&nbsp;</td>
              <td class=infopost>автор: <em class=author>".htmlspecialchars($author)."</em>&nbsp;&nbsp;&nbsp;(".$time.")</td>
              <td width=50>&nbsp;</td>
            </tr>";
    // Выводим тело сообщения
    // Если есть прикреплённый файл(рисунок) выводим ссылку
    $writefile = "";
    if($file != "" && $file != "-" && is_file("../$forum/".$file))
    {
      // Если файл не нулевой длины выдаём на него ссылку
      if(filesize("../$forum/".$file)) $writefile = "<a href=../$forum/".$file."><img border=0 src={$skin}images/flopy.gif></a>";
      // Иначе уничтожаем его
      else unlink($file);
    }

    // Обрабатываем текст поста
    $postbody = post_work_up($name);
    // Выясняем, статус темы и выделяем жирным
    // соответствующее слово
    $show = "Отобразить";
    $hide = "Скрыть";
    $lock = "Закрыть";
    // Переменная $posthide, на самом деле может принимать
    // три значения - show, hide и lock
    $$posthide = "<b>".$$posthide."</b>";
    // Формируем строку для правки сообщения
    $edit = "<td class=postmenu>
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=pstshow.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post
                  title='Сделать сообщение доступным для посетителей'>$show</a>
               &nbsp;&nbsp;&nbsp;
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=psthide.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post
                  title='Сделать сообщение недоступной для посетителей'>$hide</a>
               &nbsp;&nbsp;&nbsp;
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=pstlock.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post
                  title='Запретить ответ на данное сообщение'>$lock</a>
               &nbsp;&nbsp;&nbsp;
               <img src='{$skin}images/pen.gif' border='0' width='20' height='15'>
               <a href=pstedit.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post>Править</a>
             </td>";
    // Выводим тело сообщения
    echo "<tr valign=top>
            <td width='$indent%'>&nbsp;</td>
            <td><p class=posttext>$postbody".$url."</p></td>
            <td align=center>$writefile</td></tr>";
    echo "<tr>
            <td width='$indent%'>&nbsp;</td>
            $edit
            <td>&nbsp;</td></tr>";
    echo "<tr>
            <td width='$indent%'>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td></tr>";
    echo "</table>";
    echo "</td></tr>";
  }
?>