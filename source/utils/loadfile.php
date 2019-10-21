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
  ///////////////////////////////////////////////////
  // Загрузка файла-аттача на форуме
  ///////////////////////////////////////////////////
  if(!defined("ADDMESSAGE")) return;
  $path = '';
  // Если поле выбора файла(рисунка) не пустое,
  // закачиваем его на сервер и переименовываем
  if (!empty($_FILES['attach']['tmp_name']))
  {
    if($_FILES['attach']['size'] > $settings['size_file'])
      $error[] = "Слишком большая фотография (более ".valuesize($settings['size_file']).")";
    // Проверяем, не является ли файл скриптом PHP или Perl, 
    // html, если это так преобразуем его в формат .txt
    $extentions = array("#\.php#i",
                        "#\.phtml#i",
                        "#\.php3#i",
                        "#\.html#i",
                        "#\.htm#i",
                        "#\.hta#i",
                        "#\.pl#i",
                        "#\.xml#i",
                        "#\.inc#i",
                        "#\.shtml#i", 
                        "#\.xht#i", 
                        "#\.xhtml#i");
    // Извлекаем из имени файла расширение
    $ext = strrchr($_FILES['attach']['name'], "."); 
    // Формируем путь к файлу    
    $path="files/$id_theme-".date("YmdHis",time()).$ext; 
    foreach($extentions AS $exten) 
    {
      if(preg_match($exten, $ext)) 
        $path="files/$id_theme-".date("YmdHis",time()).".txt"; 
    }
    // Перемещаем файл из временной директории сервера в
    // директорию /files Web-приложения
    if(copy($_FILES['attach']['tmp_name'], $path))
    {
      // Уничтожаем файл во временной директории
      @unlink($_FILES['attach']['tmp_name']);
      // Изменяем права доступа к файлу
      @chmod($path, 0644);
    }
  }
?>