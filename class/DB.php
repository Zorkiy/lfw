<?php
class DB {
   public $dbcnx;

   public function __construct()
   {
      $this->dbcnx = mysqli_connect(
         dblocation, dbuser, dbpassword, dbname);
   }

   public function get_connect()
   {
      if (!$this->dbcnx) {
         throw new ExceptionMySQL(mysqli_error($dbcnx),
            "connection",
            "Невозможно установить соединение с MySQL-сервером");
      } else {
         mysqli_set_charset($this->dbcnx, "utf8");
         return $this->dbcnx;
      }
   }
}
?>
