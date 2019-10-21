<?php
////////////////////////////////////////////////////////////
// Класс HTML-формы Form
////////////////////////////////////////////////////////////

class Form {
   // Массив элементов управления
   public $fields;
   // Название кнопки html-формы
   protected $button_name;

   // Класс css ячейки таблицы
   protected $css_td_class;
   // стиль css ячейки таблицы
   protected $css_td_style;
   // Класс css элемента управления
   protected $css_fld_class;
   // Стиль css элемента управления
   protected $css_fld_style;

   public function __construct($flds, 
                     $button_name, 
                     $css_td_class = "", 
                     $css_td_style = "",
                     $css_fld_class = "",
                     $css_fld_style = "") {
      $this->fields        = $flds;
      $this->button_name   = $button_name;

      $this->css_td_class  = $css_td_class;
      $this->css_td_style  = $css_td_style;
      $this->css_fld_class = $css_fld_class;
      $this->css_fld_style = $css_fld_style;

      // Проверяем являются ли элементы массива $flds
      // производными класса Field
      foreach ($flds as $key => $obj) {
         if (!is_subclass_of($obj, "Field")) {
            throw new ExceptionObject($key, 
               "\"$key\" не является производным класса Field");
            }
         }
   }

   // Вывод html-формы в окно браузера
   public function print_form() {
      $enctype = "";
      if (!empty($this->fields)) {
         foreach ($this->fields as $obj) {
            // Назначаем всем элементам управления единый стиль
            if (!empty($this->css_fld_class)) {
               $obj->css_class = $this->css_fld_class;
            }
            if (!empty($this->css_fld_class)) {
               $obj->css_style = $this->css_fld_style;
            }
            // Проверяем нет ли среди элементов управления
            // поля file; если оно есть - включаем строку
            // enctype='multipart/form-data'
            if ($obj->type == "file") {
               $enctype = "enctype='multipart/form-data'";
            }
         }
      }

      // Если элементы управления не пусты - учитываем их
      if (!empty($this->css_td_style)) {
         $style = "style=\"".$this->css_td_style."\"";
      } else $style = "";
      if (!empty($this->css_td_class)) {
         $class = "class=\"".$this->css_td_class."\"";
      } else $class = "";

      // Выводим html-форму
      echo "<form name=form $enctype method=post>";
      echo "<table>";
      if (!empty($this->fields)) {
         foreach ($this->fields as $obj) {
            list($caption, $tag, $help, $alternative) =
               $obj->get_html();
            if (is_array($tag)) $tag = implode("<br>",$tag);
               switch ($obj->type) {
                  case "hidden":
                     echo $tag;
                     break;
                  case "paragraph":
                  case "title":
                     echo "<tr>
                        <td $style $class colspan=2 valign=top>$tag</td>
                        </tr>\n";
                     break;
                  default:
                     echo "<tr><td width=150 
                        $style $class valign=top>$caption:</td>
                        <td $style $class valign=top>$tag</td>
                        </tr>\n";
                        if (!empty($help))
                           echo "<tr><td>&nbsp;</td>
                           <td $style $class valign=top>$help</td>
                           </tr>";
                     break;
               }
            }
      }

      // Выводим кнопку подтверждения
      echo "<tr>
               <td $style $class></td>
               <td $style $class>
                  <input class=button 
                     type=submit 
                     value=\"".htmlspecialchars($this->button_name,
                        ENT_QUOTES)."\">
               </td>
            </tr>\n";
      echo "</table>";
      echo "</form>";
   }

    // Перезагрузка специального метода __toString()
   public function __toString() {
      $this->print_form();
   }

   // Метод, проверяющий корректность ввода данных в форму
   public function check() {
      // Последовательно вызываем метод check для каждого
      // объекта Field, принадлежащего классу
      $arr = array();
      if (!empty($this->fields)) {
         foreach ($this->fields as $obj) {
            $str = $obj->check();
            if(!empty($str)) $arr[] = $str;
         }
      }
      return $arr;
   }
}
?>
