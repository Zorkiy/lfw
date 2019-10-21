<?php
class ExceptionMysql extends Exception {
   // Сообщение об ошибке
   protected $mysql_error;
   // SQL-запрос
   protected $sql_query;

   public function __construct ($mysql_error, $sql_query, $message) {
      $this->mysql_error = $mysql_error;
      $this->sql_query = $sql_query;

      // Вызываем конструктор базового класса
      parent::__construct($message);
   }

   public function getMySQLError () {
      return $this->mysql_error;
   }

   public function getMySQLQuery () {
      return $this->sql_query;
   }
}
