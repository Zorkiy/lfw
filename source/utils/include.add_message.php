<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 
?>
<tr><td colspan="2"><p class="fieldname">���������:<br>
<textarea class=input style="padding-right: 10px;" cols=110 rows=15 name=message><?php echo htmlspecialchars($message, ENT_QUOTES); ?></textarea></td></tr>
  <?php
    if(!empty($_REQUEST['putfile']) && $_REQUEST['putfile'] != '-')
    {
      // ���� ���� � ������ �� ������, ��������� 
      // ���������� ���
  ?>
  <tr><td colspan="2"><p class="fieldname">
    <input type="checkbox" name="delete_file" <?php echo $_REQUEST['delete_file']; ?>>&nbsp;������� ��������</td>
  </tr>
  <?php
    }
  ?>
<tr><td><p class="fieldname">����������:</td><td><input class=input type=file name=attach size=82></td></tr>
<tr><td>&nbsp;</td><td><input class=button type=submit name=send value=���������></td><td></td></tr>
<?php
  // ������ 
  $dirname = '../skins/'.$skin.'smiles';
  if (file_exists($dirname))
  {
    ?>
    <tr><td colspan="2"><br>
    <p class=texthelp>��� ������� ������� � ����� �������� �� ������.
    <div class="blockremark">
    <?php
    $dir = opendir($dirname);
    if (!$dir) print "$dirname ";
    while ($smile = readdir($dir))
    {
      if(($smile != ".") 
          &&($smile != "..")
          &&($smile != "Thumbs.db")  
          && (substr($smile, -3) != "php")
          &&(is_dir($smile) != "true" ))
      {
         echo " <a href=# onClick=\"javascript:tag(' [:".substr($smile,0,strpos($smile,".")).":] ',''); return false;\"><img src='{$skin}smiles/$smile' border=0 hspace=1/></a>";
      }
    }
    ?>
  </div>
  </td></tr>
  <?php
  }  
  // ������ 
?>        
<input type=hidden name=id_theme value="<? echo htmlspecialchars($id_theme, ENT_QUOTES); ?>">
<input type=hidden name=id_post value="<? echo htmlspecialchars($id_post, ENT_QUOTES); ?>">
<input type=hidden name=id_forum value="<? echo htmlspecialchars($id_forum, ENT_QUOTES); ?>">