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

  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ������������� ���������� � ����� ������
  require_once("config/config.php");
  // ���������� ������� ���������
  require_once("utils.navigation.php");
  // ���������
  require_once("utils.title.php");

  try
  {
    // ��������� GET-���������, ������������ SQL-��������
    $_GET['id_catalog'] = intval($_GET['id_catalog']);
    $_GET['page'] = intval($_GET['page']);
  
    // ����������� ��������� �������� �������
    $query = "SELECT * FROM $tbl_cat_catalog 
              WHERE hide = 'show' AND 
                    id_catalog = ".$_GET['id_catalog']."
              ORDER BY pos";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������� 
                               ���������� �������� �������");
    }
    if(mysql_num_rows($cat))
    {
      $current = mysql_fetch_array($cat);
    }
    // ���������� ������� ������
    $pagename = "�������";
    $keywords = "�������";
    require_once ("templates/top.php");

    // ��������� ��������
    echo title($pagename);

    if($_GET['id_catalog'] != 0) 
    {
      echo "<div><b>
              <a href=\"catalog.php\" class=\"main_ttl\">�������</a>".
              menu_navigation($_GET['id_catalog'], "", $tbl_cat_catalog).
           "</b></div>";
      echo "<br>";
    }
    // ���������, ��� �� ������������, ���� ���� - �������
    $query = "SELECT * FROM $tbl_cat_catalog 
              WHERE hide = 'show' AND 
                    id_parent = ".$_GET['id_catalog']."
              ORDER BY pos";
    $cat = mysql_query($query);
    if(!$cat)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������� 
                               ���������� �������� �������");
    }
    if(mysql_num_rows($cat))
    {
      echo '<table width="100%" 
                   border="0" 
                   cellspacing="0" 
                   cellpadding="0">';
      $i = 0;
      while($catalog = mysql_fetch_array($cat))
      {
        echo '<tr>
              <td align="right">
                <td width="100%" class="table1_txt">
                  <a href="catalog.php?id_catalog='.$catalog['id_catalog'].'" 
                     class="main_ttl">'.$catalog['name'].'</a></td>
              </tr>';
      }
      echo '</table>';
    }

    if($_GET['id_catalog'] != 0) 
    {
      // ���������� ������ �������� �������
      require_once("catalog_position.php");
    }

    // ���������� ������ ������
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