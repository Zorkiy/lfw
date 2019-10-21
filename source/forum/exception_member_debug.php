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
  // Выставляем уровень обработки ошибок (http://www.softtime.ru/info/articlephp.php?id_article=23)
  Error_Reporting(E_ALL & ~E_NOTICE); 

  if(defined("DEBUG"))
  {
    echo "<p class=help>Произошла исключительная 
          ситуация (ExceptionMember) - попытка 
          обращения к несуществующему члену класса.
          {$exc->getMessage()}.</p>";
    echo "<p class=help>Ошибка в файле {$exc->getFile()}
          в строке {$exc->getLine()}.</p>";
    exit();
  }
  else
  {
    echo "<HTML><HEAD>
            <META HTTP-EQUIV='Refresh' CONTENT='0; URL=exception_member.php'>
          </HEAD></HTML>";
    exit();
  }
?>