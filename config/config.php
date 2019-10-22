<?php
define("dblocation", "localhost");
define("dbname", "lfw");
define("dbuser", "root");
define("dbpassword", "12");

require_once("class/DB.php");
require_once("class/ExceptionMember.php");
require_once("class/ExceptionObject.php");
require_once("class/ExceptionMysql.php");
require_once("class/Field.php");
require_once("class/FieldText.php");
require_once("class/Form.php");
require_once("class/FieldTextPassword.php");
require_once("class/FieldTextEnglish.php");
require_once("class/FieldTextInt.php");
require_once("class/FieldTextEmail.php");
require_once("class/FieldTextarea.php");
require_once("class/FieldHidden.php");
require_once("class/FieldHiddenInt.php");
require_once("class/FieldCheckBox.php");
require_once("class/FieldSelect.php");

/**
* Краткое описание
* @param 
* @return 
*/
function fileRtrim($fileFrom, $fileTo)
{
   $f1 = fopen($fileFrom, "r");
   $f2 = fopen($fileTo, "a");

   while (!feof($f1)) {
      $buffer = fgets($f1, 4096);
      fwrite($f2, rtrim($buffer, "\r\n"));
   }

   fclose($f1);
   fclose($f2);
}

function p($arr) {
   echo "<pre style='color: blue; background-color: Gainsboro'>\n";
   print_r($arr);
   echo "</pre>\n";
}
function pexit($arr) {
   echo "<pre style='color: blue; background-color: Gainsboro'>\n";
   print_r($arr);
   echo "</pre>\n";
   exit();
}
?>