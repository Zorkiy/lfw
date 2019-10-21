<?php
  ///////////////////////////////////////////////////
  // Web-приложение форум - LiteForum
  // Поддержка http://www.softtime.ru/forum/
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  ///////////////////////////////////////////////////
?>
<body class=body style="padding: 2px; padding-top: 2px" topmargin="0" bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr align="center">
    </tr>
</table>
<?php
  if ($shownewpost) include "../utils/newpostslist.php";
?>
<table class="diztabletop" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width=1%>&nbsp;</td>    
        <td class=starttd>&nbsp;</td>
        <!--td class=nameforumtd valign="bottom" style="padding-top: 10px"-->
        <td class=messagenews style="padding: 10px 0px 5px 0px">
        <?php
          include "../utils/currentforum.php";  
        ?>
        </td>
        <td>
          <table width=100%><tr valign=top><td>
        <?php
          include "../utils/messagelinks.php";
        ?>
          <br clear="all">
	    </td><td style="padding-top: 6px; padding-left: 10px">	
	    </td></tr></table>	

        </td>
    </tr>
</table>
<table width="100%" cellspacing="0" border="0">
  <tr>
    <td class=infotd style='padding: 0px 0px 5px 10px'>
        <a class=menuinfo href='update.php'>Обновить&nbsp;визитку</a>&nbsp;
        <a class=menuinfo href=rules.php?id_forum=<?php echo $id_forum; ?>>Правила&nbsp;форума</a>&nbsp;
        <a class=menuinfo href=online.php?id_forum=<?php echo $id_forum; ?>>Участники&nbsp;"Online"</a>&nbsp;
        <a class=menuinfo href=authorslist.php?id_forum=<?php echo $id_forum;?>>Все&nbsp;участники</a>&nbsp;
        <a class=menuinfo href='xml.xml'>RSS-канал</a>&nbsp;&nbsp;
        <a class=menuinfo href='index.php?id_forum=<?php echo $id_forum; ?>'>Живой форум</a>&nbsp;&nbsp;
        <a class=menuinfo href='archive.php?id_forum=<?php echo $id_forum; ?>'>Архив</a>&nbsp;&nbsp;
        <a class=menuinfo href='index.php'>Список форумов</a>&nbsp;&nbsp;
    </td>
  </tr>
  <tr><td class=dizline></td></tr>
</table>    

<div class=switchskindiv>
<?php
  if($show_switch_forum) include "../utils/switchskin.php";
?>
</div>   

<div class=switchforumdiv>
<?php
  if($show_switch_forum) include "../utils/switchforum.php";
?>
</div>     

<div >&nbsp;</div>      
<?  // Приветствие
  include "../utils/salutation.php";  
?>  
<?  
  // Меню форума
  include "../utils/menu.php";
?>  
<div class=bodydiv>