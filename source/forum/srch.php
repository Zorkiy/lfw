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
  error_reporting(E_ALL & ~E_NOTICE); 

  // Подключаем SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Функции для работы со временем
  require_once("../utils/utils.time.php");
  // Функции для работы с сообщениями
  require_once("../utils/utils.posts.php");
  // Настройки форума
  require_once("../utils/utils.settings.php");
  // Функции для работы с пользователями
  require_once("../utils/utils.users.php");
  // Функция для работы с файлами
  require_once("../utils/utils.files.php");
  // Подключаем постраничную навигацию
  require_once("../utils/utils.pager.php");

  try
  {
    // Задаём название форума
    $nameaction="Поиск на форуме";
    // Выводим "шапку" страницы
    require_once("../utils/topforumaction.php");
    if(!isset($_GET['logic'])) $_GET['logic'] = 1;
  ?>
  <p class=linkbackbig><a href=index.php?id_forum=<?php echo $_GET['id_forum']; ?>>Вернуться</a></p>
  <div class=blockremark><p class=texthelp align=left>Введите ключевые слова для поиска,
    настройте параметры и нажмите кнопку "Найти".<br>
    Логика "ИЛИ" означает, что в результатах поиска будут те темы, где встречается хотя бы одно
    из введенных Вами слов. Логика "И" означает, что будут найдены только те сообщения, где
    встречаются все введенные Вами слова одновременно.<br>
    Ключевое слово необязательно набирать полностью, т.е. если вы ищете "сотовый телефон", для
    поиска этой комбинации можно ввести только часть слов "сотов телеф", это обеспечит поиск
    фраз "сотовым телефоном", "сотовыми телефонами" и т.п.<br>
    Искомые слова должны содержать четыре или более символов, т.е. слова "sms", "WAP", "код"
    обнаружить не удастся, это связано с конструктивными особенностями используемой в форуме
    базы данных.
    </div>
  <form action=srch.php?id_forum=<?php echo $_GET['id_forum']; ?> method=get>
  <input type=hidden name=id_forum value=<?php echo $_GET['id_forum']; ?>>
  <table border="0">
    <tr>
      <td><p class="fieldname">Ключевые слова</td>
      <td><input class=input type=text name=name size=60 maxlength=200 value="<?php echo htmlspecialchars(stripslashes($_GET['name']),ENT_QUOTES); ?>"></td>
    </tr>
    <tr>
      <td><p class="fieldname">Количество <br>выводимых тем</td>
      <td><input class=input type=text name=numberthemes size=3 maxlength=10 value=<?php if(empty($_GET['numberthemes'])) echo 30; else echo htmlspecialchars($_GET['numberthemes']); ?>></td>
    </tr>
    <tr>
      <td><p class="fieldname"><nobr>Искать в...</nobr></td>
        <td>
          <select class=input type=text name=srchwhere>
             <option value=1 <?php if($_GET['srchwhere'] == 1) echo "selected"; ?>>в названиях тем
             <option value=2 <?php if($_GET['srchwhere'] == 2) echo "selected"; ?>>в сообщениях
          </select>
       </td>
    </tr>
    <tr>
      <td><p class="fieldname"><nobr>Искать в форуме...</nobr></td>
        <td>
          <select class=input type=text name=id_forum>
            <?php
              if($_GET['id_forum'] == 0) $strtmp = "selected";
              else $strtmp = "";
              echo "<option value=0 $strtmp>Не имеет значения";
              $query = "SELECT * FROM forums 
                        WHERE hide != 'hide' 
                        ORDER BY pos";
              $frm = mysql_query($query);
              if($frm)
              {
                while($forums = mysql_fetch_array($frm))
                {
                  if($forums['id_forum'] == $_GET['id_forum']) $strtmp = "selected";
                  else $strtmp = "";
                  echo "<option value=".$forums['id_forum']." $strtmp>".$forums['name'];
                }
              }
            ?>
          </select>
       </td>
    </tr>
    <tr>
      <td><p class="fieldname">Логика</td>
      <td><p>
          <input name=logic type=radio value=1 <?php if($_GET['logic'] == 1) echo "checked"; ?>>И&nbsp;&nbsp;&nbsp;
          <input name=logic type=radio value=0 <?php if($_GET['logic'] == 0) echo "checked"; ?>>ИЛИ
      </td>
    </tr>
    <tr><td>&nbsp;</td><td><input class=button type=submit name=send value=Найти></td></tr>
  </table>
  </form>
  <?php
    // Извлекаем переменные переданные методом POST
    if(!preg_match("|^[\d]+$|",$_GET['numberthemes']) && !empty($_GET['numberthemes'])) exit("Недопустимый формат URL");
    if(!preg_match("|^[\d]+$|",$_GET['id_forum']) && !empty($_GET['id_forum'])) exit("Недопустимый формат URL");
    if(!preg_match("|^[\d]+$|",$_GET['page']) && !empty($_GET['page'])) exit("Недопустимый формат URL");
    if($_GET['numberthemes']>100) $_GET['numberthemes'] = 100;
    if (!get_magic_quotes_gpc())
    {
      $_GET['name']         = mysql_escape_string($_GET['name']);
      $_GET['numberthemes'] = mysql_escape_string($_GET['numberthemes']);
      $_GET['srchwhere']    = mysql_escape_string($_GET['srchwhere']);
      $_GET['logic']        = mysql_escape_string($_GET['logic']);
    }
    
    // Длину поисковой фразы ограничиваем 70 символами
    $_GET['name'] = substr($_GET['name'],0,70);
    // Количество слов ограничиваем 6 штуками
    $arr_words = explode(" ", $_GET['name'],7);
    if (count($arr_words) > 6) unset($arr_words[6]);
    $_GET['name'] = implode(" ", $arr_words);
    
    if(!isset($_GET['logic'])) $_GET['logic'] = 1;
  
    $name         = $_GET['name'];
    $numberthemes = $_GET['numberthemes'];
    $srchwhere    = $_GET['srchwhere'];
    $logic        = $_GET['logic'];
    $page         = $_GET['page'];
    $id_forum     = $_GET['id_forum'];
    if(!empty($name) && !empty($numberthemes))
    {
      if (!get_magic_quotes_gpc())
      {
        $name = mysql_escape_string($name);
      }
  
      if(empty($id_forum)) $id_forum = 0;
      if($id_forum === 0) $tmp_id_forum = "";
      else $tmp_id_forum = "AND id_forum = $id_forum ";
      // Элемент постраничной навигации
      if(empty($page)) $page = 1;
      $start = ($page - 1)*$numberthemes;
  
      // Формирование и выполнение SQL-запросов
      switch($srchwhere)
      {
        case 1: // Поиск в названиях тем и авторах
        {
          $name = trim($name);
          $temp = strtok($name," ");
          if($logic==0) $logstr = "or";
          else $logstr = "and";
          while ($temp)
          {
            // Полнотекстовый поиск в столбцах name и authors
            if($is_query)
              $tmp1 .= " $logstr MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            else
              $tmp1 .= "MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            $is_query = true;
            $temp = strtok(" ");
		  $num_words ++;
          }       
          // Формируем SQL-запрос
          $query = "SELECT * FROM $tbl_themes 
                    WHERE ($tmp1)
                          $tmp_id_forum AND
                          hide != 'hide'
                    UNION
                    SELECT id_theme, 
                           name, 
                           author, 
                           id_author, 
                           last_author, 
                           id_last_author, 
                           hide, 
                           `time`, 
                           id_forum 
                    FROM $tbl_archive_themes 
                    WHERE ($tmp1)
                          $tmp_id_forum AND
                          hide != 'hide'
                          ORDER BY time DESC
                          LIMIT $start, $numberthemes";
          $thm = mysql_query($query);
  
          // Выясняем общее число тем
          $query = "SELECT COUNT(*) FROM $tbl_themes
                    WHERE ($tmp1) 
                          $tmp_id_forum AND
                          hide != 'hide'";
          $tot = mysql_query($query);
          if(!$tot)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при поиске");
          }
          $total = mysql_result($tot,0);
  
          // Выясняем общее число тем
          $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                    WHERE ($tmp1) 
                          $tmp_id_forum AND
                          hide != 'hide'";
          $tot = mysql_query($query);
          if(!$tot)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при поиске");
          }
          $total += mysql_result($tot,0);
          break;
        }
        case 2: // Полнотекстовый поиск по сообщениям
        {
          $name = trim($name);
          $temp = strtok($name," ");
          if($logic==0) $logstr = "or";
          else $logstr = "and";
          while ($temp)
          {
            // Полнотекстовый поиск в столбцах name и authors
            if($is_query)
              $tmp1 .= " $logstr MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            else
              $tmp1 .= "MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            $is_query = true;
            $temp = strtok(" ");
          }       
          // Формируем SQL-запрос
          $query = "SELECT id_theme FROM $tbl_posts
                    WHERE ($tmp1) AND
                          hide != 'hide' 
                    GROUP BY id_theme
                    UNION
                    SELECT id_theme FROM $tbl_archive_posts
                    WHERE ($tmp1) AND
                          hide != 'hide' 
                    GROUP BY id_theme";
          $post = mysql_query($query);
          if(!$post)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при выборке тем форума");
          }
          $numtot = mysql_num_rows($post);
          if($numtot>0)
          {
            $query = "SELECT * FROM $tbl_themes WHERE id_theme IN (";
            $query_tot = "SELECT COUNT(*) FROM $tbl_themes WHERE id_theme IN (";
            $query_arch = "SELECT id_theme, name, author, id_author, last_author, id_last_author, hide, time, id_forum 
                           FROM $tbl_archive_themes WHERE id_theme IN (";
            $query_tot_arch = "SELECT COUNT(*) FROM $tbl_archive_themes WHERE id_theme IN (";
            while($posts = mysql_fetch_array($post))
            {
              $query .= $posts['id_theme'].","; 
              $query_tot .= $posts['id_theme'].",";
              $query_arch .= $posts['id_theme'].","; 
              $query_tot_arch .= $posts['id_theme'].","; 
            }
            $query .= "0) $tmp_id_forum AND
                              hide != 'hide' 
                      GROUP BY id_theme 
                      UNION
                      $query_arch 0) $tmp_id_forum AND
                              hide != 'hide'
                      GROUP BY id_theme 
                      ORDER BY time DESC
                      LIMIT $start, $numberthemes";
            $thm = mysql_query($query);
            $query_tot .= "0) $tmp_id_forum AND
                                   hide != 'hide'
                      GROUP BY id_theme
                      ORDER BY time DESC";
            $tot = mysql_query($query_tot);
            if(!$tot)
            {
              throw new ExceptionMySQL(mysql_error(), 
                                       $query,
                                      "Ошибка при поиске");
            }
            $total = mysql_num_rows($tot);
            $query_tot_arch .= "0) $tmp_id_forum AND
                                   hide != 'hide'
                      GROUP BY id_theme
                      ORDER BY time DESC";
            $tot = mysql_query($query_tot_arch);
            if(!$tot)
            {
              throw new ExceptionMySQL(mysql_error(), 
                                       $query,
                                      "Ошибка при поиске");
            }
            $total += mysql_num_rows($tot);
          }
          else
          {
            $tmp = explode(" ", $name);
            if(count($tmp) > 1)
            {
              foreach($tmp as $line) if(strlen($line)<4) exit("<p class=result>Запрос содержит слова, с числом символов меньше 4 - попробуйте исключить их из поиска.</p>");
            }
            else
            {
              if(strlen($name)<4) exit("<p class=result>Запрос содержит слова, с числом символов меньше 4 - попробуйте исключить их из поиска.</p>");
            }
            exit("<p class=result>К сожалению, по Вашему запросу ничего не найдено. Попробуйте изменить условия поиска.</p>");
          }
          break;
        }
      }
      if($thm) // && $tot)
      {
        // Выясняем количество тем
        $numtot = mysql_num_rows($thm);
        if($numtot>0)
        {
          // Начало таблица с темами
          ?>
             <p class="zagtext">Результаты:</p>
             <table class=srchtable border="0" width="100%" cellpadding="4" cellspacing="1" >
                <tr class="tableheadern" align="center">
                  <td class="tableheadern"><p class=fieldnameindex><nobr>Кол-во</nobr> сообщ.</p></td>
                  <td class="tableheadern"><p class=fieldnameindex>Название темы</p></td>
                  <td class="tableheadern"><p class=fieldnameindex>Автор</p></td>
                  <td class="tableheadern"><p class=fieldnameindex>Последнее сообщение</p></td>
                </tr>
          <?php
          if($lasttime == "") $lasttime = date("Y-m-d H:i:s",time()-3600*2);
  
          // Загружаем первичный ключ темы, которая последняя в 
          // архивной таблице
          $query = "SELECT id_theme FROM $tbl_archive_number LIMIT 1";
          $arh = mysql_query($query);
          if(!$arh)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при поиске");
          }
          if(mysql_num_rows($arh)) $id_theme_archive = @mysql_result($arh, 0);
          // Все темы, которые имеют первичный ключ ниже $id_theme_archive
          // находятся в архиве, все, что выше - в "живом форуме"
  
          while($themes = mysql_fetch_array($thm))
          {
            if($themes['id_theme'] > $id_theme_archive) $tbl = $tbl_posts;
            else $tbl = $tbl_archive_posts;
            // Считаем количество сообщений в данной теме.
            $query = "SELECT COUNT(*) FROM $tbl
                      WHERE id_theme = $themes[id_theme] AND hide != 'hide'";
            $pst = mysql_query($query);
            if(!$pst) 
            {
              throw new ExceptionMySQL(mysql_error(), 
                                       $query,
                                      "Ошибка при подсчёте количества сообщений темы");
            }
            if(mysql_num_rows($pst)) $theme_count = mysql_result($pst,0);
            
            // Выводим тему
            // Предварительно обрабатываем угловые скобки и ентеры
            // Обрабатываем теги [b],[/b],[i] и [/i]
            $namet = theme_work_up($themes['name']);
            // Выводим список тем
            if($page != "") $strpage = "&page=".$page;
  
            // Количество сообщений в теме
            echo "<tr class=trtablen><td class=trtemaheight align=center><p class=nametema><nobr><a class=nametema href=read.php?id_forum=$themes[id_forum]&id_theme=$themes[id_theme]>$theme_count</nobr></p></td>";
            // Название
            echo "<td><p><a target='_blank' href=read.php?id_forum=$themes[id_forum]&id_theme=$themes[id_theme]>$namet</a></td>";
            // Автор
            if($themes['id_author'] != 0) echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$themes[id_forum]&id_author=$themes[id_author]>".htmlspecialchars($themes['author'])."</a></td>";
            else echo "<td><p class=author>".htmlspecialchars($themes['author'])."</td>";
            // Время последнего обновления темы
            echo "<td><p class=texthelp>".substr($themes['time'],0,10)." в ".substr($themes['time'],10,6)."</p></td></tr>";
          }
          // Конец таблицы по выводу тем форума
          // Выводим ссылки на другие темы форума
          $page_link = 1;
          $number = (int)($total/$numberthemes);
          if((float)($total/$numberthemes)-$number != 0) $number++;
          $url = "&id_forum=$id_forum&name=".urlencode($name)."&numberthemes=$numberthemes&srchwhere=$srchwhere&logic=$logic";
          echo "<tr><td class=bottomtablen colspan=4><p class=texthelp>";
          pager($page, $total, $numberthemes, $page_link, $url);
          echo "</td></tr>";
        }
      }
    }
    // Выводим завершение страницы
    include "../utils/bottomforumaction.php";
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