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
  // Постраничная навигация
  require_once("../utils/utils.pager.php");

  try
  {
    $title = $titlepage =  'Информационные ссылки';  
    $pageinfo = '<p class=help>На данной странице можно
    добавить, удалить или отредактировать информационные
    ссылки</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");

    // Количество позиций на странице
    $pnumber = 5;
    // Если в строке запроса не передана страница
    // выводим первую страницу
    $page = intval($_GET['page']);
    if(empty($page)) $page = 1;
    $begin = ($page - 1)*$pnumber;

    if(empty($_GET['part'])) $_GET['part'] = 1;
    else $_GET['part'] = intval($_GET['part']);

    // Выводим новостные позиции
    $query = "SELECT * FROM $tbl_links 
              WHERE part=$_GET[part]
              ORDER BY pos DESC
              LIMIT $begin, $pnumber";
    $res = mysql_query($query);
    if(!$res)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "Ошибка при обращении 
                               к таблице ссылок");
    }
    echo "<a class=menu href=lnkadd.php?part=$_GET[part]>Добавить ссылку</a>&nbsp;&nbsp;";
    if ($_GET['part']==1) echo "<a class=menu href=links.php?part=2>Информационные ссылки</a>";
    else echo "<a class=menu href=links.php?part=1>Сообщения</a>";
    echo "<br><br>";
    ?>    
    <table width=100% 
         class=table 
         border=0 
         align=center 
         cellpadding=0 
         cellspacing=0>
    <tr class=header align="center">
      <td width=50px>Позиция</td>
      <td>Ссылка</td>
      <td>Действия</td>
    </tr>
    <?php
    if(mysql_num_rows($res))
    {
      while($links = mysql_fetch_array($res))
      {
        $url = "id_link=$links[id_links]&part=$_GET[part]&page=$_GET[page]";
        // Если ссылка отмечена как невидимая (hide='hide'), выводим
        // ссылку "отобразить", если как видимия (hide='show') - "скрыть"
        if($links['hide'] == 'show')
        {
          $showhide = "<a href=lnkhide.php?$url title='Скрыть ссылку'>Скрыть</a>";
          $colorrow = "";
        }
        else
        {
          $showhide = "<a href=lnkshow.php?$url title='Отобразить ссылку'>Отобразить</a>";
          $colorrow = "class='hiddenrow'";
        }
        // Выводим новость
        echo "<tr $colorrow valign=top>
              <td>$links[pos]</td>
              <td>
              <a href='$links[url]'>".htmlspecialchars($links['name'])."</a><br>
              ".htmlspecialchars($links['url'])."
              </td>
              <td align=center>
                 $showhide<br>
                 <a href=# onClick=\"delete_position('lnkdel.php?$url','Вы действительно хотите удалить ссылку?');\" title='Удалить ссылку'>Удалить</a><br>
                 <a href=lnkedit.php?$url title='Редактировать ссылку'>Редактировать</a>
              </td>
            </tr>";
      }
    }
    ///////////////////////////////////////////////////////////
    // Постраничная навигация
    ///////////////////////////////////////////////////////////
    $page_link = 4;
    // Запрашиваем информацию об количестве всех тем
    $query = "SELECT COUNT(*) FROM $tbl_links 
              WHERE part = $_GET[part]";
    $total = query_result($query);
    $number = (int)($total/$pnumber);
    if((float)($total/$pnumber) - $number != 0) $number++;

    // Выводим ссылки на другие страницы
    echo "<tr><td class=bottomtablen colspan=3>";
    pager($page, 
          $total, 
          $pnumber, 
          3, 
          "&part=$_GET[part]");
    echo "</td></tr></table>";

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