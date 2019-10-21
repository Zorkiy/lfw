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
  // ���������� SQL-�������
  require_once("utils.query_result.php");

  try
  {
    $title = '���������� ������';  
    $pageinfo = '<p class=help>����� ������� �������������� ����������
                 �� ������</p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
    // ����
    require_once("forummenu.php");

    // ���������� ������������������ �����������
    $query = "SELECT COUNT(*) FROM $tbl_authors";
    $ath = mysql_query($query);
    if(!$ath)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� � 
                               ������� �����������");
    }
    $number_author = mysql_result($ath, 0);
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center width=50>&nbsp;</td>
      <td align=center>���������� ������������������ �������</td>
    </tr>
    <tr>
      <td align=center class="header">&nbsp;</td>
      <td align=center><?php echo $number_author; ?></td>
    </tr>
    </table><br><br>
    <?php
      // ���������� ���������, ������� � �������� ��� � ������
      // � ����� ������
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE hide = 'hide'";
      $theme_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE hide = 'show'";
      $theme_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_themes
                WHERE hide = 'lock'";
      $theme_lock = query_result($query);

      // ���������� ���������, ������� � �������� ��� � ������
      // � �������� ������
      $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                WHERE hide = 'hide'";
      $theme_archive_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                WHERE hide = 'show'";
      $theme_archive_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                WHERE hide = 'lock'";
      $theme_archive_lock = query_result($query);
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center widht=300>&nbsp;</td>
      <td align=center>���������</td>
      <td align=center>�������</td>
      <td align=center>��������</td>
    </tr>
    <tr>
      <td align=center class="header">���� ������ ������</td>
      <td align=center><?php echo $theme_show; ?></td>
      <td align=center><?php echo $theme_hide; ?></td>
      <td align=center><?php echo $theme_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">���� ��������� ������</td>
      <td align=center><?php echo $theme_archive_show; ?></td>
      <td align=center><?php echo $theme_archive_hide; ?></td>
      <td align=center><?php echo $theme_archive_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">�����</td>
      <td align=center><?php echo ($theme_show + $theme_archive_show); ?></td>
      <td align=center><?php echo ($theme_hide + $theme_archive_hide); ?></td>
      <td align=center><?php echo ($theme_lock + $theme_archive_lock); ?></td>
    </tr>
    </table><br><br>
    <?php
      $query = "SELECT COUNT(*) FROM $tbl_posts
                WHERE hide = 'hide'";
      $post_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_posts
                WHERE hide = 'show'";
      $post_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_posts
                WHERE hide = 'lock'";
      $post_lock = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                WHERE hide = 'hide'";
      $post_archive_hide = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                WHERE hide = 'show'";
      $post_archive_show = query_result($query);
      $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                WHERE hide = 'lock'";
      $post_archive_lock = query_result($query);
    ?>
    <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align=center widht=300>&nbsp;</td>
      <td align=center>���������</td>
      <td align=center>�������</td>
      <td align=center>��������</td>
    </tr>
    <tr>
      <td align=center class="header">��������� ������ ������</td>
      <td align=center><?php echo $post_show; ?></td>
      <td align=center><?php echo $post_hide; ?></td>
      <td align=center><?php echo $post_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">��������� ��������� ������</td>
      <td align=center><?php echo $post_archive_show; ?></td>
      <td align=center><?php echo $post_archive_hide; ?></td>
      <td align=center><?php echo $post_archive_lock; ?></td>
    </tr>
    <tr>
      <td align=center class="header">�����</td>
      <td align=center><?php echo ($post_show + $post_archive_show); ?></td>
      <td align=center><?php echo ($post_hide + $post_archive_hide); ?></td>
      <td align=center><?php echo ($post_lock + $post_archive_lock); ?></td>
    </tr>
    </table><br><br>

    <?php
      // ��������� ���������� ���������� �� ���������� ������
      $query = "SELECT UNIX_TIMESTAMP(MIN(`time`)) AS min_putdate,
                       UNIX_TIMESTAMP(MAX(`time`)) AS max_putdate
                FROM $tbl_posts";
      $dat = mysql_query($query);
      if(!$dat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ����������
                                 ��������� � �������� ���");
      }
      if(mysql_num_rows($dat))
      {
        ?>
          <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr class="header">
            <td align=center width=100>����� �����</td>
            <td align=center>����</td>
            <td align=center>�����</td>
          </tr>
        <?php
        $date = mysql_fetch_array($dat);
        $min = $date['min_putdate'];
        $max = $date['max_putdate'];
        for($i = date("Ym",$min); $i <= date("Ym",$max); $i++)
        {
          if(substr($i,4,2) > 12) $i = (substr($i,0,4) + 1)."01";
          $month[] = substr($i,0,4)."-".substr($i,4,2);
        }

        for($i = count($month) - 1; $i >=0; $i--)
        {
          list($year, $mon) = explode("-", $month[$i]);

          $query = "SELECT COUNT(*) FROM $tbl_posts
                    WHERE YEAR(`time`) = $year AND 
                    MONTH(time) = $mon";
          $posts = query_result($query);
          $query = "SELECT COUNT(*) FROM $tbl_themes
                    WHERE YEAR(`time`) = $year AND 
                    MONTH(time) = $mon";
          $thmemes = query_result($query);
          ?>
          <tr>
            <td align=center class="header"><?php echo $month[$i]; ?></td>
            <td align=center><?php echo $thmemes; ?></td>
            <td align=center><?php echo $posts; ?></td>
          </tr>
          <?php
        }
        echo "</table><br><br>";
      }

      // ��������� ���������� ���������� �� �������� ������
      $query = "SELECT UNIX_TIMESTAMP(MIN(`time`)) AS min_putdate,
                       UNIX_TIMESTAMP(MAX(`time`)) AS max_putdate
                FROM $tbl_archive_posts";
      $dat = mysql_query($query);
      if(!$dat)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ����������
                                 ��������� � �������� ���");
      }
      if(mysql_num_rows($dat))
      {
        ?>
        <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="header">
          <td align=center width=100>�������� �����</td>
          <td align=center>����</td>
          <td align=center>�����</td>
        </tr>
        <?php
        $date = mysql_fetch_array($dat);
        $min = $date['min_putdate'];
        $max = $date['max_putdate'];
        unset($month);
        for($i = date("Ym",$min); $i <= date("Ym",$max); $i++)
        {
          if(substr($i,4,2) > 12) $i = (substr($i,0,4) + 1)."01";
          $month[] = substr($i,0,4)."-".substr($i,4,2);
        }

        for($i = count($month) - 1; $i >=0; $i--)
        {
          list($year, $mon) = explode("-", $month[$i]);

          $query = "SELECT COUNT(*) FROM $tbl_archive_posts
                    WHERE YEAR(time) = $year AND 
                    MONTH(time) = $mon";
          $posts = query_result($query);
          $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                    WHERE YEAR(time) = $year AND 
                    MONTH(time) = $mon";
          $thmemes = query_result($query);
          ?>
          <tr>
            <td align=center class="header"><?php echo $month[$i]; ?></td>
            <td align=center><?php echo $thmemes; ?></td>
            <td align=center><?php echo $posts; ?></td>
          </tr>
          <?php
        }
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