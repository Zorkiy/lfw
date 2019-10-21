<body style="padding: 2px; padding-top: 2px" topmargin="0" bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" bgcolor="white">
<?php
  include "../utils/infolinks.php";
  if ($shownewpost) include "../utils/newpostslist.php";
?>
<table class="diztabletop" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width=5%></td>
        <td width=70% valign="bottom"><?php
            include "../utils/currentforum.php";  
            include "../utils/messagelinks.php"; ?></td>
        </td>
        <td class=infotd><p class=null>
          <table>
            <tr>
              <td><a class=menuinfo href='index.php?id_forum=<?php echo $id_forum; ?>'>Живой форум</a></td>
              <td><a class=menuinfo href='archive.php?id_forum=<?php echo $id_forum; ?>'>Архив</a><br></td>
            </tr>
            <tr>
              <td><a class=menuinfo href='update.php?id_forum=<?php echo $id_forum; ?>&update=update'>Обновить&nbsp;визитку</a></td>
              <td><a class=menuinfo href='rules.php?id_forum=<?php echo $id_forum; ?>'>Правила&nbsp;форума</a></td>
            </tr>
            <tr>
              <td><a class=menuinfo href='online.php?id_forum=<?php echo $id_forum; ?>'>Участники&nbsp;"Online"</a></td>
              <td><a class=menuinfo href='authorslist.php?id_forum=<?php echo $id_forum;?>'>Все&nbsp;участники</a></td>
            </tr>
            <tr>
              <td><a class=menuinfo href='xml.xml'>RSS-канал</a></td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </td>       
    </tr>
    <tr><td class=dizline colspan="3">&nbsp;</td></tr>
</table>
<div class=switchskindiv>
<?php
  if($show_switch_forum) include "../utils/switchskin.php";
?>
</div>   
<div class=switchforumdiv>
<?php
  // Переключатель форумов
  if($show_switch_forum) include "../utils/switchforum.php";
?>
</div> 
<div class=images>&nbsp;</div>      
<?php
  // Приветствие
  include "../utils/salutation.php";  
?>
<!--<div class=images>&nbsp;</div>-->
<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td class=bodydiv>
<?php
  // Меню форума
  include "../utils/menu.php";
?>  