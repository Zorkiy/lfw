<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) Кузнецов М.В., Симдянов И.В.
  // PHP. Практика создания Web-сайтов
  // IT-студия SoftTime 
  // http://www.softtime.ru   - портал по Web-программированию
  // http://www.softtime.biz  - коммерческие услуги
  // http://www.softtime.mobi - мобильные проекты
  // http://www.softtime.org  - некоммерческие проекты
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  // Устанавливаем соединение с базой данных
  require_once("../../config/config.php");
  // Подлкючаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем классы формы
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
                              "Ошибка при обращении
                               к таблице позиции");
    }
    $position = mysql_fetch_array($pos);
    if(empty($_POST))
    {
      // Берём информацию для оставшихся переменных из базы данных
      $_REQUEST = $position;
      // Определяем скрыто поле или нет
      if($position['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }
  
    $district = new field_select("district",
                                 "Район",
                                 array("kanavinskii" => "Канавинский",
                                       "nizhegorodskii" => "Нижегородский",
                                       "sovetskii" => "Советский",
                                       "priokskii" => "Приокский",
                                       "moskovskii" => "Московский",
                                       "avtozavodskii" => "Автозаводский",
                                       "leninskii" => "Ленинский",
                                       "sormovskii" => "Сормовский"),
                                 $_REQUEST['district']);
    $address        = new field_text("address",
                                     "Адрес",
                                      true,
                                     $_REQUEST['address']);
    $squareo        = new field_text("squareo",
                                     "пл(О)",
                                      true,
                                     $_REQUEST['squareo']);
    $squarej        = new field_text("squarej",
                                     "пл(Ж)",
                                      true,
                                     $_REQUEST['squarej']);
    $squarek        = new field_text("squarek",
                                     "пл(K)",
                                      true,
                                     $_REQUEST['squarek']);
    $rooms    = new field_select("rooms",
                                 "Kол-во комнат",
                                  array("1" => "1",
                                        "2" => "2",
                                        "3" => "3",
                                        "4" => "4",
                                        "5" => "5",
                                        "6" => "6"),
                                  $_REQUEST['rooms']);
    $floor        = new field_text_int("floor",
                                     "Этаж",
                                      true,
                                     $_REQUEST['floor']);
    $floorhouse   = new field_text_int("floorhouse",
                                     "Этажн.дома",
                                      true,
                                     $_REQUEST['floorhouse']);
    $material = new field_select("material",
                                 "Материал дома",
                                  array("brick" => "кирпичный",
                                        "concrete" => "панельный",
                                        "reconcrete" => "монолитный"),
                                  $_REQUEST['material']);
    $su = new field_select("su",
                           "Сан. узел",
                            array("combined" => "раздельный",
                                  "separate" => "совмещённый"),
                            $_REQUEST['su']);
    $balcony = new field_select("balcony",
                                "Сан. узел",
                                array("balcony" => "балкон",
                                      "loggia" => "лоджия"),
                                $_REQUEST['balcony']);
    $price   = new field_text_int("price",
                                  "Цена",
                                   true,
                                   $_REQUEST['price']);
    $pricemeter = new field_text_int("pricemeter",
                                     "Цена м.кв.",
                                      true,
                                      $_REQUEST['pricemeter']);
    $currency = new field_select("currency",
                                 "Валюта",
                                  array("RUR" => "RUR",
                                        "EUR" => "EUR",
                                        "USD" => "USD"),
                                  $_REQUEST['currency']);
    $note = new field_textarea("note",
                               "Примечание",
                                false,
                                $_REQUEST['note']);
    $hide        = new field_checkbox("hide",
                               "Отображать",
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
                     "Редактировать",
                     "field");

    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Скрытая или открытая позиция
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";

        // Формируем запрос на редактирование позиции
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
                                  "Ошибка редактирования
                                   позиции");
        }
        // Осуществляем редирект на главную страницу администрирования
        header("Location: position.php?".
               "id_catalog={$form->fields[id_catalog]->value}&".
               "page={$form->fields[page]->value}");
        exit();
      }
    }
    // Начало страницы
    $title     = 'Редактирование позиции';
    $pageinfo  = '<p class=help></p>';
    // Включаем заголовок страницы
    require_once("../utils/top.php");
  
    echo "<p><a href=# onClick='history.back()'>Назад</a></p>";
    // Выводим сообщения об ошибках если они имеются
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\">$err</span><br>";
      }
    }
    // Выводим HTML-форму 
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

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>