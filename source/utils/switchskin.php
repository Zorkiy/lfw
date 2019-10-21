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
<form action="skin.php" method="POST">
<?php
$skin_dir = opendir("../skins");
  while(($dir = readdir($skin_dir)))
  {
    // Если очередной объект в папке skins
    // является директорией, заносим его в
    // массив $skin_list()
    if(@is_dir("../skins/".$dir) && $dir != "." && $dir != "..") $skin_list[] = $dir;
  }
  // Закрываем директорию
  closedir($skin_dir);
?>

<table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class=switchforum>      
                <nobr>
                <p class=texthelp>Выбрать другой skin<br>
               <select class=input type=text name='skin'>
<?php
  foreach($skin_list as $value)
  {
    // Отображаем выбранный форум
    if(isset($_COOKIE['skin']))
    {
      if($_COOKIE['skin'] == $value ) $chk = "selected";
      else $chk = "";
    }
    else
    {
      if($settings['skin'] == $value ) $chk = "selected";
      else $chk = "";
    }
    echo "<option $chk value=$value>$value";
  }
?>
</select>
  <input class=button type=submit value="Выбрать">
</nobr>
  </td></tr>
  </table>
  </form>