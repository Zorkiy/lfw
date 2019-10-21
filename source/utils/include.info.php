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
<table>
  <tr><td><p class="fieldname">Имя</td><td><p class=authortext><?php echo htmlspecialchars($author['name'], ENT_QUOTES);?></td></tr>
  <?php
    if(!empty($author['email']))
    {
      $pattern = "/^[-0-9a-z_\.]+@[0-9a-z_^\.]+\.[a-z]{2,6}$/i";
      if(preg_match($pattern, $author['email']))
      {
        echo "<tr>
                <td><p class=\"fieldname\">e-mail</td>
                <td><p class=text><a href=\"mail.php?id_forum=$id_forum&id_author=$author[id_author]\">написать письмо</a>
                </td>
              </tr>";
      }
      else
      {
        echo "<tr>
                <td><p class=\"fieldname\">e-mail</td>
                <td><p class=text>".htmlspecialchars($author['email'], ENT_QUOTES)."</td>
              </tr>";
      }
    }
  ?>
  <tr><td><p class="fieldname">URL</td><td><p class=text><a target="_blank" href="<?php echo is_http($author['url']);?>"><?php echo is_http($author['url']);?></a></td></tr>
  <tr><td><p class="fieldname">ICQ</td><td><p class=text>
  <?php
    $icq = $author['icq'];
    if(!empty($icq) && $icq != "-")
    {
      $icq = htmlspecialchars($icq, ENT_QUOTES);
      echo "<a href='http://www.icq.com/scripts/search.dll?to=$icq' 
               title='Добавить в мой контакт лист' target='_blank'> 
            <img src='http://wwp.icq.com/scripts/online.dll?icq=$icq&img=5'
                 width=18 height=18 border=0>$icq</a>";
    }
  ?></td></tr>
  <tr><td><p class="fieldname">О себе</td><td><p><?php echo nl2br(htmlspecialchars($author['about'], ENT_QUOTES));?></td></tr>
  <tr><td><p class="fieldname">Порядковый номер</td><td><p class=texthelp><?php echo htmlspecialchars($id_author, ENT_QUOTES); ?></td></tr>
  <tr><td><p class="fieldname">Количество сообщений</td><td><p class=texthelp><?php echo $author['themes'];?></td></tr>
  <tr><td><p class="fieldname">Последнее посещение</td><td><p class=texthelp><?php echo convertdate($author['time']); ?></td></tr>
  <tr>
    <td><p class="fieldname">Все темы автора</td>
    <td><p class=texthelp>
      <a href=authorthmes.php?id_author=<?php echo $author['id_author']; ?>&id_forum=<?php echo htmlspecialchars($_GET['id_forum']);?>>Живой форум</a>
      <a href=authorthmes.php?id_author=<?php echo $author['id_author']; ?>&id_forum=<?php echo htmlspecialchars($_GET['id_forum']);?>&arch=archiv>Архив</a>&nbsp;&nbsp;
    </td>
  </tr>
  <?php
    if(!empty($author['photo']) && $author['photo'] != "-" && is_file($author['photo']))
    {
      // Если фото не нулевой длины выводим его
      if(filesize($author['photo'])) 
      {
        echo "<tr><td colspan=2><p class=fieldname align=center >Фото<br><br><img border=1 src='".$author['photo']."'></td></tr>";
      }
    }
  ?>
</table>