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
    $query = "SELECT * FROM $tbl_cat_position
              WHERE id_position=$_GET[id_position]";
    $pos = mysql_query($query);
    if(!$pos)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������
                               � ������� �������");
    }
    $position = mysql_fetch_array($pos);
    if(empty($_POST))
    {
      // ���� ���������� ��� ���������� ���������� �� ���� ������
      $_REQUEST = $position;
      // ���������� ������ ���� ��� ���
      if($position['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }
  
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
    $id_position = new field_hidden_int("id_position",
                               true,
                               $_REQUEST['id_position']);
  

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
                           "id_catalog" => $id_catalog, 
                           "id_position" => $id_position), 
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
        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";

        // ��������� ������ �� �������������� �������
        $query = "UPDATE $tbl_cat_position
                  SET note = '{$form->fields[note]->value}',
                      district = '{$form->fields[district]->value}',
                      address = '{$form->fields[address]->value}',
                      squareo = '{$form->fields[squareo]->value}',
                      squarej = '{$form->fields[squarej]->value}',
                      squarek = '{$form->fields[squarek]->value}',
                      rooms = '{$form->fields[rooms]->value}',
                      floor = '{$form->fields[floor]->value}',
                      floorhouse = '{$form->fields[floorhouse]->value}',
                      material = '{$form->fields[material]->value}',
                      su = '{$form->fields[su]->value}',
                      balcony = '{$form->fields[balcony]->value}',
                      price = '{$form->fields[price]->value}',
                      pricemeter = '{$form->fields[pricemeter]->value}',
                      currency = '{$form->fields[currency]->value}',
                      putdate = NOW(),
                      hide = '$showhide'
                  WHERE id_catalog = {$form->fields[id_catalog]->value} AND
                        id_position = {$form->fields[id_position]->value}";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��������������
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
    $title     = '�������������� �������';
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