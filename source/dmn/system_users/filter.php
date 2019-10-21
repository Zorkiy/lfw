<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) Кузнецов М.В., Симдянов И.В.
  // PHP. Практика создания Web-сайтов
  // IT-студия SoftTime 
  // http://www.softtime.ru   - портал по Web-программированию
  // http://www.softtime.biz  - коммерческие услуги
  // http://www.softtime.mobi - мобильные проекты
  // http://www.softtime.org  - некоммерческие проекты
  ////////////////////////////////////////////////////////////
  // Выставляем уровень обработки ошибок 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  // Поиск по дате
  echo "<a class=link href=$_SERVER[PHP_SELF]?page$_GET[page]>Снять фильтр</a><br>";
?>
<table>
  <tr>
    <td>
<?php
  if(empty($_GET['begin_date'])) $chk_begin = "";
  else $chk_begin = "checked";
  if(empty($_GET['end_date'])) $chk_end = "";
  else $chk_end = "checked";
?>
<form name=form action=filterset.php?<? echo "?page=$_GET[page]"; ?> method=post>
<table>
<tr>
  <td class=field>Начало:</td>                                                             
  <td class=field><input type="checkbox" name="chk_begin" onclick="freeze_begin(this.form)" <?php echo htmlspecialchars($chk_begin); ?>></td>
  <td class=field>
   <?php
     if(empty($_GET['begin_date'])) $date = time();
     else $date = $_GET['begin_date'];
     $date_month  = date("m",$date);
     $date_day    = date("d",$date);
     $date_year   = date("Y",$date);
     // Выпадающий список для дня
     echo "<select title='День' class=input type=text name='b_date_day'>";
     for($i = 1; $i <= 31; $i++)
     {
       if($date_day == $i) $temp = "selected";
       else $temp = "";
       echo "<option value=$i $temp>".sprintf("%02d", $i);
     }
     echo "</select>";
     // Выпадающий список для месяца
     echo "<select class=input type=text name='b_date_month'>";
     for($i = 1; $i <= 12; $i++)
     {
       if($date_month == $i) $temp = "selected";
       else $temp = "";
       echo "<option value=$i $temp>".sprintf("%02d", $i);
     }
     echo "</select>";
     // Выпадающий список для года
     echo "<select class=input type=text name='b_date_year'>";
     for($i = 2004; $i <= 2017; $i++)
     {
       if($date_year == $i) $temp = "selected";
       else $temp = "";
       echo "<option value=$i $temp>$i";
     }
     echo "</select>";
   ?>
    </td>
  <td><p class=help></td>
</tr>
<tr>
  <td class=field>Конец:</td>                                                             
  <td class=field><input type="checkbox" name="chk_end" onclick="freeze_end(this.form)" <?php echo htmlspecialchars($chk_end); ?>></td>
  <td class=field>
   <?php
     if(empty($_GET['end_date'])) $date = time();
     else $date = $_GET['end_date'];
     $date_month  = date("m",$date);
     $date_day    = date("d",$date);
     $date_year   = date("Y",$date);
     // Выпадающий список для дня
     echo "<select title='День' class=input type=text name='e_date_day'>";
     for($i = 1; $i <= 31; $i++)
     {
       if($date_day == $i) $temp = "selected";
       else $temp = "";
       echo "<option value=$i $temp>".sprintf("%02d", $i);
     }
     echo "</select>";
     // Выпадающий список для месяца
     echo "<select class=input type=text name='e_date_month'>";
     for($i = 1; $i <= 12; $i++)
     {
       if($date_month == $i) $temp = "selected";
       else $temp = "";
       echo "<option value=$i $temp>".sprintf("%02d", $i);
     }
     echo "</select>";
     // Выпадающий список для года
     echo "<select class=input type=text name='e_date_year'>";
     for($i = 2004; $i <= 2017; $i++)
     {
       if($date_year == $i) $temp = "selected";
       else $temp = "";
       echo "<option value=$i $temp>$i";
     }
     echo "</select>";
   ?>
    </td>
  <td><p class=help></td>
</tr>
<tr>
  <td class=field></td>                                                             
  <td class=field></td>
  <td class=field><input type="submit" class=button value="Установить фильтр"></td>
  <td><p class=help></td>
</tr>
</table>
</form>
<script language="JavaScript"> 
<!-- 
  function freeze_begin(form) 
  { 
    form.b_date_day.disabled    = !form.chk_begin.checked; 
    form.b_date_month.disabled  = !form.chk_begin.checked; 
    form.b_date_year.disabled   = !form.chk_begin.checked; 
  } 
  function freeze_end(form) 
  { 
    form.e_date_day.disabled    = !form.chk_end.checked; 
    form.e_date_month.disabled  = !form.chk_end.checked; 
    form.e_date_year.disabled   = !form.chk_end.checked; 
  } 

  if('<?= $chk_begin; ?>' == 'checked')
  {
    document.form.b_date_day.disabled    = false; 
    document.form.b_date_month.disabled  = false; 
    document.form.b_date_year.disabled   = false; 
  }
  else
  {
    document.form.b_date_day.disabled    = true;
    document.form.b_date_month.disabled  = true;
    document.form.b_date_year.disabled   = true;
  }
  if('<?= $chk_end; ?>' == 'checked')
  {
    document.form.e_date_day.disabled    = false; 
    document.form.e_date_month.disabled  = false; 
    document.form.e_date_year.disabled   = false; 
  }
  else
  {
    document.form.e_date_day.disabled    = true;
    document.form.e_date_month.disabled  = true;
    document.form.e_date_year.disabled   = true;
  }
//--> 
</script> 
   </td>
 </tr>
</table>