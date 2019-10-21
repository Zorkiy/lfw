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

  function pager($page, $total, $pnumber, $page_link, $parameters)
  {
    // Вычисляем число страниц в системе
    $number = (int)($total/$pnumber);
    if((float)($total/$pnumber) - $number != 0) $number++;
    // Проверяем есть ли ссылки слева
    if($page - $page_link > 1)
    {
      echo "<a class=menuinfo href=$_SERVER[PHP_SELF]?page=1{$parameters}> <nobr>[1-$pnumber]</nobr></a>&nbsp;<em class=currentpage><nobr>&nbsp;...&nbsp;</nobr> </em>&nbsp;";
      // Есть
      for($i = $page - $page_link; $i<$page; $i++)
      {
          echo "&nbsp;<a class=menuinfo href=$_SERVER[PHP_SELF]?page=$i{$parameters}> <nobr>[".(($i - 1)*$pnumber + 1)."-".$i*$pnumber."]</nobr></a>&nbsp;";
      }
    }
    else
    {
      // Нет
      for($i = 1; $i<$page; $i++)
      {
          echo "&nbsp;<a class=menuinfo href=$_SERVER[PHP_SELF]?page=$i{$parameters}> <nobr>[".(($i - 1)*$pnumber + 1)."-".$i*$pnumber."]</nobr></a>&nbsp;";
      }
    }
    // Проверяем есть ли ссылки справа
    if($page + $page_link < $number)
    {
      // Есть
      for($i = $page; $i<=$page + $page_link; $i++)
      {
        if($page == $i)
          echo "<em class=currentpage><nobr>&nbsp;[".(($i - 1)*$pnumber + 1)."-".$i*$pnumber."]&nbsp;</nobr> </em>";
        else
          echo "&nbsp;<a class=menuinfo href=$_SERVER[PHP_SELF]?page=$i{$parameters}> <nobr>[".(($i - 1)*$pnumber + 1)."-".$i*$pnumber."]</nobr></a>&nbsp;";
      }
      echo "<em class=currentpage><nobr>&nbsp;...&nbsp;</nobr> </em>&nbsp;<a class=menuinfo href=$_SERVER[PHP_SELF]?page=$number{$parameters}> <nobr>[".(($number - 1)*$pnumber + 1)."-$total]</nobr></a>&nbsp;";
    }
    else
    {
      // Нет
      for($i = $page; $i<=$number; $i++)
      {
        if($number == $i)
        {
          if($page == $i)
            echo "<em class=currentpage><nobr>&nbsp;[".(($i - 1)*$pnumber + 1)."-$total]&nbsp;</nobr></em>";
          else
            echo "&nbsp;<a class=menuinfo href=$_SERVER[PHP_SELF]?page=$i{$parameters}>[".(($i - 1)*$pnumber + 1)."-$total]</a>&nbsp;";
        }
        else
        {
          if($page == $i)
            echo "<em class=currentpage><nobr>&nbsp;[".(($i - 1)*$pnumber + 1)."-".$i*$pnumber."]&nbsp;</nobr> </em>";
          else
            echo "&nbsp;<a class=menuinfo href=$_SERVER[PHP_SELF]?page=$i{$parameters}> <nobr>[".(($i - 1)*$pnumber + 1)."-".$i*$pnumber."]</nobr></a>&nbsp;";
        }
      }
    }
  }
?>