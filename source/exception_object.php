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

  // Заголовок
  require_once("utils.title.php");

  // Подключаем верхний шаблон
  $pagename = "Произошла ошибка при работе сайта";
  $keywords = "Произошла ошибка при работе сайта";
  require_once ("templates/top.php");

  // Название
  echo title($pagename);
  ?>
    <div class="main_txt">В работе сайта произошла ошибка.
      Приносим Вам свои извинения. Если вас не затруднит, сообщите
      пожалуйста администрации об обстоятельствах её возникновения.</td>
    </div>
<?php
  //Подключаем нижний шаблон
  require_once ("templates/bottom.php");
?>