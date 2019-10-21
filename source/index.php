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
  // ������������� ���������� � ����� ������
  require_once("config/config.php");
  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ���������
  require_once("utils.title.php");

  // ���������� �������� ��� ������
  define("ARTICLE", 1);

  try
  {
    // ���� �� ������� �������� id_position - ������� ������ ������
    if(empty($_GET['id_position']))
    {
      // ��������� GET-���������, ������������ SQL-��������
      $_GET['page']       = intval($_GET['page']);
      $_GET['id_catalog'] = intval($_GET['id_catalog']);
  
      if(empty($_GET['id_catalog']))
      {
        // ����������� ��������� �������� �������
        $query = "SELECT * FROM $tbl_catalog 
                  WHERE id_catalog = $_GET[id_catalog]";
        $cat = mysql_query($query);
        if(!$cat)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� 
                                   ���������� �������� �������");
        }
        $catalog = mysql_fetch_array($cat);
      }
  
      //���������� ������� ������
      if(empty($catalog['name'])) $pagename = $catalog['name'];
      else $pagename = "������";
      if(empty($catalog['keywords'])) $keywords = $catalog['keywords'];
      else $pagename = "�������� �����";
  
      // ����������� ���������� �������� �������
      $query = "SELECT * FROM $tbl_catalog
                WHERE hide = 'show' AND id_parent = $_GET[id_catalog]
                ORDER BY pos";
      $sub = mysql_query($query);
      if (!$sub)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� � 
                                 ����� ������");
      }
      if(mysql_num_rows($sub))
      {
        // ������� ������
        require_once ("templates/top.php");
        // ��������
        echo title($pagename);
        echo "<div class=\"main_txt\">";
        while($subcatalog = mysql_fetch_array($sub))
        {
          echo "<a href=\"".$_SERVER['PHP_SELF']."?id_catalog=".$subcatalog['id_catalog']."\" 
                       class=\"menu_lnk\"><h3>".
                       htmlspecialchars($subcatalog['name'])."</a></h3>";
        }
        echo "</div>";
      }
  
      // ����������� ������ �������� �������
      $query = "SELECT * FROM $tbl_position
                WHERE hide = 'show' AND id_catalog = ".$_GET['id_catalog']."
                ORDER BY pos";
      $pos = mysql_query($query);
      if (!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� � 
                                 ����� ������");
      }
      if(mysql_num_rows($pos) > 0)
      {
        // ������� ���� � ����������� ���
        if(mysql_num_rows($pos) == 1 && !mysql_num_rows($sub))
        {
          // �������� ��������� ������� ������
          $position = mysql_fetch_array($pos);
          // ���� ������ �� ����� ���� �������� ������� - ������������ ��������
          if($position['url'] != 'article')
          {
            echo "<HTML><HEAD>
                  <META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'>
                  </HEAD></HTML>";
            exit();
          }
          // ������ ���� � ��� ����������� - ������� ���������� ������
          $_GET['id_position'] = $position['id_position'];
          // �������� � �������� �����
          $pagename = $position['name'];
          if(empty($pagename)) $pagename = "�����";
          $_GET['id_catalog'] = $position['id_catalog'];
          $keywords = $position['keywords'];
          // ������� ������
          require_once ("templates/top.php");
          // ��������
          echo title($pagename);
          require_once("article_print.php");
        }
        // ������ ��������� ��� ������� ����� ����������
        else
        {
          echo "<div class=\"main_txt\">";
          while($position = mysql_fetch_array($pos))
          {
            if($position['url'] != 'article')
            {
              echo "<a href=\"".htmlspecialchars($position['url'])."\" 
                        class=\"main_txt_lnk\">
                     ".htmlspecialchars($position['name'])."</a><br>";
            }
            else
            {
              echo "<a href=\"$_SERVER[PHP_SELF]?id_catalog=$_GET[id_catalog]&".
                   "id_position=$position[id_position]\" 
                     class=\"main_txt_lnk\">".htmlspecialchars($position['name'])."</a><br>";
            }
          }
          echo "</div>";
        }
      }
    }
    else
    {
      // ��������� GET-���������, ������������ SQL-��������
      $_GET['id_position'] = intval($_GET['id_position']);
      // �������� ��������� ������� ������
      $query = "SELECT * FROM $tbl_position
                WHERE hide = 'show' AND 
                      id_position = $_GET[id_position]";
      $pos = mysql_query($query);
      if (!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� � 
                                 ����� ������");
      }
      if(mysql_num_rows($pos))
      {
        $position = mysql_fetch_array($pos);
        // ���� ������ �� ����� ���� �������� ������� - ������������ ��������
        if($position['url'] != 'article')
        {
          echo "<HTML><HEAD>
                <META HTTP-EQUIV='Refresh' CONTENT='0; URL=$position[url]'>
                </HEAD></HTML>";
          exit();
        }
        //���������� ������� ������
        $pagename = $position['name'];
        if(empty($pagename)) $pagename = "�����";
        $_GET['id_catalog'] = $position['id_catalog'];
        $keywords = $position['keywords'];
        require_once ("templates/top.php");
  
        // ��������
        echo title($pagename);
        // ������� ������
        require_once("article_print.php");
      }
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