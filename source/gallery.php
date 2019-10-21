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

  // ���������� ������
  session_start();

  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ���������� ������� ��������� ������
  // ����� �����������
  require_once("dmn/utils/utils.print_page.php");
  // ������������� ���������� � ����� ������
  require_once("config/config.php");
  // ���������
  require_once("utils.title.php");

  // �����������
  function poll($id_position, $user_rating)
  {
    // ��������� �������� ������� ����������
    global $tbl_photo_position;
    // ������������ �����������, ����� ��
    // ����������� �� ���������� ������.
    $_SESSION['user_poll_id'][] = $id_position;
    // ����������� ���������� ���������� ������� �����������
    $query = "UPDATE $tbl_photo_position
              SET pollnumber = pollnumber + 1,
                  pollmark = pollmark + $user_rating
              WHERE id_position = $id_position
              LIMIT 1";
    if(!mysql_query($query))
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ����������� ��
                               ����������");
    }
  }

  try
  {
    if($_POST['user_rating'] > 0 && $_POST['user_rating'] <= 5)
    {
      // ��������� ��������� pollnumber � pollmark,
      // �������������� ����������� ��������������� � 
      // ��������� ������ (������� ����� ������� �� ����������
      // ���������������).
      if(is_array($_SESSION['user_poll_id']))
      {
        if(!in_array($_POST['id_position'], $_SESSION['user_poll_id']))
        { 
          poll($_POST['id_position'], $_POST['user_rating']);
        }
      }
      else poll($_POST['id_position'], $_POST['user_rating']);
    }

    // ������������� SQL-��������
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    // ��������� �������� �������
    $query = "SELECT * FROM $tbl_photo_catalog
              WHERE id_catalog = $_GET[id_catalog]";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������� 
                               �������");
    }
    $catalog = mysql_fetch_array($cat);

    // ���������� �����
    $pagename = "������� - ".$catalog['name'];
    $keywords = "�������";
    require_once("templates/top.php");
    // ������� ��������� ��������
    echo title($pagename);

    // ��������� ��������� �������
    $query = "SELECT * FROM $tbl_photo_settings LIMIT 1";
    $set = mysql_query($query);
    if(!$set)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������� 
                               ���������� �������");
    }
    // ���� ������� ���� �� ���� ������ � �������
    // ��������� ���������� ���������� � ����
    if(mysql_num_rows($set))
    {
      $settings = mysql_fetch_array($set);
      $numphoto = $settings['row'];
    }
    // ���� ������ � ������� $tbl_photo_settigns
    // ���������� ������� �� 3 ���������� � ���
    else $numphoto = 3;

    // ������� ����������
    $query = "SELECT * FROM $tbl_photo_position
              WHERE id_catalog = $_GET[id_catalog] AND
                    hide = 'show'
              ORDER BY pos";
    $pht = mysql_query($query);
    if(!$pht)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ����������
                               �����������");
    }
    if(mysql_num_rows($pht))
    {
      $tr == 0;
      echo "<div class=\"main_txt\">";
      echo "<table width=100% border=0>";
      while($photo = mysql_fetch_array($pht))
      {
        $name = $photo['name'];
        $alt = $photo['alt'];
        // ���������� ������� �����������
        list($width_big, $height_big) = @getimagesize($photo['big']);
        list($width_small, $height_small) = @getimagesize($photo['small']);

        // ��������� ������� ������� ����������
        $rating = "0.0";
        if(!empty($photo['pollnumber']))
        {
          $rating = floor($photo['pollmark']/$photo['pollnumber']);
          if($photo['pollmark'] % $photo['pollnumber'] >= 0.5) $rating += 0.5;
          $rating = sprintf("%0.01f", $rating);
        }
        // ���������� ���������� ����������
        $countwatch = "";
        if(!empty($photo['countwatch'])) $countwatch = " ($photo[countwatch])";

        if ($tr == 0) echo "<tr class=\"main_txt\">";

        echo "<td class='gallery_txt' align='center'>
             <div style='padding-top:10px;'
                 ><img src='dataimg/rating_$rating.gif' 
                   align=center
                   border=0 
                   alt='$rating'
                   style='padding-top:10px;'></div>
              <a href=# 
                 onclick=\"show_img('$photo[id_position]', $width_big, $height_big); return false \"
              ><img src='$photo[small]' 
                    width='$width_small'
                    height='$height_small'
                    alt='$alt' 
                    style=\"border: 1px solid black\" 
                    vspace=3></a>
                    <div class=\"gallery_txt\" align=\"center\">$name $countwatch</div>";

        // �����������
        ?>
         <br><br><table border=0 cellpadding=0 cellspacing=0>
         <form id=addvote name=addvote method="post">
           <tr>
             <td><input type=radio name=user_rating value=1></td>
             <td><input type=radio name=user_rating value=2></td>
             <td><input type=radio name=user_rating value=3 checked></td>
             <td><input type=radio name=user_rating value=4></td>
             <td><input type=radio name=user_rating value=5></td>
             <td rowspan=2 valign=top><input type=submit class=in_button value="Ok" /></td>
           </tr>
           <tr class=main_txt><td align=center>1</td><td align=center>2</td><td align=center>3</td><td align=center>4</td><td align=center>5</td></tr>
           <input type=hidden name=id_position value="<?= $photo['id_position']; ?>">
           </form>
         </table>
        <?php

        echo "</td>";
        if (++$tr == $numphoto)
        {
          echo "</tr>";
          $tr = 0;
        }
      }
      if($tr != 0)
      {
        for($i = $tr; $i < $numphoto; $i++)
        {
          echo "<td align=center>&nbsp;</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
      echo "</div>";
    }

    require_once("templates/bottom.php");
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
<script language='JavaScript1.1' type='text/javascript'>
<!--
  function show_img(id_position,width,height)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a = (screen.height-vidWindowHeight)/5;
    b = (screen.width-vidWindowWidth)/2;
    features = "top=" + a + ",left=" + b + ",width=" + 
               vidWindowWidth + ",height=" + 
               vidWindowHeight + ",toolbar=no,menubar=no," +
               "location=no,directories=no,scrollbars=no," +
               "resizable=no";
    url = "show.php?id_position=" + id_position;
    window.open(url,'',features,true);
  }
//-->
</script>