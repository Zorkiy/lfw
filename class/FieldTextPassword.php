<?php
/**
/* Текстовое поле для ввода пароля
/* @param 
/* @return 
 */
class FieldTextPassword extends FieldText {
   function __construct (  $name,
                           $caption, 
                           $is_required = false, 
                           $value,
                           $maxlength = 255,
                           $size = 41,
                           $parameters = "", 
                           $help = "",
                           $help_url = "") {
      // Вызываем конструктор базового класса FieldText
      parent::__construct( $name,
                           $caption,
                           $is_required, 
                           $value,
                           $maxlength,
                           $size,
                           $parameters, 
                           $help,
                           $help_url);
      // Для пароля нужно переприсвоить значение атрибута type
      $this->type = "password";
   }
}
?>