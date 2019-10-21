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

  session_start();
  // ������������� ���������� � ����� ������
  require_once("config/config.php");
  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ���������� ������� ������ ������ � bbCode
  require_once("dmn/utils/utils.print_page.php");
  // ���������� ��������� 
  require_once("utils.title.php");

  try
  {
    // ����������� ������� �����
    $query = "SELECT * FROM $tbl_poll
              WHERE archive = 'active' AND 
      	            hide = 'show'";
    $pol = mysql_query($query);
    if(!$pol)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� 
                               � ���������� �������");
    }
    if(mysql_num_rows($pol)) $poll = mysql_fetch_array($pol);
    // ��������� �����
    if(!empty($_POST))
    {
      // ������� ������ ������ �� ������� $tbl_poll_session
      $query = "DELETE FROM $tbl_poll_session
                WHERE putdate < NOW() - INTERVAL 1 HOUR";
      if(!mysql_query($query))
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ������� �������
                                 ���������");
      }
      // ��������� �� ��������� �� ������� ���������� �����
      $query = "SELECT COUNT(*) FROM $tbl_poll_session
                WHERE session = '".session_id()."'";
      $ses = mysql_query($query);
      if(!$ses)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��������� � �������
                                 ���������");
      }
      if(!mysql_result($ses, 0))
      {
        $query = "INSERT INTO $tbl_poll_session
                  VALUES (NULL, '".session_id()."', NOW())";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��������� � �������
                                   ���������");
        }
        $_POST['id_answer'] = intval($_POST['id_answer']);
        $query = "UPDATE $tbl_poll_answer
                  SET hits = hits + 1 
                  WHERE id_position = $_POST[id_position] AND
                        id_catalog = $poll[id_catalog]";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ �� ����� �����������");
        }
      }
    }
    // ���������� ������� ������
    $pagename = $poll['name'];
    $keywords = $poll['name'];
    require_once ("templates/top.php");

    // ������� ���������� �����������
    echo title($poll['name']);

    // ������������ ����� ���� ��������������� � ������� �����������
    $query = "SELECT SUM(hits) FROM $tbl_poll_answer
              WHERE id_catalog = $poll[id_catalog]";
    $tot = mysql_query($query);
    if(!$tot)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ����������
                               ����������� �����������");
    }
    // ����� ���������� ������� �������
    $total = mysql_result($tot, 0);
    // ������������� ������� �� ����
    if($total == 0) $total = 1;

    // ��������� �������� ������� � ���������� �������,
    // �������� �� ���
    $query = "SELECT * FROM $tbl_poll_answer
              WHERE id_catalog = $poll[id_catalog]
              ORDER BY pos";
    $ans = mysql_query($query);
    if(!$ans)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ����������
                               ����������� �����������");
    }
    if(mysql_num_rows($ans))
    {
      // ������� ��������� ������� � ������������ �����������
      echo "<table width=100% 
                   border=0 
                   cellspacing=1 
                   cellpadding=1>
            <tr class=stable_tr_ttl_clr>
              <td align=center class=stable_txt>
                <b>�����</b>
              </td>
              <td align=center class=stable_txt>
                <b>�������������</b>
              </td>
              <td align=center class=stable_txt>
                <b>%</b>
              </td>
            </tr>";
      $i = 0;
      while($answer = mysql_fetch_array($ans))
      {
        if($i++ % 2) $class = "stable_tr_clr2";
        else $class = "stable_tr_clr1";
        // ������� ���������� �����������
        echo "<tr class=\"$class\">
                <td class=stable_txt>$answer[name]</td>
                <td class=stable_txt align=center>$answer[hits]</td>
                <td class=stable_txt align=center>".sprintf("%01.1f%s", 
                      $answer['hits']/$total*100,'%')."</td>
              </tr>";
      }
      echo "</table>";
      echo "<div class=main_txt>����� ���������� ��������������� ����������: $total</div>";
    }

    //���������� ������ ������
    require_once ("templates/bottom.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>
