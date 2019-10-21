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

  try
  {
    $title = $titlepage =  '������� ������';  
    $pageinfo = '<p class=help>�� ������ �������� � ��������� 
                 ����� ����� �������� �������. ��� ����������, 
                 ���� ���������� ��������� ��� ��� ������ ����������. 
                 ����� ����� ������������ ����� �������, ������ 
                 � ��� ��������� ��������. ���������� ������ 
                 ������������� ����� ����, ������� ������ 
                 ������ � ���������� ������.
                 �� ��������� � ������ ������ �������� ����
                 ������: ����� �����</p>';

    // �������� ��������� ��������
    require_once("../utils/top.php");
    // ����
    require_once("forummenu.php");
    ?>
    <a href="partadd.php"
       title="�������� ����� �����">�������� ����� �����</a><br><br>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center width=50>���.</td>
      <td align=center>�������� ������</td>
      <td align=center>������� ��������</td>
      <td align=center>��������</td>
    </tr>
    <?php
      $query = "SELECT * FROM $tbl_forums
                ORDER BY pos";
      $frm = mysql_query($query);
      if(!$frm)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� 
                                 � ������� �������");
      }
      if(mysql_num_rows($frm))
      {
        while($forums = mysql_fetch_array($frm))
        {
          // ���������� ����� ����� ��� ���
          if($forums['hide'] == 'hide')
          {
            $showhide = "<a href=partshow.php?id_forum=$forums[id_forum] title='������� ������ ������� �������������'>����������</a>";
            $colorrow = "class='hiddenrow'";
          }
          else
          {
            $showhide = "<a href=parthide.php?id_forum=$forums[id_forum] title='������� ������ ��������� �������������'>������</a>";
          }
          // ������� ���������� � ������
          echo "<tr $colorrow>
                 <td align=center>$forums[pos]</td>
                 <td><a href=editpartform.php?id_forum=$forums[id_forum]>".htmlspecialchars($forums['name'], ENT_QUOTES)."</a></td>
                 <td>".htmlspecialchars($forums['logo'], ENT_QUOTES)."</td>
                 <td align=center>$showhide<br>
                   <a href=# onClick=\"delete_position('partdel.php?id_forum=$forums[id_forum]','�� ������������� ������ ������� ������?');\" title='������� ������ � ��� ��� ���������'>�������</a><br>
                   <a href=partedit.php?id_forum=$forums[id_forum] title='������ ����������� � ��������, ������� � ��������� ����� �������'>�������������</a><br>
                   <a href=partchn.php?id_forum=$forums[id_forum] title='����������� ��� ��������� ������� � ������ ������ ������'>����������</a></td>
                </tr>";
        }
      }
      echo "</table>";
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