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

  $query = "SELECT * FROM $tbl_forums 
            WHERE hide = 'show'
            ORDER BY pos";
  $frm = mysql_query($query);
  if(!$frm)
  {
     throw new ExceptionMySQL(mysql_error(), 
                              $query,
                             "������ ��� ������� 
                              ������ ������� ������");
  }
  if(mysql_num_rows($frm))
  {
    // ���� ������ ������ ���
    if(basename($_SERVER['PHP_SELF']) == 'read.php')
    { 
      // ���������� $id_theme � $id_theme_archive ������������ � utils/newpostslist.php
      if($id_theme > $id_theme_archive)
      {
        $action = "index.php";
      }
      else
      {
        $action = "archive.php";
      }
    }
    else
    {
      $action = basename($_SERVER['PHP_SELF']);
    }
  ?>
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class=switchforum>
                <form style="margin: 0px" action=<?= $action; ?> method=get>          
                <nobr><p class=texthelp>������� ������ �����<br>
                <select type=text name='id_forum'>
    <?php
    while($forum = mysql_fetch_array($frm))
    {
      // ���������� ��������� �����
      if($forum['id_forum'] == $id_forum)
      {
         $chk = "selected";
         // ���� ����� ������, ���������� ��� ������� ��������.
         $nameforum = $forum['name'];
         $logo = $forum['logo'];
      }
      else $chk = "";
      echo "<option $chk value=$forum[id_forum]>$forum[name]";
    }
  ?>
  </select>
  <input class=button type=submit value="�������">
  <?php
  }
  ?></nobr>
  </td></tr>
  </table>
  </form>