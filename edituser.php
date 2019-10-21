<?php
require_once("config/config.php");
$db = new DB();

try {
   // Если это первое обращение к форме
   if (empty($_POST)) {
      $_GET['id_user'] = intval($_GET['id_user']);
   
      $query = "select * from lfw.users
         where id_user = $_GET[id_user]";
      $usr = mysqli_query($db->get_connect(), $query);
      if (!$usr) {
         throw new ExceptionMySQL(mysql_error(
            $db->get_connect()),
            $query,
            "Ошибка извлечения данных пользователя"
         );
      }
      $_REQUEST = mysqli_fetch_array($usr);
      $_REQUEST['pass_again'] = $_REQUEST['pass'];
   }

   // Формирование html-формы

   $pass = new FieldTextPassword(
      "pass",
      "Пароль",
      true,
      $_REQUEST['pass']
   );

   $pass_again = new FieldTextPassword(
      "pass_again",
      "Повтор пароля",
      true,
      $_REQUEST['pass_again']
   );

   $email = new FieldTextEmail(
      "email",
      "E-mail",
      true,
      $_REQUEST['email']
   );

   $description = new FieldTextarea(
      "description",
      "О себе",
      false,
      $_REQUEST['description']
   );

   $id_user = new FieldHiddenInt(
      "id_user",
      true,
      $_REQUEST['id_user']
   );

   $form = new Form(
      array(
         "pass"         => $pass,
         "pass_again"   => $pass_again,
         "email"        => $email,
         "description"  => $description,
         "id_user"      => $id_user
      ),
      "Добавить",
      "field"
   );

   // Обработчик html-формы

   if (!empty($_POST)) {
      $error = $form->check();
      if ($form->fields['pass']->value !=
         $form->fields['pass_again']->value) {
         $error[] = "Пароли не рвны";
      }
      // Проверка существования пользователя в БД
      $query = "select count(*) from lfw.users
         where email = '{$form->fields['email']->value}'";
      $mal = mysqli_query($db->get_connect(), $query);
      if (!$mal) {
         throw new ExceptionMySQL(
            mysqli_error($db->get_connect()),
            $query,
            "Ошибка обновления пользовательских данных"
         );
      }

      if (empty($error)) {
         // Обновление записи пользователя
         $query = "update lfw.users set
            pass = MD5('{$form->fields['pass']->value}'),
            email = '{$form->fields['email']->value}',
            description = '{$form->fields['description']->value}'
            where id_user = {$form->fields['id_user']->value}";
         if (!mysqli_query($db->get_connect(), $query)) {
            throw new ExceptionMySQL(
               mysqli_error($db->get_connect()),
               $query,
               "Ошибка обновления пользовательских данных"
            );
         }
         header("Location: edituser.php?id_user=".$_GET['id_user']);
         exit();
      }
   }
   require_once("utils/top.php");
   if (!empty($error)) {
      foreach ($error as $key) {
         echo "<span style=\"color:red\">$key</span><br>";
      }
   }
   $form->print_form();
}
catch (ExceptionMySQL $exc) { require("exception_mysql.php"); }
catch (ExceptionObject $exc) { require("exception_object.php"); }
catch (ExceptionMember $exc) { require("exception_member.php"); }

require_once("utils/bottom.php");
?>