<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  // ���������� �.�. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  $skin =  @$_POST['skin'];
  $set = setcookie("skin", $skin, time() + 3600*24*7);
  if($set){
	header("location: {$_SERVER["HTTP_REFERER"]}");
  }else{
  	header("location: {$_SERVER["HTTP_REFERER"]}");
  }
?>