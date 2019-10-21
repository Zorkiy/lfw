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

  // Подключаем верхний шаблон
  $pagename = "Произошла ошибка при работе сайта";
  $keywords = "Произошла ошибка при работе сайта";
  require_once("../utils/topforum.php");

  ?>
    <div class="main_txt">В работе сайта произошла ошибка.
      Приносим Вам свои извинения. Если вас не затруднит, сообщите
      пожалуйста администрации об обстоятельствах её возникновения.</td>
    </div>
  <?php
  // Выводим завершение страницы
  include "../utils/bottomforum.php";
?>