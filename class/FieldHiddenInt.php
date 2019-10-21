<?php
/**
* Скрытое поле с целочисленными значениями hidden
*/
class FieldHiddenInt extends FieldHidden
{
   // Метод, проверяющий корректность переданных данных
   function check()
   {
      if ($this->is_required) {
         // Поле обязательно к заполнению
         if (!preg_match("|^[\d]+$|",$this->value)) {
            return "Скрытое поле должно быть целым числом";
         }
      }
      // Поле не обязательно к заполнению
      if(!preg_match("|^[\d]*$|",$this->value)) {
         return "Скрытое поле должно быть целым числом";
      }

      return "";
   }
}
?>