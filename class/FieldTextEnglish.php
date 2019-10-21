<?php
/**
/* Текстовое поля для английского текста
/* @param 
/* @return 
 */
class FieldTextEnglish extends FieldText {
   /**
   /* Проверка корректности переданных данных
   /* @param 
   /* @return 
   */
   function check () {
      if (!get_magic_quotes_gpc())
         $this->value = mysql_escape_string($this->value);

      if ($this->is_required) $pattern = "|^[a-z]+$|i";
      else $pattern = "|^[a-z]*$|i";

      // Проверить символы в поле value на принадлежность к латинскому алфавиту
      if (!preg_match($pattern, $this->value))
         return "Поле \"{$this->caption}\" должно содержать только символы латинского алфавита";

      return "";
   }
}
?>