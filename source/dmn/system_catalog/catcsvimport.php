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

  // Если поле separator пусто - используем по
  // умолчанию в качестве разделителя точку с запятой
  if(empty($_REQUEST['separator'])) $_REQUEST['separator'] = ";";

  $csvfile   = new field_file("csvfile",
                              "CSV-файл",
                               true,
                               $_FILES,
                              "../../files/csvfile/");
  $separator = new field_text("separator",
                              "Разделитель",
                               true,
                              $_REQUEST['separator']);
  $id_catalog = new field_hidden_int("id_catalog",
                                      true,
                                     $_REQUEST['id_catalog']);
  try
  {
    // Форма
    $form = new form(array("csvfile"    => $csvfile,
                           "separator"  => $separator,
                           "id_catalog" => $id_catalog), 
                    "Импортировать",
                    "field");
  
    // Обработчик HTML-формы
    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();
      if(empty($error))
      {
        // Читаем содержимое загруженного файла
        $filename = "../../files/csvfile/".$form->fields['csvfile']->get_filename();
        $content = file_get_contents($filename);
        // Удаляем файл
        unlink($filename);
        // Разделитель
        $separator = $form->fields['separator']->value;
        // Если имеются пустые позиции забиваем их прочерком "-"
        // В начале файла
        $content = str_replace("\n".$separator,
                               "\n-".$separator,
                               $content);
        // В середине файла
        $content = str_replace($separator.$separator,
                               $separator."-".$separator,
                               $content);
        // В конце файла
        $content = str_replace($separator."\n",
                               $separator."-\n",
                               $content);
        // Разбиваем файл по строкам, каждую из которых заносим
        // в отдельный элемент временного массива $strtmp
        $strtmp = explode("\n", $content);

        // Разбиваем строку по отдельным словам, используя
        // разделитель $separator
        $i = 0;
        foreach($strtmp as $value)
        {
          // Если строка пуста - выходим из цикла. Пустые строки могут
          // появится, если в конце csv-файла находятся пустые строки.
          if(empty($value)) continue;
          // Разбиваем строку по разделителю
          list($district,   // Район
               $address,    // Адрес
               $floor,      // Этаж
               $floorhouse, // Этажность дома
               $material,   // Материал дома
               $rooms,      // К-во комнат
               $square_o,   // Площадь общая
               $square_j,   // Площадь жилая
               $square_k,   // Площадь комнат
               $su,         // Сан.узел
               $balcony,    // Тип балкона
               $note,       // Замечание
               $pricemeter, // Цена за метр
               $price,      // Цена
               $currency    // Валюта
              ) = explode($separator,$value);
          // Игнорируем строку с заголовками
          if($district == "Район") continue;
          // Увеличиваем значене счётчика
          $i++;
          // Определяем район по первым трем буквам его названия
          switch(substr(strtolower($district), 0, 3))
          {
              case 'кан':
                $district = "kanavinskii";
                break;
              case 'ниж':
                $district = "nizhegorodskii";
                break;
              case 'сов':
                $district = "sovetskii";
                break;
              case 'при':
                $district = "priokskii";
                break;
              case 'мос':
                $district = "moskovskii";
                break;
              case 'авт':
                $district = "avtozavodskii";
                break;
              case 'лен':
                $district = "leninskii";
                break;
              case 'сор':
                $district = "sormovskii";
                break;
          }
          // Материал дома
          switch(substr($material, 0, 3))
          {
              case 'кир':
                $material = "brick";
                break;
              case 'пан':
                $material = "concrete";
                break;
              case 'мон':
                $material = "reconcrete";
                break;
          }
          // Сан.узел 
          switch(substr(strtolower($su), 0, 1))
          {
              case 'с':
                $su = "separate";
                break;
              case 'р':
                $su = "combined";
                break;
          }
          // Лоджия/Балкон
          switch(substr(strtolower($balcony), 0, 1))
          {
              case 'л':
                $balcony = "loggia";
                break;
              case 'б':
                $balcony = "balcony";
                break;
          }
          // Валюта
          $currency = trim($currency);
          // Преобразуем кавычки
          $note     = mysql_escape_string($note);
          $district = mysql_escape_string($district);
          $address  = mysql_escape_string($address);
          $currency = mysql_escape_string($currency);
          // Формируем и выполняем SQL-запрос на добавление позиции
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
          // Удаляем записи из таблицы $tbl_cat_position 
          // принадлежащие данному подкаталогу
          $query = "DELETE FROM $tbl_cat_position 
                    WHERE id_catalog={$form->fields[id_catalog]->value}";
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при удалении
                                     старых позиций");
          }
          // Начало формирования SQL-запроса на вставку данных из
          // csv-файла
          $query = "INSERT INTO $tbl_cat_position 
                    VALUES ".implode(",", $insert_query);
          // Выполняем многострочный оператор INSERT
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "Ошибка при вставке
                                     новых позиций");
          }
        }
        // Осуществляем автоматический переход на страницу администрирования
        // текущего каталога
        header("Location: position.php?id_catalog={$form->fields[id_catalog]->value}");
        exit();
      }
    }

    // Начало страницы
    $title     = 'Импорт позиций из CSV-файла';
    $pageinfo  = '<p class=help>Позиции можно импортировать из Excel-формата,
                  сохранив предварительно импортируемый лист как CSV-файл.</p>';
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
