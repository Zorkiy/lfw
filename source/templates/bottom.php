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
?>

<!-- s rightpanel -->
</td>
<td width="250" valign="top" style="padding-top: 5px;">

<!-- s rightpanel -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="20" bgcolor="#82A6DE" class="rightpanel_ttl"><img src="dataimg/dot_ttl.gif" align="absmiddle"> �����</td>
</tr>
<tr>
<td height="3" nowrap bgcolor="#004BBC"></td>
</tr>
<tr>
<td bgcolor="#EBEAF4" class="rightpanel_txt">
  <?php
    // ���� �����������
    // ����������� ������� �����
    $query = "SELECT * FROM $tbl_poll
              WHERE archive = 'active' AND 
      	            hide = 'show'";
    $pol = mysql_query($query);
    if(!$pol)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ��������� 
                               � ���������� �������");
    }
    if(mysql_num_rows($pol))
    {
      // ��������� ��������� �������
      $poll = mysql_fetch_array($pol);

      // ��������� ������
      echo "<b>$poll[name]</b><br>
            <a class=\"rightpanel_lnk\" href=poll.php>����������</a>";
      
      // ��������� �������� �������
      $query = "SELECT * FROM $tbl_poll_answer
                WHERE id_catalog = $poll[id_catalog]
                ORDER BY pos";
      $ans = mysql_query($query);
      if(!$ans)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� 
                                 � ��������� �������");
      }
      if(mysql_num_rows($ans))
      {
        echo "<form action=poll.php method=post>";
        while($answers = mysql_fetch_array($ans))
        {
          echo "<input type=radio name=id_position value=\"$answers[id_position]\"> $answers[name]<br>";
        }
        echo "<br><input class=in_input type=submit value=�������������>";
        echo "</form>";
      }
    }
  ?>
</td>
</tr>

<tr>
<td>&nbsp;</td>
</tr>

<tr>
<td height="20" bgcolor="#82A6DE" class="rightpanel_ttl"><img src="dataimg/dot_ttl.gif" align="absmiddle"> ����������</td>
</tr>
<tr>
<td height="3" nowrap bgcolor="#004BBC"></td>
</tr>
<tr>
<td bgcolor="#EBEAF4" class="rightpanel_txt"><br> 	
<?php
  // ������� ������ �����������
  $query = "SELECT * FROM $tbl_photo_catalog
            WHERE hide = 'show'";
  $cat = mysql_query($query);
  if(!$cat)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ��� ���������� 
                             �����������");
  }
  if(mysql_num_rows($cat) > 1)
  {
    while($catalog_photo = mysql_fetch_array($cat))
    {
      // ��������� ���������� ���������� � �������
      $query = "SELECT COUNT(*) FROM $tbl_photo_position
                WHERE id_catalog = $catalog_photo[id_catalog] AND
                      hide = 'show'";
      $cnt = mysql_query($query);
      if(!$cnt)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ���������� 
                                 ���������� ����������");
      }
      $total = mysql_result($cnt, 0);
      if($total > 0) $total = "&nbsp;($total)";
      else $total = "";
      echo "<b><a href=gallery.php?id_catalog=$catalog_photo[id_catalog] 
                    class=\"rightpanel_lnk\">".
           "{$catalog_photo[name]}$total</a></b><br><br>";
    }
  }
?>
</td>
</tr>

<tr>
<td>&nbsp;</td>
</tr>

<tr>
<td height="20" bgcolor="#82A6DE" class="rightpanel_ttl"><img src="dataimg/dot_ttl.gif" align="absmiddle"> �������������� �����</td>
</tr>
<tr>
<td height="3" nowrap bgcolor="#004BBC"></td>
</tr>
<tr>
<td bgcolor="#EBEAF4" class="rightpanel_txt"><br>
  <b><a href=guestbook.php class="rightpanel_lnk">�������� �����</a></b><br><br>
  <b><a href=forum/ class="rightpanel_lnk">�����</a></b><br><br>
  <b><a href=register.php class="rightpanel_lnk">������������������</a></b><br><br>
  <b><a href=register_enter.php class="rightpanel_lnk">����</a></b><br><br>
  <b><a href=remember.php class="rightpanel_lnk">��������� ������</a></b><br><br>
</td>
</tr>

</table>
<!-- s rightpanel -->

</td>
</tr>
</table>
<!-- e mainbody -->

</td>
</tr>
<tr>
<td height="3" nowrap bgcolor="#004BBC"></td>
</tr>
<tr>
<td height="100" bgcolor="#EBEAF4" class=main_txt>
<?php
  // ��������� ���������� ����������
  $query = "SELECT * FROM $tbl_contactaddress LIMIT 1";
  $adr = mysql_query($query);
  if(!$adr)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ���������� ���������� ����������");
  }
  $address = mysql_fetch_array($adr);
  $adr_arr = array();
  if(!empty($address['address'])) $adr_arr[] = "$address[address]";
  if(!empty($address['phone']))   $adr_arr[] = "���. $address[phone]";
  if(!empty($address['fax']))     $adr_arr[] = "fax $address[fax]";
  if(!empty($address['email']))   $adr_arr[] = "e-mail: $address[email]";
  if(!empty($adr_arr))
  {
    echo "<div style='text-align: right'>".implode("<br>", $adr_arr)."</div>";
  }
?>
</td>
</tr>
</table>
</body>
</html>
