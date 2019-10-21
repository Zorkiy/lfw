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

  try
  {
    $title = $titlepage =  'Разделы форума';  
    $pageinfo = '<p class=help>На данной странице в выбранный 
                 форум можно добавить разделы. Это необходимо, 
                 если количество доступных вам баз данных ограничено. 
                 Тогда можно ограничиться одним форумом, создав 
                 в нем несколько разделов. Посетители смогут 
                 переключаться между ними, выбирая нужный 
                 раздел в выпадающем списке.
                 По умолчанию в каждом форуме создаётся один
                 раздел: Общий форум</p>';

    // Включаем заголовок страницы
    require_once("../utils/top.php");
    // Меню
    require_once("forummenu.php");
    ?>
    <a href="partadd.php"
       title="Добавить новый форум">Добавить новый форум</a><br><br>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center width=50>Поз.</td>
      <td align=center>Название форума</td>
      <td align=center>Краткое описание</td>
      <td align=center>Действия</td>
    </tr>
    <?php
      $query = "SELECT * FROM $tbl_forums
                ORDER BY pos";
      $frm = mysql_query($query);
      if(!$frm)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "Ошибка при обращении 
                                 к таблице форумов");
      }
      if(mysql_num_rows($frm))
      {
        while($forums = mysql_fetch_array($frm))
        {
          // Определяем скрыт форум или нет
          if($forums['hide'] == 'hide')
          {
            $showhide = "<a href=partshow.php?id_forum=$forums[id_forum] title='Сделать раздел видимым пользователям'>Отобразить</a>";
            $colorrow = "class='hiddenrow'";
          }
          else
          {
            $showhide = "<a href=parthide.php?id_forum=$forums[id_forum] title='Сделать раздел невидимым пользователям'>Скрыть</a>";
          }
          // Выводим информацию о форуме
          echo "<tr $colorrow>
                 <td align=center>$forums[pos]</td>
                 <td><a href=editpartform.php?id_forum=$forums[id_forum]>".htmlspecialchars($forums['name'], ENT_QUOTES)."</a></td>
                 <td>".htmlspecialchars($forums['logo'], ENT_QUOTES)."</td>
                 <td align=center>$showhide<br>
                   <a href=# onClick=\"delete_position('partdel.php?id_forum=$forums[id_forum]','Вы действительно хотите удалить раздел?');\" title='Удалить раздел и все его сообщения'>Удалить</a><br>
                   <a href=partedit.php?id_forum=$forums[id_forum] title='Внести исправления в название, правила и заглавную фразу раздела'>Редактировать</a><br>
                   <a href=partchn.php?id_forum=$forums[id_forum] title='Переместить все сообщения раздела в другой раздел форума'>Объединить</a></td>
                </tr>";
        }
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
?>