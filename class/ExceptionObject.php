<?php
class ExceptionObject extends Exception {
   // Имя объекта
   protected $key;

   public function __construct ($key, $message) {
      $this->key = $key;

      // Вызываем конструктор базового класса
      parent::__construct($message);
   }

   public function getKey () {
      return $this->key;
   }
}
