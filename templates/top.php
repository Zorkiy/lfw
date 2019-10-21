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

  // Устанавливаем соединение с базой данных
  require_once("config/config.php");
  // Подключаем систему классов 
  require_once("config/class.config.php");
  // Подключаем функцию вывода текста с bbCode
  require_once("dmn/utils/utils.print_page.php");
  // Подключаем счётчик посещений
  require_once("count.php");
?>
<html>
<head>
<title><?php echo $pagename; ?></title>
<meta name="keywords" content="<? echo htmlspecialchars($keywords, ENT_QUOTES); ?>">
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="site.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="116" background="dataimg/bg_main.gif">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<!--td width="250"><img src="dataimg/logo.gif" width="250" height="116"></td-->
<td width="220" nowrap background="/dataimg/bg_city.gif">&nbsp;</td>
<td>&nbsp;</td>
<td width="250" background="dataimg/bg_autorize.gif">

</td>
</tr>
</table>

</td>
</tr>
<tr>
<td valign="top">

<!-- s mainbody -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="250" align="center" valign="top" style="padding-top: 5px;">

<!-- s menu -->
<table width="240" border="0" cellpadding="0" cellspacing="0" bgcolor="#004BBC">
<tr>
<td height="20" class="menu_ttl"><img src="dataimg/dot_ttl.gif" align="absmiddle"> РАЗДЕЛЫ</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr>
<tr>
<td height="3" nowrap bgcolor="#82A6DE"></td>
</tr>
<tr>
<td bgcolor="#EBEAF4">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td width="100%" height="25" align="left" class="in_input_txt"><br>
  <?php
    $query = "SELECT * FROM $tbl_catalog
              WHERE hide = 'show' AND id_parent = 0
              ORDER BY pos";
    $cat = mysql_query($query);
    if (!$cat) exit("Ошибка при обращении к блоку статей");
    if(mysql_num_rows($cat))
    {
      while($catalog = mysql_fetch_array($cat))
      {
        echo "<b><a href=index.php?id_catalog=$catalog[id_catalog]
                    class=\"rightpanel_lnk\">$catalog[name]</a></b><br><br>";
      }
    }
  ?>
  </td>
<tr>
</tr>
</form>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<!-- e menu -->

<!-- s menu -->
<table width="240" border="0" cellpadding="0" cellspacing="0" bgcolor="#004BBC">
<tr>
<td height="20" class="menu_ttl"><img src="dataimg/dot_ttl.gif" align="absmiddle"> ПОИСК</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr>
<tr>
<td height="3" nowrap bgcolor="#82A6DE"></td>
</tr>
<tr>
<td bgcolor="#EBEAF4">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <form action=search_fulltext.php method=GET>
  <td width="100%" height="25" align="left" class="in_input_txt"><br>
      <input type=text class=in_input size=30 name=name value="<?= htmlspecialchars(stripslashes($_GET['name']), ENT_QUOTES); ?>">
      <input type=submit class=in_input value="Искать"><br><br>
  </td>
    </form>
<tr>
</tr>
</form>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<!-- e menu -->

<!-- s menu -->
<table width="240" border="0" cellpadding="0" cellspacing="0" bgcolor="#004BBC">
<tr>
<td height="20" class="menu_ttl"><img src="dataimg/dot_ttl.gif" align="absmiddle"> КАТАЛОГ</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr>
<tr>
<td height="3" nowrap bgcolor="#82A6DE"></td>
</tr>
<tr>
<td bgcolor="#EBEAF4">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td width="100%" height="25" align="left" class="in_input_txt"><br>
  <?php
    $query = "SELECT * FROM $tbl_cat_catalog
              WHERE hide = 'show' AND id_parent = 0
              ORDER BY pos";
    $cat = mysql_query($query);
    if (!$cat) exit("Ошибка при обращении к каталогу");
    if(mysql_num_rows($cat))
    {
      while($catalog = mysql_fetch_array($cat))
      {
        echo "<b><a href=index.php?id_catalog=$catalog[id_catalog]
                    class=\"rightpanel_lnk\">$catalog[name]</a></b><br><br>";
      }
      echo "<b><a href=catalog_search.php
                  class=\"rightpanel_lnk\">Поиск</a></b><br><br>";
    }
  ?>
  </td>
<tr>
</tr>
</form>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<!-- e menu -->

<!-- s menu -->
<table width="240" border="0" cellpadding="0" cellspacing="0" bgcolor="#004BBC">
<tr>
<td height="20" class="menu_ttl"><img src="dataimg/dot_ttl.gif" align="absmiddle"> НОВОСТИ</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr>
<tr>
<td height="3" nowrap bgcolor="#82A6DE"></td>
</tr>
<tr>
<td bgcolor="#EBEAF4">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td width="100%" height="25" align="left" class="in_input_txt">
  <?php
    $query = "SELECT id_news,
                     name,
                     body,
                     DATE_FORMAT(putdate,'%d.%m.%Y') as putdate_format,
                     url,
                     urltext,
                     urlpict,
                     hide
              FROM $tbl_news
              WHERE hide = 'show'
              ORDER BY putdate DESC 
              LIMIT 3";
    $new = mysql_query($query);
    if (!$new) exit("Ошибка при обращении к блоку новостей");
    if(mysql_num_rows($new))
    {
      $patt = array("[b]", "[/b]", "[i]", "[/i]");
      $repl = array("", "", "", "");
      $pattern_url = "|\[url[^\]]*\]|";
      $pattern_b_url = "|\[/url[^\]]*\]|";
      while($news_up = mysql_fetch_array($new))
      {
        if(strlen($news_up['body']) > 100)
        {
          $news_up['body'] = substr($news_up['body'], 0, 100)."...";
          $news_up['body'] = str_replace($patt, $repl, $news_up['body']);
          $news_up['body'] = preg_replace($pattern_url, "", $news_up['body']);
          $news_up['body'] = preg_replace($pattern_b_url, "", $news_up['body']);
        }
  
        echo "<b>$news_up[putdate_format] | ".print_page($news_up['name'])."</b>
            <br> 
            ".print_page($news_up['body'])."
            <div align=\"right\">
              <a href=\"news.php?id_news=$news_up[id_news]\" 
                 class=\"rightpanel_lnk\">
                 <img src=\"dataimg/dot_lnk.gif\" hspace=\"6\" border=\"0\" align=\"absmiddle\">подробнее</a>&nbsp;&nbsp;&nbsp;
            </div>
            <br>";
      }
    }
    echo "<div align=\"center\">
            <a href=\"news.php\" 
               class=\"rightpanel_lnk\">все новости</a><br>
          </div><br>";
  ?>
  </td>
<tr>
</tr>
</form>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<!-- e menu -->

</td>
<td valign="top" style="padding-top: 5px; padding-left: 10px; padding-right: 15px; padding-bottom: 10px;">
