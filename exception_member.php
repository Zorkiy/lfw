<?php
error_reporting(E_ALL & ~E_NOTICE);

// Заголовок
// require_once("utils.title.php");

// Подключаем верхний шаблон
$pagename = "Произошла ошибка при работе сайта";
$keywords = "Произошла ошибка при работе сайта";
// require_once ("templates/top.php");

// Название
// echo title($pagename);
?>
<div class="main_txt">В работе сайта произошла ошибка.
Приносим Вам свои извинения. Если вас не затруднит, сообщите
пожалуйста администрации об обстоятельствах её возникновения.</div>
<?php
//Подключаем нижний шаблон
// require_once ("templates/bottom.php");
?>