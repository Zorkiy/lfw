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

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� ������ �����
  require_once("../../config/class.config.dmn.php");

  // ���� ���� separator ����� - ���������� ��
  // ��������� � �������� ����������� ����� � �������
  if(empty($_REQUEST['separator'])) $_REQUEST['separator'] = ";";

  $csvfile   = new field_file("csvfile",
                              "CSV-����",
                               true,
                               $_FILES,
                              "../../files/csvfile/");
  $separator = new field_text("separator",
                              "�����������",
                               true,
                              $_REQUEST['separator']);
  $id_catalog = new field_hidden_int("id_catalog",
                                      true,
                                     $_REQUEST['id_catalog']);
  try
  {
    // �����
    $form = new form(array("csvfile"    => $csvfile,
                           "separator"  => $separator,
                           "id_catalog" => $id_catalog), 
                    "�������������",
                    "field");
  
    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ������ ���������� ������������ �����
        $filename = "../../files/csvfile/".$form->fields['csvfile']->get_filename();
        $content = file_get_contents($filename);
        // ������� ����
        unlink($filename);
        // �����������
        $separator = $form->fields['separator']->value;
        // ���� ������� ������ ������� �������� �� ��������� "-"
        // � ������ �����
        $content = str_replace("\n".$separator,
                               "\n-".$separator,
                               $content);
        // � �������� �����
        $content = str_replace($separator.$separator,
                               $separator."-".$separator,
                               $content);
        // � ����� �����
        $content = str_replace($separator."\n",
                               $separator."-\n",
                               $content);
        // ��������� ���� �� �������, ������ �� ������� �������
        // � ��������� ������� ���������� ������� $strtmp
        $strtmp = explode("\n", $content);

        // ��������� ������ �� ��������� ������, ���������
        // ����������� $separator
        $i = 0;
        foreach($strtmp as $value)
        {
          // ���� ������ ����� - ������� �� �����. ������ ������ �����
          // ��������, ���� � ����� csv-����� ��������� ������ ������.
          if(empty($value)) continue;
          // ��������� ������ �� �����������
          list($district,   // �����
               $address,    // �����
               $floor,      // ����
               $floorhouse, // ��������� ����
               $material,   // �������� ����
               $rooms,      // �-�� ������
               $square_o,   // ������� �����
               $square_j,   // ������� �����
               $square_k,   // ������� ������
               $su,         // ���.����
               $balcony,    // ��� �������
               $note,       // ���������
               $pricemeter, // ���� �� ����
               $price,      // ����
               $currency    // ������
              ) = explode($separator,$value);
          // ���������� ������ � �����������
          if($district == "�����") continue;
          // ����������� ������� ��������
          $i++;
          // ���������� ����� �� ������ ���� ������ ��� ��������
          switch(substr(strtolower($district), 0, 3))
          {
              case '���':
                $district = "kanavinskii";
                break;
              case '���':
                $district = "nizhegorodskii";
                break;
              case '���':
                $district = "sovetskii";
                break;
              case '���':
                $district = "priokskii";
                break;
              case '���':
                $district = "moskovskii";
                break;
              case '���':
                $district = "avtozavodskii";
                break;
              case '���':
                $district = "leninskii";
                break;
              case '���':
                $district = "sormovskii";
                break;
          }
          // �������� ����
          switch(substr($material, 0, 3))
          {
              case '���':
                $material = "brick";
                break;
              case '���':
                $material = "concrete";
                break;
              case '���':
                $material = "reconcrete";
                break;
          }
          // ���.���� 
          switch(substr(strtolower($su), 0, 1))
          {
              case '�':
                $su = "separate";
                break;
              case '�':
                $su = "combined";
                break;
          }
          // ������/������
          switch(substr(strtolower($balcony), 0, 1))
          {
              case '�':
                $balcony = "loggia";
                break;
              case '�':
                $balcony = "balcony";
                break;
          }
          // ������
          $currency = trim($currency);
          // ����������� �������
          $note     = mysql_escape_string($note);
          $district = mysql_escape_string($district);
          $address  = mysql_escape_string($address);
          $currency = mysql_escape_string($currency);
          // ��������� � ��������� SQL-������ �� ���������� �������
          $insert_query[] = "(NULL,
                            '$note',
                            '$district',
                            '$address',
                            $square_o,
                            $square_j,
                            $square_k,
                            $rooms,
                            $floor,
                            $floorhouse,
                            '$material',
                            '$su',
                            '$balcony',
                            $price,
                            $pricemeter,
                            '$currency',
                            'show',
                            $i,
                            NOW(),
                            {$form->fields[id_catalog]->value})";
        }
        if(is_array($insert_query))
        {
          // ������� ������ �� ������� $tbl_cat_position 
          // ������������� ������� �����������
          $query = "DELETE FROM $tbl_cat_position 
                    WHERE id_catalog={$form->fields[id_catalog]->value}";
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� ��������
                                     ������ �������");
          }
          // ������ ������������ SQL-������� �� ������� ������ ��
          // csv-�����
          $query = "INSERT INTO $tbl_cat_position 
                    VALUES ".implode(",", $insert_query);
          // ��������� ������������� �������� INSERT
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� �������
                                     ����� �������");
          }
        }
        // ������������ �������������� ������� �� �������� �����������������
        // �������� ��������
        header("Location: position.php?id_catalog={$form->fields[id_catalog]->value}");
        exit();
      }
    }

    // ������ ��������
    $title     = '������ ������� �� CSV-�����';
    $pageinfo  = '<p class=help>������� ����� ������������� �� Excel-�������,
                  �������� �������������� ������������� ���� ��� CSV-����.</p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
    
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� ������� ���� ��� �������
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\">$err</span><br>";
      }
    }
    // ������� HTML-����� 
    $form->print_form();
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

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>
