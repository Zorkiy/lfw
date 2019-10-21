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

  // ������� ��� ����������� ���������� ������
  function get_password($pass)
  {
    $settings = get_settings();

    switch($settings['type_crypt'])
    {
      case 'PASSWORD':
        return "PASSWORD('$pass')";
      case 'OLD_PASSWORD':
        return "OLD_PASSWORD('$pass')";
      case 'MD5':
        return "MD5('$pass')";
      case 'PLAIN':
        return "'$pass'";
    }
  }

  // ��������� �� ���������� �� ��� ������������
  // � ����� ������
  function check_user($author)
  {
    // ��������� ���������� � ������� ������ �����������
    global $tbl_authors;
    ///////////////////////////////////////////////////////////
    // ���� �������� ����������� �����
    ///////////////////////////////////////////////////////////
    // �������� �� ���������������� �� ��� ��� ���
    // �������� ��� ��������, ������� ���������� �������������:
    // 1. �������� ���, ��������� ����������� � ��� ������������
    // 2. �������� ��� ������������ ������������ ���, � �������
    //    ���� ��� ��������� ���� �������� �� ���������
    // 3. �������� ��� ������������ ��������� ���, � �������
    //    ���� ��� ��������� ���� ��������� �� ������������
    // ������ ������������ ����
    $rus = array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
    // ������ ��������� ����
    $eng = array("A","a","B","E","e","K","M","H","O","o","P","p","C","c","T","X","x");
    // �������� ������� ����� ����������
    $eng_author = str_replace($rus, $eng, $author); 
    // �������� ��������� ����� ��������
    $rus_author = str_replace($eng, $rus, $author); 
    // ��������� SQL-������
    $query = "SELECT * FROM $tbl_authors 
              WHERE name LIKE '$author' OR
                    name LIKE '$eng_author' OR
                    name LIKE '$rus_author'";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ����������� 
                                ����������");
    }
    if(mysql_num_rows($ath)) return "� ���������, ������ ��� ��� ����������������. ��������� ������.";
    else return "";
  }

  // �������� ������ ������������
  function get_user($author, $pswrd)
  {
    // ��������� ���������� � ������� ������ �����������
    global $tbl_authors;

    // ��������� ������ 
    $query = "SELECT * FROM $tbl_authors 
              WHERE name = '$author' AND 
                  passw = ".get_password($pswrd)." AND
                  statususer != 'wait'";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� 
                                � ������� �������");
    }
    if(mysql_num_rows($ath)) return mysql_fetch_array($ath);
    else return false;
  }

  // ������������� cookie ��� ����� �� �����
  function setallcookie($author, $wrdp)
  {
    $settings = get_settings();
    $tmppos = strrpos($_SERVER['PHP_SELF'],"/") + 1;
    $path = substr($_SERVER['PHP_SELF'], 0, $tmppos);
    setcookie("current_author", $author, time() + 3600*24*$settings['cooktime'],$path);
    setcookie("wrdp", $wrdp, time() + 3600*24*$settings['cooktime'],$path);
    if(isset($_COOKIE['lineforum']))
    {
      setcookie("lineforum", "set_line_forum", time() + 3600*24*$settings['cooktime'], $path);
    }
    if(isset($_COOKIE['lineforumdown']))
    {
      setcookie("lineforumdown", "set_line_forum_down", time() + 3600*24*$settings['cooktime'], $path);
    }
  }

  // ��� ������� ���������� ���� ��� ������ � ������
  function cleanallcookie()
  {
    $tmppos = strrpos($_SERVER['PHP_SELF'],"/") + 1;
    $path = substr($_SERVER['PHP_SELF'], 0, $tmppos);
    setcookie("current_author", "", 0, $path);
    setcookie("wrdp", "", 0, $path);
    setcookie("lineforum", "", 0);
    setcookie("lineforumdown", "", 0);
  }
?>