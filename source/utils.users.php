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

  function enter($name, $password)
  {
    // ��������� �������� ������� ����������
    global $tbl_users;
    // ���������, ������������� �� ����� ������
    // � ���� ������������� - ������������ �����������
    $query = "SELECT * FROM $tbl_users
              WHERE name = '$name' AND
                    pass = '$password' AND
                    block = 'unblock'
              LIMIT 1";
    $usr = mysql_query($query);
    if(!$usr)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                               "������ ��������������");
    }
    if(mysql_num_rows($usr))
    {
      $user = mysql_fetch_array($usr);
      // ���� �� ����
      $_SESSION['name'] = $user['name'];
      $_SESSION['id_user_position'] = $user['id_position'];
      // ��������� ���� ���������� ��������� ������������
      $query = "UPDATE $tbl_users
                SET lastvisit = NOW()
                WHERE id_position = $user[id_position]";
      if(!mysql_query($query))
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                 "������ ��������������");
      }
      // ���������� ������� �������� ��������������
      return true;
    }
    // ���������� ������� ��������� ��������������
    else return false;
  }
  function user($id_position)
  {
    // ��������� ��� ������� $tbl_users ����������
    global $tbl_users;
    // ������������� SQL-��������
    $id_position = intval($id_position);
    // ��������� ��������� ������������
    $query = "SELECT * FROM $tbl_users
              WHERE id_position = $id_position AND 
                    block = 'unblock'
              LIMIT 1";
    $usr = mysql_query($query);
    if(!$usr) 
    {
      throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ���������� ���������� ������������");
    }
    if(mysql_num_rows($usr)) return mysql_fetch_array($usr);
    else return 0;
  }
  function remember($name)
  {
    // ��������� ��� ������� $tbl_users ����������
    global $tbl_users;
    // ��������� SQL-������ �� ���������� ���������������� ������
    $query = "SELECT * FROM $tbl_users 
              WHERE name = '$name'";
    $usr = mysql_query($query);
    if(!$usr)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                               "������ ��� �������������� ������");
    }
    // ��������� e-mail ������������
    $user = mysql_fetch_array($usr);

    $thm =  convert_cyr_string("�������������� ������",'w','k'); 
    $msg = "�������������� ������ �� �����\r\n".
           "����� - $user[name] \r\n".
           "������ - $user[pass] \r\n";
    $msg =  convert_cyr_string(stripslashes($msg),'w','k'); 
    $header = "Content-Type: text/plain; charset=KOI8-R\r\n\r\n";
    // ���� �� �������� ����������������� ������
    // ����� ������� ��������� - ���������� ������
    @mail($user['email'], $thm, $msg, $header);
  }
?>