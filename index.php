<?php
require_once("config/config.php");

$db = new DB();

// Формирование html-формы
try {
   @$name = new FieldText(
      "name",
      "Имя",
      true,
      $_POST['name']
   );

   @$pass = new FieldTextPassword(
      "pass",
      "Пароль",
      true,
      $_POST['pass']
   );

   @$pass_again = new FieldTextPassword(
      "pass_again",
      "Повтор пароля",
      true,
      $_POST['pass_again']
   );

   @$email = new FieldTextEmail(
      "email",
      "E-mail",
      true,
      $_POST['email']
   );

   @$about = new FieldTextarea(
      "about",
      "О себе",
      false,
      $_POST['about']
   );

   @$form = new Form(
      array(
         "name" => $name,
         "pass" => $pass,
         "pass_again" => $pass_again,
         "email" => $email,
         "about" => $about
      ),
      "Добавить",
      "field"
   );

   // Обработчик HTML-формы
   if (!empty($_POST)) {
      $error = $form->check();

      // Проверка идентичности паролей
      if ($form->fields['pass']->value !=
         $form->fields['pass_again']->value)
            $error[] = "Неверный пароль";

      // Проверка существования пользователя в БД
      $query = "select count(*) from users
         where email = '{$form->fields['email']->value}'";
      $mal = mysqli_query($db->get_connect(), $query);
      if (!$mal)
         throw new ExceptionMysql(  mysqli_error($db->get_connect()),
                                    $query,
                                    "Ошибка регистрации пользователя");
      $result = mysqli_fetch_array($mal);
      if ($result['count(*)'] > 0)
         $error[] = "Пользователь с электронным адресом
            {$form->fields['email']->value} уже зарегистрирован";
      
      if (empty($error)) {
         $query = "insert into users values(
                     '0',
                     '{$form->fields['name']->value}',
                     MD5('{$form->fields['pass']->value}'),
                     '{$form->fields['email']->value}',
                     '{$form->fields['about']->value}',
                     now())";

         if (!mysqli_query($db->get_connect(), $query))
            throw new ExceptionMysql(mysqli_error($db->get_connect()),
               $query,
               "Ошибка регистрации пользователя");
         header("Location: ".$_SERVER['PHP_SELF']);
         exit();
      }
   }

   // Видимая часть страницы

   require_once("utils/top.php");

   // Выводим сообщение об ошибках, если они есть
   if (!empty($error))
      foreach ($error as $err)
         echo "<span style=\"color:red\">$err</span><br>";

   $form->print_form();
}
catch(ExceptionObject $exc) { require("exception_object.php"); }
catch(ExceptionMember $exc) { require("exception_member.php"); }
catch(ExceptionMember $exc) { require("exception_mysql.php"); }

try {
   $query = "select * from lfw.users";
   $usr = mysqli_query($db->get_connect(), $query);
   if (!$usr) {
      throw new ExceptionMySQL(mysqli_error($db->get_connect()),
         $query, "Ошибка обращения к списку пользователей");
   }
   if (mysqli_num_rows($usr)) {
      while ($user = mysqli_fetch_array($usr)) {
         echo "<a href=edituser.php?id_user=$user[id_user]>".
            htmlspecialchars($user['name'], ENT_QUOTES).
            "</a><br>";
      }
   }
} catch (ExceptionMySQL $exc) { require("exception_mysql.php"); }
require_once("utils/bottom.php");
?>