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
  $skin =  @$_POST['skin'];
  $set = setcookie("skin", $skin, time() + 3600*24*7);
  if($set){
	header("location: {$_SERVER["HTTP_REFERER"]}");
  }else{
  	header("location: {$_SERVER["HTTP_REFERER"]}");
  }
?>