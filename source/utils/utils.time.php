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

  function get_last_time($current_author, $id_forum)
  {
    // ��������� ���������� � ������� ������ �����������
    global $tbl_last_time, $tbl_authors;

    // ������������� SQL-��������
    $id_forum = intval($id_forum);

    // ���� ����� ���������� ��������� �������, 
    // �� ���������, ��� ���������������� 
    // ������������� - ������� ����� ���������
    // �� ��������� ��� ����
    $forum_lasttime = date("Y-m-d H:i:s",time()-3600*2);
    // ���� ������������ ����������� - ��������� 
    // ���� ���������� ��������� �� �������� �������
    if(!empty($current_author) && trim($current_author) != '����������')
    {
      $query = "SELECT $tbl_last_time.last_time$id_forum AS last_time,
                       UNIX_TIMESTAMP($tbl_last_time.now$id_forum) AS now_time
                FROM $tbl_last_time, $tbl_authors
                WHERE $tbl_authors.name='$current_author' AND
                      $tbl_authors.id_author = $tbl_last_time.id_author";
      $ath = mysql_query($query);
      if(!$ath)
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� ������� 
                                  ��� ������");
      }
      if(mysql_num_rows($ath))
      {
        $lsttime = mysql_fetch_array($ath);
        $forum_lasttime = $lsttime['last_time'];
        $forum_nowtime = $lsttime['now_time'];
        // ���� � ������� ���������� ��������� ������ ������ 20 �����
        if((time() - $forum_nowtime)/60>20)
        {
          // ��������� ����� ����� �����
          $forum_lasttime = date("Y-m-d H:i:s",$forum_nowtime);
        }
      }
    }

    return $forum_lasttime;
  }
  // ��� ������� ���������� ���������� ������� ���������� ���������
  // ������������ � ������������� ���� ��� ����������� �������� ��
  // ��������� �����. ��� ������ ��������� �������� ������ ����������
  // ���������� ������� ���������� ��������� �������� ������, ��� ���� 
  // ������� ����� ������������ � ���������� ��������� - ���� ��� ������
  // 20 �����, ������ last_time ����������� ��� ������ ��������, � � ����
  // ������� ����� �����. ��� ��������.
  // $author - ��� ������������
  // $enter - ���� true - �������������� ���������� �����
  function settime($author, $enter, $id_forum)
  {
    // ��������� ���������� � ������� ������ �����������
    global $tbl_last_time, $tbl_authors;

    // ������������� SQL-��������
    if(empty($id_forum)) $id_forum = 1;
    $id_forum = intval($id_forum);
    
    $query = "SELECT UNIX_TIMESTAMP($tbl_last_time.now$id_forum) AS now_time 
              FROM $tbl_authors, $tbl_last_time 
              WHERE $tbl_authors.name='$author' AND 
                    $tbl_authors.id_author = $tbl_last_time.id_author";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ��������� � 
                                ������� ������� (settime)");
    }
    if(mysql_num_rows($ath))
    {
      $authr = mysql_fetch_array($ath);
      $temptime = (int)$authr['now_time'];

      // ���� � ������� ���������� ��������� ������ ������ 20 �����  
      if((time() - $temptime)/60>20 || $enter)
      {
        // ������������� ����� �����
        $query = "UPDATE $tbl_authors, $tbl_last_time 
                  SET $tbl_last_time.last_time$id_forum = '".date("Y-m-d H:i:s",$temptime)."'
                  WHERE $tbl_authors.name='$author' AND 
                        $tbl_authors.id_author = $tbl_last_time.id_author";
        if(!mysql_query($query))
        {
           throw new ExceptionMySQL(mysql_error(), 
                                    $query,
                                   "������ ��� ���������� �������
                                    ���������� ��������� (settime)");
        }
      }
      // � � ����� ������ ��������� 
      // ����� ���������� ��������� ����������
      $query = "UPDATE $tbl_last_time, $tbl_authors 
                SET $tbl_last_time.now$id_forum = NOW() 
                WHERE $tbl_authors.name='$author' AND 
                      $tbl_authors.id_author = $tbl_last_time.id_author";
      if(!mysql_query($query))
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� ���������� �������
                                  ���������� ��������� (settime)");
      }
      $query = "UPDATE $tbl_authors 
                SET `time` = NOW() 
                WHERE name = '$author'";
      if(!mysql_query($query))
      {
         throw new ExceptionMySQL(mysql_error(), 
                                  $query,
                                 "������ ��� ���������� �������
                                  ���������� ��������� (settime)");
      }
    }
  }
  // $date - ���� � ��������� ������� (���, �����, ����, ����, ������, ������)
  // $type - ���������� � ����� ������� ����� ������� ����
  // $type = 0 ������: ����.�����.��� � ����:������
  function convertdate($date)
  {
    return strftime("%d.%m.%Y � %H:%M", strtotime($date));
  }
?>