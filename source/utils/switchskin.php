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
<form action="skin.php" method="POST">
<?php
$skin_dir = opendir("../skins");
  while(($dir = readdir($skin_dir)))
  {
    // ���� ��������� ������ � ����� skins
    // �������� �����������, ������� ��� �
    // ������ $skin_list()
    if(@is_dir("../skins/".$dir) && $dir != "." && $dir != "..") $skin_list[] = $dir;
  }
  // ��������� ����������
  closedir($skin_dir);
?>

<table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class=switchforum>      
                <nobr>
                <p class=texthelp>������� ������ skin<br>
               <select class=input type=text name='skin'>
<?php
  foreach($skin_list as $value)
  {
    // ���������� ��������� �����
    if(isset($_COOKIE['skin']))
    {
      if($_COOKIE['skin'] == $value ) $chk = "selected";
      else $chk = "";
    }
    else
    {
      if($settings['skin'] == $value ) $chk = "selected";
      else $chk = "";
    }
    echo "<option $chk value=$value>$value";
  }
?>
</select>
  <input class=button type=submit value="�������">
</nobr>
  </td></tr>
  </table>
  </form>