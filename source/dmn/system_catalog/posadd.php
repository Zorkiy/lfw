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

  if(empty($_POST)) $_REQUEST['hide'] = true;
  $_REQUEST['id_catalog'] = intval($_REQUEST['id_catalog']);

  try
  {
    $district = new field_select("district",
                                 "�����",
                                 array("kanavinskii" => "�����������",
                                       "nizhegorodskii" => "�������������",
                                       "sovetskii" => "���������",
                                       "priokskii" => "���������",
                                       "moskovskii" => "����������",
                                       "avtozavodskii" => "�������������",
                                       "leninskii" => "���������",
                                       "sormovskii" => "����������"),
                                 $_REQUEST['district']);
    $address        = new field_text("address",
                                     "�����",
                                      true,
                                     $_REQUEST['address']);
    $squareo        = new field_text("squareo",
                                     "��(�)",
                                      true,
                                     $_REQUEST['squareo']);
    $squarej        = new field_text("squarej",
                                     "��(�)",
                                      true,
                                     $_REQUEST['squarej']);
    $squarek        = new field_text("squarek",
                                     "��(K)",
                                      true,
                                     $_REQUEST['squarek']);
    $rooms    = new field_select("rooms",
                                 "K��-�� ������",
                                  array("1" => "1",
                                        "2" => "2",
                                        "3" => "3",
                                        "4" => "4",
                                        "5" => "5",
                                        "6" => "6"),
                                  $_REQUEST['rooms']);
    $floor        = new field_text_int("floor",
                                     "����",
                                      true,
                                     $_REQUEST['floor']);
    $floorhouse   = new field_text_int("floorhouse",
                                     "�����.����",
                                      true,
                                     $_REQUEST['floorhouse']);
    $material = new field_select("material",
                                 "�������� ����",
                                  array("brick" => "���������",
                                        "concrete" => "���������",
                                        "reconcrete" => "����������"),
                                  $_REQUEST['material']);
    $su = new field_select("su",
                           "���. ����",
                            array("combined" => "����������",
                                  "separate" => "�����������"),
                            $_REQUEST['su']);
    $balcony = new field_select("balcony",
                                "���. ����",
                                array("balcony" => "������",
                                      "loggia" => "������"),
                                $_REQUEST['balcony']);
    $price   = new field_text_int("price",
                                  "����",
                                   true,
                                   $_REQUEST['price']);
    $pricemeter = new field_text_int("pricemeter",
                                     "���� �.��.",
                                      true,
                                      $_REQUEST['pricemeter']);
    $currency = new field_select("currency",
                                 "������",
                                  array("RUR" => "RUR",
                                        "EUR" => "EUR",
                                        "USD" => "USD"),
                                  $_REQUEST['currency']);
    $note = new field_textarea("note",
                               "����������",
                                false,
                                $_REQUEST['note']);
    $hide        = new field_checkbox("hide",
                               "����������",
                               $_REQUEST['hide']);
    $id_catalog = new field_hidden_int("id_catalog",
                               true,
                               $_REQUEST['id_catalog']);
  
    $form = new form(array("district"   => $district, 
                           "address"    => $address,
                           "squareo"    => $squareo,
                           "squarej"    => $squarej,
                           "squarek"    => $squarek,
                           "rooms"      => $rooms,
                           "floor"      => $floor,
                           "floorhouse" => $floorhouse,
                           "material"   => $material,
                           "su"         => $su,
                           "balcony"    => $balcony,
                           "price"      => $price,
                           "pricemeter" => $pricemeter,
                           "currency"   => $currency,
                           "note"       => $note,
                           "hide"       => $hide,
                           "id_catalog" => $id_catalog), 
                     "��������",
                     "field");

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ��������� ������� ������������ �������
        $query = "SELECT MAX(pos) 
                  FROM $tbl_cat_position
                  WHERE id_catalog = {$form->fields[id_catalog]->value}')";
        $pos = mysql_query($query);
        if(!$pos)
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� ���������� 
                                   ������� �������");
        }
        $position = mysql_result($pos, 0) + 1;

        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";

        // ��������� SQL-������ �� ���������� �������
        $query = "INSERT INTO $tbl_cat_position
                  VALUES (NULL,
                          '{$form->fields[note]->value}',
                          '{$form->fields[district]->value}',
                          '{$form->fields[address]->value}',
                          '{$form->fields[squareo]->value}',
                          '{$form->fields[squarej]->value}',
                          '{$form->fields[squarek]->value}',
                          '{$form->fields[rooms]->value}',
                          '{$form->fields[floor]->value}',
                          '{$form->fields[floorhouse]->value}',
                          '{$form->fields[material]->value}',
                          '{$form->fields[su]->value}',
                          '{$form->fields[balcony]->value}',
                          '{$form->fields[price]->value}',
                          '{$form->fields[pricemeter]->value}',
                          '{$form->fields[currency]->value}',
                          '$showhide',
                          '$position',
                          NOW(),
                          '{$form->fields[id_catalog]->value}')";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ���������� 
                                   �������");
        }
        // ������������ �������� �� ������� �������� �����������������
        header("Location: position.php?".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // ������ ��������
    $title     = '���������� �������';
    $pageinfo  = '<p class=help></p>';
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