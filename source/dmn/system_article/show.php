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

  $filename = $_GET['img'];
  $size = getimagesize($filename);
?>
<html>
<head>
<title>Просмотр фотографии</title>
<meta http-equiv="imagetoolbar" content="no">
<style>
 table{font-size: 12px; font-family: Arial, Helvetica, sans-serif; background-color: #F3F3F3;}
</style>
</head>
<body marginheight="0" marginwidth="0" rightmargin="0" bottommargin="0" leftmargin="0" topmargin="0">
<table height="100%" cellpadding="0" cellspacing="0" width="100%" border="1">
  <tr>
    <td height="100%" valign="middle" align="center">
     Дождитесь загрузки изображения
     <div  style="position: absolute; top: 0px; left: 0px"><img src="<? echo $filename;?>" border="0" <?= $size[3] ?>></div>
    </td>
  </tr>
</table>    
<div style="position: absolute; z-index: 2; width: 100%; bottom: 5px" align="center">
<input class=button type="submit" value="Закрыть" onclick="window.close();"></div>
</body>
</html>