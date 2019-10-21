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

  // Форматирование объёма базы данных
  function valuesize($filesize)
  {
    // Если размер файл превышает 1024 байта,
    // пересчитываем размер в Кб
    if($filesize > 1024)
    {
      $filesize = (float)($filesize/1024);
      // Если размер файл превышает 1024 Кбайта,
      // пересчитываем размер в Мбайты
      if($filesize > 1024)
      {
        $filesize = (float)($filesize/1024);
        // Округляем дробную часть до
        // первого знака после запятой
        $filesize = round($filesize, 1);
        return $filesize." Мб";
      }
      else
      {
        // Округляем дробную часть до
        // первого знака после запятой
        $filesize = round($filesize, 1);
        return $filesize." Кб";
      }
    }
    else
    {
      return $filesize." байт";
    }
  }

  // Формирование размера файла
  function getfilesize($filename)
  {
    $filesize=filesize($filename);
    if($filesize > 1024)
    {
      $filesize = (float)($filesize/1024);
      // Если размер файл превышает 1024 Кбайта
      // пересчитываем размер в Мбайты
      if($filesize > 1024)
      {
        $filesize = (float)($filesize/1024);
        // Округляем дробную часть до
        // первого знака после запятой
        $filesize = round($filesize, 1);
        return $filesize." Мб";
      }
      else
      {
        // Округляем дробную часть до
        // первого знака после запятой
        $filesize = round($filesize, 1);
        return $filesize." Кб";
      }
    }
    else
    {
      return $filesize." байт";
    }
  }
?>
