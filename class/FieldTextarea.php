<?php
/**
/* Краткое описание
/* @param 
/* @return 
 */
class FieldTextarea extends FieldText {
   // Размер текстового поля
   protected $cols;
   // Максимальное кол-во вводимых данных
   protected $rows;
   // Блокировка поля
   protected $disabled;
   // Только для чтения
   protected $readonly;
   // Отсутствие автоперевода строк
   protected $wrap;
   protected $type;

   function __construct (  $name,
                           $caption, 
                           $is_required = false, 
                           $value = "",
                           $cols = 35,
                           $rows = 7,
                           $disabled = false,
                           $readonly = false,
                           $wrap = false,
                           $parameters = "", 
                           $help = "",
                           $help_url = "") {
      parent::__construct( $name, 
                           $caption, 
                           $is_required = false, 
                           $value,
                           $parameters, 
                           $help,
                           $help_url);
      // Инициализация членов класса FieldText
      $this->cols     = $cols;
      $this->rows     = $rows;
      $this->disabled = $disabled;
      $this->readonly = $readonly;
      $this->wrap     = $wrap;
   }

   // Возвращает имя поля и сам тег элемента управления
   function get_html () {
      // Если элементы управления не пусты - учесть их
      if (!empty($this->css_style))
         $style = "style=\"".$this->css_style."\"";
      else $style = "";
      if (!empty($this->css_class))
         $class = "class=\"".$this->css_class."\"";
      else $class = "";

      // Если определены размеры - учесть их
      if (!empty($this->cols))
         $cols = "cols=".$this->cols;
      else $cols = "";
      if (!empty($this->rows))
         $rows = "rows=".$this->rows;
      else $rows = "";

      // Атрибуты текстовой области
      if ($this->disabled) $disabled = "disabled";
      else $disabled = "";
      if ($this->readonly) $readonly = "readonly";
      else $readonly = "";
      if ($this->wrap) $wrap = "wrap";
      else $wrap = "";

      if (is_array($this->value))
         $this->value = implode("\r\n",$this->value);
      if (!get_magic_quotes_gpc())
         $output = str_replace('\r\n',"\r\n",$this->value);
      else $output = $this->value;
      $tag = "<textarea $style $class
         name=\"".$this->name."\"
         $cols $rows $disabled $readonly $wrap $this->caption>".
         htmlspecialchars($output, ENT_QUOTES).
         "</textarea>\n";

      // Если поле обязательно для заполнения
      if ($this->is_required) $this->caption .= "&nbsp;*";

      // Формирование подсказки, если она есть
      $help = "";
      if (!empty($this->help))
         $help .= "<span style='color:blue'>".nl2br($this->help)."</span>";
      if (!empty($help)) $help .= "<br>";
      if (!empty($this->help_url))
         $help .= "<span style='color:blue'>
         <a href=".$this->help_url.">ОНЛНЫЭ</a>
         </span>";

      return array($this->caption, $tag, $help);
   }

   function check () {
      if (!get_magic_quotes_gpc())
         $this->value = mysqli_escape_string(
            $this->db->get_connect(), $this->value);
      if ($this->is_required)
         if (empty($this->value))
            return "Поле \"".$this->caption."\" не заполнено";

      return "";
   }
}
?>