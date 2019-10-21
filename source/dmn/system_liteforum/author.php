<?php
  ////////////////////////////////////////////////////////////
  // 2003-2008 (C) �������� �.�., �������� �.�.
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

  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ���������� SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ������������ ���������
  require_once("../utils/utils.pager.php");
  // ���������� SQL-�������
  require_once("utils.query_result.php");
  // ���������� ������� ��� ������ �� ��������
  require_once("../../utils/utils.time.php");


  try
  {
    $title = '������������� ������';  
    $pageinfo = '<p class=help>���������� �� ������</p>';

    // �������� ��������� ��������
    require_once("../utils/top.php");
    // ����
    require_once("forummenu.php");

    // ��������� ���������� �� ������ �������
    $id_author = intval($_GET['id_author']);
    $id_forum = intval($_GET['id_forum']);

    // ��������� ���������� � ����������
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_author";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ������ ���������� � ������������");
    }
    if(mysql_num_rows($ath))
    {
      $author = mysql_fetch_array($ath);
      echo '<a href="javascript: history.back()">��������� �����</a><br><br>';
      echo '<table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">';
      echo "<tr class='header'>
              <td align=center>��������</td>
              <td align=center>��������</td>
            </tr>";
      echo "<tr><td>���</td><td>".htmlspecialchars($author['name'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>E-mail</td><td>".htmlspecialchars($author['email'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>URL</td><td>".htmlspecialchars($author['url'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>ICQ</td><td>".htmlspecialchars($author['icq'], ENT_QUOTES)."&nbsp;</td></tr>";
      echo "<tr><td>� ����</td><td>".nl2br(htmlspecialchars($author['icq'], ENT_QUOTES))."&nbsp;</td></tr>";
      echo "<tr><td>���������� �����</td><td>".nl2br(htmlspecialchars($author['id_author'], ENT_QUOTES))."&nbsp;</td></tr>";
      echo "<tr><td>���������� ���������</td><td>".nl2br(htmlspecialchars($author['themes'], ENT_QUOTES))."&nbsp;</td></tr>";
      echo "<tr><td>��������� ���������</td><td>".convertdate($author['time'])."&nbsp;</td></tr>";
      if(!empty($author['photo']) && $author['photo'] != "-" && is_file($author['photo']))
      {
        // ���� ���� �� ������� ����� ������� ���
        if(filesize($author['photo']) && $author['photo'] != "-" && is_file("../../forum/".$author['photo'])) 
        {
          echo "<tr><td>����</td><td><a href=../../forum/$author[photo]>�������</a></td></tr>";
        }
      }
      echo "<tr><td>&nbsp;</td><td><a href=authorthmes.php?id_author=$author[id_author]&id_forum=1>����� �����</a></td></tr>";
      echo "<tr><td>&nbsp;</td><td><a href=authorthmes.php?id_author=$author[id_author]&id_forum=1&arch=archiv>�����</a></td></tr>";
      echo "</table>";
    }
    // ������� ���������� ��������
    require_once("../utils/bottom.php");
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }
?>