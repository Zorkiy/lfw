<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 
  ///////////////////////////////////////////////////
  // �������� ������������ ������ � ������
  ///////////////////////////////////////////////////
  if(!defined("ADDMESSAGE")) return;

  // �� ��������� �������, ��� ���������� �� ���������������
  $id_author = 0;
  // ���� ������������� �������� �������, "������" �� �����
  // � ���������� $id_author ����������� �������� ����������
  // ����� ������ ������� authors
  if(!empty($pswrd))
  {
    $auth = get_user($author, $pswrd);
    if(!$auth)
    {
      $error[] = "������ �������������, ������ �� 
                     ������������� ������";
    }
    else
    {
      // �� ���� ������������ ���� �� ����� ����� ������������
      setallcookie($auth['name'], $pswrd);
      $id_author = $auth['id_author'];
    }
  }
  else
  {
    if($settings['registration_required'] == 'yes')
    {
      $error[] = "��������� ���� � ��������� ����� ������ 
                  ������������������ ����������, ���������� 
                  �����������������";
    }
    $err = check_user($author);
    if(!empty($err)) $error[] = $err;
  }
?>