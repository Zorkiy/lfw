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

  eval("\$menu$menu=\"class=tdactivemenu\";");    
  $dx=100/7;
?>
<table width=100% 
       class="table" 
       border="0" 
       cellpadding="0" 
       cellspacing="0">
<tr class="header" align="center" valign="middle">
  <td width=<?echo $dx?>% class="header"><a class=menu href=index.php>Разделы форума</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=themes.php>Модерирование</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=statistics.php>Статистика</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=authorslist.php>Участники форума</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=settings.php>Настройки форума</a></td>
  <td width=<?echo $dx?>% class="header"><a class=menu href=links.php?part=1>Ссылки</a></td>  
  <td width=<?echo $dx?>% class="header"><a class=menu href=archive.php>Архивирование</a></td>
</tr>
</table><br><br>
