<?php
/**
/* Краткое описание
/* @param 
/* @return 
 */
class FieldTextInt extends FieldText {
   // Минимальное значение поля
   protected $min_value;
   // Максимальное значение поля
   protected $max_value;

   function __construct (  $name, 
                           $caption, 
                           $is_required = false, 
                           $value = "",
                           $min_value = 0,
                           $max_value = 0,
                           $maxlength = 255,
                           $size = 41,
                           $parameters = "", 
                           $help = "",
                           $help_url = "") {
      parent::__construct( $name,
                           $caption, 
                           $is_required, 
                           $value,
                           $maxlength,
                           $size,
                           $parameters, 
                           $help,
                           $help_url);
      $this->min_value = intval($min_value);
      $this->max_value = intval($max_value);

      // Минимальное значение должно быть больше максимального
      if ($this->min_value > $this->max_value)
         throw Exception("Минимальное значение должно быть больше максимального
            значения. Поле \"".$this->caption."\".");
   }

   // Проверить корректность переданных данных
   function check () {
      $pattern = "|^[-\d]*$|i";
      if ($this->is_required) {
         // Проверить поле value на максимальное и минимальное значение
         if ($this->min_value != $this->max_value) {
            if ($this->value < $this->min_value || 
               $this->value > $this->max_value) {
               return "Поле \"".$this->caption."\" 
                  должно быть больше ".$this->min_value." 
                  и меньше ".$this->max_value."";
            }
         }
         $pattern = "|^[-\d]+$|i";
      }

      // Проверить является ли введенное значение целым числом
      if (!preg_match($pattern, $this->value))
         return "Поле \"".$this->caption."\" должно содержать только целые числа";

      return "";
   }
}
?>