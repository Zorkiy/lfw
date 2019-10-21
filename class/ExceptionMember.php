<?php
class ExceptionMember extends Exception {
   // Имя несуществующего члена
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
?>
