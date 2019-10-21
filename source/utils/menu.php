<?php
  ////////////////////////////////////////////////////////////
  // Форум - LiteForum
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 
?>
<table border=0 width=100%>
  <tr valign="bottom">
    <td><p class=menu><img src="<?php echo $skin; ?>images/newtema.gif" border="0" width="20" height="15"><a title="Создать новую тему" class=menu href=addtheme.php?id_forum=<?php echo $id_forum; ?>>Новая&nbsp;тема</a></td>
    <td><p class=menu><img src="<?php echo $skin; ?>images/enterforum.gif" border="0" width="20" height="15">&nbsp;<a title="Зарегистрироваться на форуме" class=menu href=register.php?id_forum=<?php echo $id_forum; ?>>Регистрация</a></td>   
    <td><p class=menu><nobr><img src="<?php echo $skin; ?>images/enter.gif" border="0" width="20" height="15">&nbsp;<a title="Вход на форум" class=menu href=enter.php?id_forum=<?php echo $id_forum; ?>>Вход</a> /
        <a title="Выход" class=menu href=exit.php?id_forum=<?php echo $id_forum;?>>Выход</a></nobr></td>
    <td>
      <p class=menu>
        <img src="<?php echo $skin; ?>images/find.gif" border="0" width="20" height="15">
          <a title="Поиск по сайту" class=menu href=search.php?id_forum=<?php echo $id_forum;?>>Поиск по сайту</a>
          <a title="Поиск по форуму" class=menu href=srchform.php?id_forum=<?php echo $id_forum;?>>(по форуму)</a>
    </td>
    <?php
      // Если выбран список тем
      //if ($showlisttopics)
      if(basename($_SERVER['PHP_SELF']) == 'read.php')
      { 
        // Переменные $id_theme и $id_theme_archive определяются в utils/newpostslist.php
        if($id_theme > $id_theme_archive)
        {
          echo "<td><p class=menu><img src=".$skin."images/listforum.gif border=0 width=20 height=15>&nbsp;<a title='Показать список тем форума' class=menu href=index.php?id_forum=".$id_forum."&page=".$page.">Список&nbsp;тем</a></td>";
        }
        else
        {
          echo "<td><p class=menu><img src=".$skin."images/listforum.gif border=0 width=20 height=15>&nbsp;<a title='Показать список тем форума' class=menu href=archive.php?id_forum=".$id_forum."&page=".$page.">Список&nbsp;тем</a></td>";
        }
      }
    ?> 
    <td><p class=menu><img src="<?php echo $skin; ?>images/check.gif" border="0" width="20" height="15"><a title="Пометить все темы форума как прочитанные" class=menu href=readall.php?id_forum=<?php echo $id_forum;?>>Отметить&nbsp;всё</a></td>  
    
      <?php
        if($show_switch && $settings['show_struct_switch'] == 'yes') {
          ?>
            <td class="switchtypeforum"  align="center">
            <p class=texthelp><nobr>вид форума:</nobr><br>
            <nobr>
          <?php
          // Если определена переменная отображаем линейный форум
          if($lineforum)
          {   
            if($lineforumdown == "")
            {
            ?>
              <a href="setstruct.php?struct=1&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>&down=true" title="Линейный форум (новые сообщения вниз)">
              <img src="<?php echo $skin; ?>images/lineforumup.gif" border="0" width="20" height="15" alt="Линейный форум (новые сообщения вниз)"></a>
            <?php 
            }
            else
            {
            ?>
               <a href="setstruct.php?struct=1&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>" title="Линейный форум (новые сообщения вверх)">
               <img src="<?php echo $skin; ?>images/lineforumdown.gif" border="0" width="20" height="15" alt="Линейный форум (новые сообщения вверх)"></a>
            <?php 
            }
            ?>
            <a href="setstruct.php?struct=0&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>" title="Структурный форум">
            <img src="<?php echo $skin; ?>images/structforumhide.gif" border="0" width="20" height="15" alt="Структурный форум"></a><?
          }
          else
          {
          $imgstructforum = "structforum.gif";
          ?>
            <a href="setstruct.php?struct=1&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>" title="Линейный форум"><img src="<?php echo $skin; ?>images/lineforumuphide.gif" border="0" width="20" height="15" alt="Линейный форум"></a>
            <img src="<?php echo $skin; ?>images/<? echo $imgstructforum ?>" border="0" width="20" height="15" alt="Структурный форум">
          <?php } ?>
        </nobr>
       </td>
    <? } ?>
  </tr>
</table>