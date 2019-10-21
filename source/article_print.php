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

  if(!defined("ARTICLE")) return;
  if(!preg_match("|^[\d]+$|",$_GET['id_position'])) return;
  if(!preg_match("|^[\d]+$|",$_GET['id_catalog'])) return;
  // ��������� ������ ����� �������
  require_once("dmn/utils/utils.print_page.php");

  // ������� ������ ���������
  $query = "SELECT * FROM $tbl_paragraph
            WHERE id_position = $_GET[id_position] AND 
                  id_catalog = $_GET[id_catalog] AND
                  hide = 'show'
            ORDER BY pos";
    
  $par = mysql_query($query);
  if(!$par)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ��� ��������� 
                             � ���������� �������");
  }
  $type_catalog = "";
  if(mysql_num_rows($par)>0)
  {
    while($paragraph = mysql_fetch_array($par))
    {
      // �������� ��� ������������ ���������
      $align = "";
      switch($paragraph['align'])
      {
        case 'left':
          //$type .= " (�����)";
          $align = "left";
          break;
        case 'center':
          //$type .= " (�� ������)";
          $align = "center";
          break;
        case 'right':
          //$type .= " (������)";
          $align = "right";
          break;
      }

      // ����������� �������
      $image_print = "";
      $query = "SELECT * FROM $tbl_paragraph_image
                WHERE id_paragraph = $paragraph[id_paragraph] AND
                      id_position = $_GET[id_position] AND
                      id_catalog = $_GET[id_catalog] AND
                      hide = 'show'";
      $img = mysql_query($query);
      if(!$img) exit("������ ��� ���������� �����������");
      if(mysql_num_rows($img))
      {
        // ��������� �����������
        unset($img_arr);
        while($image = mysql_fetch_array($img))
        {
          // ALT-���
          if(!empty($image['alt'])) $alt = "alt='$image[alt]'";
          else $alt = "";
          // ������ ������ �����������
          $size_small = @getimagesize($image['small']);
          // �������� �����������
          if(!empty($image['name']))
          {
            $name = "<br><br><b>".$image['name']."</b>";
          }
          else $name = "";
          // ������� �����������
          if(empty($image['big']))
          {
            $img_arr[] = "<img $alt src='$image[small]' 
                               width=$size_small[0] 
                               height=$size_small[1]>$name";
          }
          else
          {
            $size = @getimagesize($image['big']);
            $img_arr[] = "<a href=# 
                           onclick=\"show_img('$image[id_image]',".
                           $size[0].",".$size[1]."); return false \">
                          <img $alt src='$image[small]' 
                               border=0 
                               width=$size_small[0] 
                               height=$size_small[1]></a>$name";
          }
        }
        for($i = 0; $i < count($img_arr)%3; $i++) $img_arr[] = "";
        // ������� �����������
        for($i = 0, $k = 0; $i < count($img_arr); $i++, $k++)
        {
          if($k == 0) $image_print .= "<table cellpadding=5><tr valign=top>";
          $image_print .= "<td class=\"main_txt\">".$img_arr[$i]."</td>";
          if($k == 2)
          {
            $k = -1;
            $image_print .= "</tr></table>";
          }
        }
      }

      // �������� ��� ���������
      $class = "rightpanel_txt";
      switch($paragraph['type'])
      {
        case 'text':
          $class = "main_txt";
          echo "<div align=$align class=$class>".
                nl2br(print_page($paragraph['name'])).
                "<br>$image_print</div>";
          break;
        case 'title_h1':
          $class = "main_ttl";
          echo "<h1 align=$align class=$class>".
                  print_page($paragraph['name']).
               "</h1>";
          break;
        case 'title_h2':
          $class = "main_ttl";
          echo "<h2 align=$align class=$class>".
                  print_page($paragraph['name']).
               "</h2>";
          break;
        case 'title_h3':
          $class = "main_ttl";
          echo "<h3 align=$align class=$class>".
                  print_page($paragraph['name']).
               "</h3>";
          break;
        case 'title_h4':
          $class = "main_ttl";
          echo "<h4 align=$align class=$class>".
                  print_page($paragraph['name']).
               "</h4>";
          break;
        case 'title_h5':
          $class = "main_ttl";
          echo "<h5 align=$align class=$class>".
                  print_page($paragraph['name']).
               "</h5>";
          break;
        case 'title_h6':
          $class = "main_ttl";
          echo "<h6 align=$align class=$class>".
                  print_page($paragraph['name']).
               "</h6>";
          break;
        case 'list':
          $arr = explode("\r\n", $paragraph['name']);
          $class = "main_txt";
          if(!empty($arr))
          {
            echo "<div align=$align class=$class><ul>";
            for($i = 0; $i < count($arr); $i++)
            {
              echo "<li>".print_page($arr[$i])."</li>";
            }
            echo "</ul></div><br>";
          }
          break;
      }
    }
  }
?>
<script language='JavaScript1.1' type='text/javascript'>
<!--
  function show_img(id_image,width,height,adm)
  {
    var a;
    var b;
    var url;
    vidWindowWidth=width;
    vidWindowHeight=height;
    a=(screen.height-vidWindowHeight)/5;
    b=(screen.width-vidWindowWidth)/2;
    features = "top=" + a + 
               ",left=" + b + 
               ",width=" + vidWindowWidth + 
               ",height=" + vidWindowHeight + 
               ",toolbar=no," + 
               "menubar=no," +
               "location=no," +
               "directories=no," +
               "scrollbars=no," +
               "resizable=no";
    url="show.php?id_image=" + id_image;
    window.open(url,'',features,true);
  }
//-->
</script>