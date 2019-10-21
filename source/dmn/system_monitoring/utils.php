<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) �������� �.�., �������� �.�.
  // PHP. �������� �������� Web-������
  // IT-������ SoftTime 
  // http://www.softtime.ru   - ������ �� Web-����������������
  // http://www.softtime.biz  - ������������ ������
  // http://www.softtime.mobi - ��������� �������
  // http://www.softtime.org  - �������������� �������
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  function utf8_win($str)
  {
    $win = array("�","�","�","�","�","�","�","�","�","�",
                 "�","�","�","�","�","�","�","�","�","�",
                 "�","�","�","�","�","�","�","�","�","�",
                 "�","�","�","�","�","�","�","�","�","�",
                 "�","�","�","�","�","�","�","�","�","�",
                 "�","�","�","�","�","�","�","�","�","�",
                 "�","�","�","�","�","�"," ");
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
  // ���������� ������ �� ������� ��������� ������� Yandex
  function yandex($url)
  {
    // �������������� ������
    $result = array();
    // ��������� ���������� ��������
    $contents = file_get_contents($url); 
    // ���������� ���������
    $pattern = "|<a[\s]+tabindex=\"[\d]+\"(.*?)href=\"([^\"]+)\"[^>]+>(.*?)</a>|is";
    // ��������� ����� �� ����������� ���������
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 
    // �������� ���������� � �������������� ������
    for($i = 0; $i < count($out[2]); $i++) 
    { 
      $result[$i]['url'] = $out[2][$i]; 
      $result[$i]['name'] = $out[3][$i]; 
    }
    return $result;
  } 
  // ���������� ������ �� ������� ��������� ������� Google
  function google($url)
  { 
    // �������������� ������
    $result = array();
    // ��������� ���������� ��������
    $contents = file_get_contents($url); 
    // ���������� ���������
    $pattern = '|<div class=g><h2 class=r><a href=\"([^\"]+)\"[^>]*>(.+)</a>|isU'; 

    // ��������� ����� �� ����������� ���������
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 

    // �������� ���������� � �������������� ������
    for($i = 0; $i < count($out[1]); $i++) 
    { 
      $result[$i]['url'] = $out[1][$i]; 
      $result[$i]['name'] = $out[2][$i]; 
    }
    return $result;
  }
  // ���������� ������ �� ������� ��������� ������� Rambler
  function rambler($url)
  {
    // �������������� ������
    $result = array();
    // ��������� ���������� ��������
    $contents = file_get_contents($url); 
    // ���������� ���������
    $pattern = "|<li>[^>]+><a(.+?)href=\"([^\"]+)\"[^>]+>(.+?)</a>|is";
    // ��������� ����� �� ����������� ���������
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 
    // �������� ���������� � �������������� ������
    for($i = 0; $i < count($out[2]); $i++) 
    { 
      $result[$i]['url'] = $out[2][$i]; 
      $result[$i]['name'] = $out[3][$i]; 
    }
    return $result;
  }
  // ���������� ������ �� ������� ��������� ������� Aport
  function aport($url)
  {
    // �������������� ������
    $result = array();
    // ��������� ���������� ��������
    $contents = file_get_contents($url); 
    // ���������� ���������
    $pattern = "|<li value[^>]+>[^>]+>[\s]+<a href=\"([^\"]+)\"[^>]+>(.+?)</a>|is";
    // ��������� ����� �� ����������� ���������
    preg_match_all($pattern, $contents, $out, PREG_PATTERN_ORDER); 
    // �������� ���������� � �������������� ������
    for($i = 0; $i < count($out[1]); $i++) 
    { 
      $result[$i]['url'] = $out[1][$i]; 
      $result[$i]['name'] = $out[2][$i]; 
    }
    return $result;
  }
  function search($keyword, $site, $search)
  {
    // ���������� ��������������� �������
    $pnumber = 10;
    // ���������
    $result = "";
    switch($search)
    {
      case 'yandex':
      {
        $result .= "��������� ������� Yandex:<br>";
        for($i = 0; $i < $pnumber; $i++)
        {
          $url = "http://www.yandex.ru/yandsearch?&p=$i&text=".urlencode($keyword);
          unset($arr);
          $arr = yandex($url);
          for($j = 0; $j < count($arr); $j++)
          {
            if(strpos($arr[$j]['url'], $site) !== false)
            {
              $result .= "������� N ".($i*10 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>".utf8_win($arr[$j]['name'])."</a><br>";
              $result .= "<a href=$url>�������� Yandex</a><br>";
              return $result;
            }
          }
        }
        $result .= "���� �� ������<br>";
        return $result;
      }
      case 'google':
      {
        $result .= "��������� ������� Google:<br>";
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
              $result .= "������� N ".($i*10 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>{$arr[$j][name]}</a><br>";
              $result .= "<a href=$url>�������� Google</a><br>";
              return $result;
            }
          }
        }
        $result .= "���� �� ������<br>";
        return $result;
      }
      case 'rambler':
      {
        $result .= "��������� ������� Rambler:<br>";
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
              $result .= "������� N ".($i*15 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>{$arr[$j][name]}</a><br>";
              $result .= "<a href=$url>�������� Rambler</a><br>";
              return $result;
            }
          }
        }
        $result .= "���� �� ������<br>";
        return $result;
      }
      case 'aport':
      {
        $result .= "��������� ������� Aport:<br>";
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
              $result .= "������� N ".($i*10 + $j + 1)."<br>";
              $result .= "<a href={$arr[$j][url]}>{$arr[$j][name]}</a><br>";
              $result .= "<a href=$url>�������� Aport</a><br>";
              return $result;
            }
          }
        }
        $result .= "���� �� ������<br>";
        return $result;
      }
    }
  }
?>