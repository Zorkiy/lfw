<?php
  ////////////////////////////////////////////////////////////
  // 2005-2008 (C) �������� �.�., �������� �.�.
  // PHP. �������� �������� Web-������
  // IT-������ SoftTime 
  // http://www.softtime.ru   - ������ �� Web-����������������
  // http://www.softtime.biz  - ������������ ������
  // http://www.softtime.mobi - ��������� �������
  // http://www.softtime.org  - �������������� �������
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� ������� ��������� ������ 
  require_once("../utils/utils.print_page.php");

  // ��������� ������
  $body = '
<html>
<head>
<title>������� �������� MikroComp</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=KOI8-R\">
<style>
li {
	list-style-image: url(dataimg/optdot_prd.gif);
	list-style-type: square;
}

.in_input {
	font-family: Tahoma;
	font-size: 11px;
	color: #000000;
	background-color: #FFFFFF;
	border: 1px solid #B20000;
}

.in_button {
	font-family: Tahoma;
	font-size: 11px;
	font-weight: bold;
	color: #FFFFFF;
	background-color: #FF0000;
	border: 1px solid #880000;	
}

.menu1_txt {
	font-family: Tahoma;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
}

.menu1_txt_lnk {
	color: #FFFFFF;
	text-decoration: none;
}

.menu1_txt_lnk:hover {
	color: #CCCCCC;
}


.menu1s_txt {
	font-family: Tahoma;
	font-size: 11px;
	font-weight: bold;
	color: #FFFFFF;
	padding-right: 5px;
}

.menu1s_txt_lnk {
	color: #FFFFFF;
	text-decoration: none;
}

.menu1s_txt_lnk:hover {
	color: #B20000;
}


.menu2_txt {
	font-family: Tahoma;
	font-size: 11px;
	color: #000000;
	padding-top: 5px;
	padding-left: 5px;
	padding-right: 5px;
	padding-bottom: 5px;
}

.menu2_txt_lnk {
	color: #B20000;
	text-decoration: none;
}

.menu2_txt_lnk:hover {
	color: #00023E;
}

.menu2_ttl {
	font-family: Tahoma;
	font-size: 12px;
	font-weight: bold;
	color: #00023E;
	padding-left: 15px;	
}

.menu2_ttl_lnk {
	color: #00023E;
	text-decoration: none;
}

.menu2_ttl_lnk:hover {
	color: #B20000;
}

.main_ttl {
	font-family: Tahoma;
	font-size: 12px;
	font-weight: bold;
	color: #00023E;
	padding-left: 10px;	
}

.main_txt {
	font-family: Tahoma;
	font-size: 11px;
	text-align: justify; 
	color: #000000;
	padding-top: 5px;
	padding-bottom: 5px;
}

.main_txt_lnk {
	color: #B20000;
	text-decoration: none;
}

.main_txt_lnk:hover {
	color: #00023E;
}

.news_ttl {
	font-family: Tahoma;
	font-size: 12px;
	font-weight: bold;
	color: #00023E;
	padding-left: 10px;	
}

.news_txt {
	font-family: Tahoma;
	font-size: 11px;
	text-align: left; 
	color: #000000;
	padding-top: 5px;
	padding-left: 5px;
	padding-right: 5px;
	padding-bottom: 5px;
}

.news_txt_lnk {
	color: #B20000;
	text-decoration: none;
}

.news_txt_lnk:hover {
	color: #00023E;
}

.signature_txt {
	font-family: Tahoma;
	font-size: 11px;
	color: #FFFFFF;
	padding-right: 5px;
}

.signature_txt_lnk {
	color: #FFFFFF;
	text-decoration: none;
}

.signature_txt_lnk:hover {
	color: #B20000;
}

.product_ttl {
	font-family: Tahoma;
	font-size: 12px;
	font-weight: bold;
	color: #B20000;
	padding-bottom: 5px;
}

.product_txt {
	font-family: Tahoma;
	font-size: 11px;
	text-align: left;
	color: #000000;
	padding-top: 5px;
	padding-bottom: 5px;
}

.product_txt_lnk {
	color: #B20000;
	text-decoration: none;
}

.product_txt_lnk:hover {
	color: #00023E;
}

.table1_txt {
	font-family: Tahoma;
	font-size: 11px;
	color: #00023E;
	padding-top: 5px;
	padding-left: 5px;
	padding-right: 5px;
	padding-bottom: 5px;
}

.table1_txt_lnk {
	color: #00023E;
	text-decoration: none;
}

.table1_txt_lnk:hover {
	color: #00023E;
	text-decoration: underline;
}

.table1_tr_ttl_clr {
	background-color: #FF0000;
}

.table1_tr_clr1 {
	background-color: #F6F6F6;
}

.table1_tr_clr2 {
	background-color: #EEEEEE;
}
</style>
</head>
<body style="padding: 5px;">';

  $body .= "<h3>{$form->fields[name]->value}</h3>";
  $body .= "<div class=main_txt>".nl2br(print_page($form->fields['body']->value));
  $body .= "</div></body></html>";

  $header = "Content-Type: text/html; charset=koi8-r\r\n\r\n";

  // ��������� e-mail �����������
  $query = "SELECT * FROM $tbl_users
            GROUP BY email";
  $eml = mysql_query($query);
  if(!$eml)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ��� ����������
                             ������ e-mail");
  }
  if(mysql_num_rows($eml))
  {
    while($email = mysql_fetch_array($eml))
    {
       @mail($email['email'],
             convert_cyr_string($form->fields['name']->value,'w','k'), 
             convert_cyr_string($body,'w','k'), 
             $header);
    }
  }
?>