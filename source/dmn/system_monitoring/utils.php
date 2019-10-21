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

  function utf8_win($str)
  {
    $win = array("а","б","в","г","д","е","ё","ж","з","и",
                 "й","к","л","м","н","о","п","р","с","т",
                 "у","ф","х","ц","ч","ш","щ","ъ","ы","ь",
                 "э","ю","я","А","Б","В","Г","Д","Е","Ё",
                 "Ж","З","И","Й","К","Л","М","Н","О","П",
                 "Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ",
                 "Ъ","Ы","Ь","Э","Ю","Я"," ");
    $utf8 = array("\xD0\xB0","\xD0\xB1","\xD0\xB2","\xD0\xB3","\xD0\xB4",
                  "\xD0\xB5","\xD1\x91","\xD0\xB6","\xD0\xB7","\xD0\xB8",
                  "\xD0\xB9","\xD0\xBA","\xD0\xBB","\xD0\xBC","\xD0\xBD",
                  "\xD0\xBE","\xD0\xBF","\xD1\x80","\xD1\x81","\xD1\x82",
                  "\xD1\x83","\xD1\x84","\xD1\x85","\xD1\x86","\xD1\x87",
                  "\xD1\x88","\xD1\x89","\xD1\x8A","\xD1\x8B","\xD1\x8C",
                  "\xD1\x8D","\xD1\x8E","\xD1\x8F","\xD0\x90","\xD0\x91",
                  "\xD0\x92","\xD0\x93","\xD0\x94","\xD0\x95","\xD0\x81",
                  "\xD0\x96","\xD0\x97","\xD0\x98","\xD0\x99","\xD0\x9A",
                  "\xD0\x9B","\xD0\x9C","\xD0\x9D","\xD0\x9E","\xD0\x9F",
                  "\xD0\xA0","\xD0\xA1","\xD0\xA2","\xD0\xA3","\xD0\xA4",
                  "\xD0\xA5","\xD0\xA6","\xD0\xA7","\xD0\xA8","\xD0\xA9",
                  "\xD0\xAA","\xD0\xAB","\xD0\xAC","\xD0\xAD","\xD0\xAE",
                  "\xD0\xAF","+");
    return str_replace($utf8, $win, $str);
  }
  // Извлечение ссылок со страниц поисковой системы Yandex
  function yandex($url)
  {
    // Результирующий массив
    $result = array();
    // Загружаем содержимое страницы
    $contents = file_get_contents($url); 
    // Регулярное выражение
    $pattern = "|<a[\s]+tabindex=\"[\d]+\"(.*?)href=\"([^\"]+)\"[^>]+>(.*?)</a>|is";
    // Выполняем поиск по регулярному выражению
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 
    // Помещаем результаты в результирующий массив
    for($i = 0; $i < count($out[2]); $i++) 
    { 
      $result[$i]['url'] = $out[2][$i]; 
      $result[$i]['name'] = $out[3][$i]; 
    }
    return $result;
  } 
  // Извлечение ссылок со страниц поисковой системы Google
  function google($url)
  { 
    // Результирующий массив
    $result = array();
    // Загружаем содержимое страницы
    $contents = file_get_contents($url); 
    // Регулярное выражение
    $pattern = '|<div class=g><h2 class=r><a href=\"([^\"]+)\"[^>]*>(.+)</a>|isU'; 

    // Выполняем поиск по регулярному выражению
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 

    // Помещаем результаты в результирующий массив
    for($i = 0; $i < count($out[1]); $i++) 
    { 
      $result[$i]['url'] = $out[1][$i]; 
      $result[$i]['name'] = $out[2][$i]; 
    }
    return $result;
  }
  // Извлечение ссылок со страниц поисковой системы Rambler
  function rambler($url)
  {
    // Результирующий массив
    $result = array();
    // Загружаем содержимое страницы
    $contents = file_get_contents($url); 
    // Регулярное выражение
    $pattern = "|<li>[^>]+><a(.+?)href=\"([^\"]+)\"[^>]+>(.+?)</a>|is";
    // Выполняем поиск по регулярному выражению
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 
    // Помещаем результаты в результирующий массив
    for($i = 0; $i < count($out[2]); $i++) 
    { 
      $result[$i]['url'] = $out[2][$i]; 
      $result[$i]['name'] = $out[3][$i]; 
    }
    return $result;
  }
  // Извлечение ссылок со страниц поисковой системы Aport
  function aport($url)
  {
    // Результирующий массив
    $result = array();
    // Загружаем содержимое страницы
    $contents = file_get_contents($url); 
    // Регулярное выражение
    $pattern = "|<li value[^>]+>[^>]+>[\s]+<a href=\"([^\"]+)\"[^>]+>(.+?)</a>|is";
    // Выполняем поиск по регулярному выражению
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 
    // Помещаем результаты в результирующий массив
    for($i = 0; $i < count($out[1]); $i++) 
    { 
      $result[$i]['url'] = $out[1][$i]; 
      $result[$i]['name'] = $out[2][$i]; 
    }
    return $result;
  }
  function search($keyword, $site, $search)
  {
    // Количество просматриваемых страниц
    $pnumber = 10;
    // Результат
    $result = "";
    switch($search)
    {
      case 'yandex':
      {
        $result .= "Поисковая система Yandex:<br>";
        for($i = 0; $i < $pnumber; $i++)
        {
          $url = "http://www.yandex.ru/yandsearch?&p=$i&text=".urlencode($keyword);
          unset($arr);
          $arr = yandex($url);
          for($j = 0; $j < count($arr); $j++)
          {
            if(strpos($arr[$j]['url'], $site) !== false)
            {
              $result .= "Позиция N ".($i*10 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>".utf8_win($arr[$j]['name'])."</a><br>";
              $result .= "<a href=$url>Страница Yandex</a><br>";
              return $result;
            }
          }
        }
        $result .= "Сайт не найден<br>";
        return $result;
      }
      case 'google':
      {
        $result .= "Поисковая система Google:<br>";
        for($i = 0; $i < $pnumber; $i++)
        {
          $url = "http://www.google.ru/search?q=".urlencode($keyword).
                 "&complete=1&hl=ru&lr=&start=".($i*$pnumber)."&sa=N";
          unset($arr);
          $arr = google($url);
          for($j = 0; $j < count($arr); $j++)
          {
            if(strpos($arr[$j]['url'], $site) !== false)
            {
              $result .= "Позиция N ".($i*10 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>{$arr[$j][name]}</a><br>";
              $result .= "<a href=$url>Страница Google</a><br>";
              return $result;
            }
          }
        }
        $result .= "Сайт не найден<br>";
        return $result;
      }
      case 'rambler':
      {
        $result .= "Поисковая система Rambler:<br>";
        for($i = 0; $i < $pnumber; $i++)
        {
          $url = "http://www.rambler.ru/srch?oe=1251&words=".
                  urlencode($keyword)."&start=".($i*15 + 1);
          unset($arr);
          $arr = rambler($url);
          for($j = 0; $j < count($arr); $j++)
          {
            if(strpos($arr[$j]['url'], $site) !== false)
            {
              $result .= "Позиция N ".($i*15 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>{$arr[$j][name]}</a><br>";
              $result .= "<a href=$url>Страница Rambler</a><br>";
              return $result;
            }
          }
        }
        $result .= "Сайт не найден<br>";
        return $result;
      }
      case 'aport':
      {
        $result .= "Поисковая система Aport:<br>";
        for($i = 0; $i < $pnumber; $i++)
        {
          $url = "http://sm.aport.ru/scripts/template.dll?r=".
                  urlencode($keyword)."&That=std&p=$i&".
                  "HID=1_2_3_4_5_6_7_8_9_10_11_12_13_14_15_".
                  "16_17_18_19_20_21_22_23_24_25_26_27_28_29".
                  "_30_31_32_33_34_35_36_37_38_39_40";
          unset($arr);
          $arr = aport($url);
          for($j = 0; $j < count($arr); $j++)
          {
            if(strpos($arr[$j]['url'], $site) !== false)
            {
              $result .= "Позиция N ".($i*10 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>{$arr[$j][name]}</a><br>";
              $result .= "<a href=$url>Страница Aport</a><br>";
              return $result;
            }
          }
        }
        $result .= "Сайт не найден<br>";
        return $result;
      }
    }
  }
?>