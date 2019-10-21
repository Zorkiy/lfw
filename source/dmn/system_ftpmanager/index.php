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

  // ������ ���������� ���������� �������� �������� � ���������.
  $title = '������ ������� � FTP-�������';
  $pageinfo = '<p class=help>������ ������� � FTP-������� 
  ��������� ��������� �� ������ ����� � ��������� ��������; 
  ����� ����, ����������� ���������������, �������, �������� 
  ����� ������� � ��� ������������ ������ � �����������.</p>';

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // �������� ��������� ��������
  require_once("../utils/top.php");
  // ������������� ���������� � FTP-��������
  require_once("../../config/ftp_connect.php");

  if(empty($_GET['dir'])) $directory = "/";
  else $directory = $_GET['dir'];

  $file_list = ftp_rawlist($ftp_handle, $directory);
  if(!empty($file_list))
  {
    // ������� ������ �� ���������� ��������
    $_GET['dir'] = rtrim($_GET['dir'],"/");
    $prev = explode("/",$_GET['dir']);
    if(!empty($prev))
    {
      $prev_path = "";
      $link = array();
      for($i = 0; $i < count($prev); $i++)
      {
        $prev_pach .= "/".$prev[$i];
        $prev_pach = str_replace("//","/",$prev_pach);
        if(!empty($prev[$i])) 
        {
          $link[] = "<a href=index.php?dir=".
                     urlencode($prev_pach).">".$prev[$i]."</a>";
        }
        else
        {
          $link[] = "<a href=index.php?dir=".
                     urlencode($prev_pach).">�������� ����������</a>";
        }
      }

      echo "<p class=help>".implode("-&gt;",$link)."</p>";
      echo "<br>";
    }
    ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td>&nbsp;</td>
          <td align=center>����� �������</td>
          <td align=center>������, �����</td>
          <td align=center>����� ��������</td>
          <td align=center colspan=2>��������</td>
        </tr>
    <?php
    $i = 0;
    $dir = array();
    $fil = array();
    foreach($file_list as $file_single)
    {
      // ��������� ������ �� ���������� ��������
      list($acc,
           $bloks,
           $group,
           $user,
           $size, 
           $month, 
           $day, 
           $year, 
           $file) = preg_split("/[\s]+/", $file_single);

      if($file == ".." || $file == ".") continue;

      $eng = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); 
      $rus = array("���", "���", "���", "���", "���", "���", 
                   "���", "���", "���", "���", "���", "���");
      $month = str_replace($eng, $rus, $month);
      $url = urlencode(str_replace("//","/",$directory."/".$file));
      if($acc[0] == 'd')
      {
        // ����������
        $dir[$i]['acc']    = $acc;
        $dir[$i]['bloks']  = $bloks;
        $dir[$i]['group']  = $group;
        $dir[$i]['user']   = $user;
        $dir[$i]['size']   = $size;
        $dir[$i]['month']  = $month;
        $dir[$i]['day']    = $day;
        $dir[$i]['year']   = $year;
        $dir[$i]['file']   = "<b><a href=index.php?dir=$url 
                              title='������� ����������'>$file</a></b>";
        $dir[$i]['delete'] = "<p><a href=# 
                              onClick=\"delete_position('rmdir.php?dir=$url',".
                             "'�� ������������� ������ ������� ��� ����������?');\"
                              >�������</a></p>";
        $dir[$i]['edit']   = "<p><a href=chdirform.php?dir=$url&acc=$acc
                              >�������������</a></p>";
        $dir[$i]['size']   = "&lt;DIR&gt;";
      }
      else
      { 
        // ����
        $fil[$i]['acc']    = $acc;
        $fil[$i]['bloks']  = $bloks;
        $fil[$i]['group']  = $group;
        $fil[$i]['user']   = $user;
        $fil[$i]['size']   = $size;
        $fil[$i]['month']  = $month;
        $fil[$i]['day']    = $day;
        $fil[$i]['year']   = $year;
        $fil[$i]['file']   = "<a href=download.php?dir=$url 
                              title='��������� ����'>$file</a>";
        $fil[$i]['delete'] = "<p><a href=# 
                              onClick=\"return delete_position('rmfile.php?dir=$url',".
                              "'�� ������������� ������ ������� ���� ����?');\"
                              >�������</a></p>";
        $fil[$i]['edit']   = "<p><a href=chdirform.php?dir=$url&acc=$acc&file=file
                              >�������������</a></p>";
      }
      $i++;
    }
    // ������� ����������
    foreach($dir as $name)
    {
      echo "<tr>
              <td align=right>$name[file]</td>
              <td align=center>$name[acc]</td>
              <td align=center>$name[size]</td>
              <td align=center>$name[day]&nbsp;&nbsp;
                               $name[month]&nbsp;&nbsp;
                               $name[year]</td>
              <td align=center>$name[delete]</td>
              <td align=center>$name[edit]</td>
            </tr>";
    }
    // ������� �����
    foreach($fil as $name)
    {
      echo "<tr>
              <td align=right>$name[file]</td>
              <td align=center>$name[acc]</td>
              <td align=center>$name[size]</td>
              <td align=center>$name[day]&nbsp;&nbsp;
                               $name[month]&nbsp;&nbsp;
                               $name[year]</td>
              <td align=center>$name[delete]</td>
              <td align=center>$name[edit]</td>
            </tr>";
    }
    echo "</table><br><br>";
  }
?>
  <table><tr><td>
<?php
  ftp_close($ftp_handle);

  // �������� ����� �� ������
  $action = "upload.php";
  $button = "���������";
  // ����� ������� �� ��������� ���
  // ������������
  $ur = "checked";
  $uw = "checked";
  $ux = "";
  // ����� ������� �� ��������� ���
  // ������
  $gr = "checked";
  $gw = "";
  $gx = "";
  // ����� ������� �� ��������� ���
  // ��������� ������������� (�� �������� � ������)
  $or = "checked";
  $ow = "";
  $ox = "";

  $ur_hint = '������ ������ ���������� ��� ���������';
  $uw_hint = '�������� � �������������� ������ � ���������� ��� ���������';
  $ux_hint = '������ ����������� ���������� ��� ���������';
  $gr_hint = '������ ������ ���������� ��� ������';
  $gw_hint = '�������� � �������������� ������ � ���������� ��� ������';
  $gx_hint = '������ ����������� ���������� ��� ������';
  $or_hint = '������ ������ ���������� ��� ������������� �� �������� � ������ ���������';
  $ow_hint = '�������� � �������������� ������ � ���������� ��� ������������� �� �������� � ������ ���������';
  $ox_hint = '������ ����������� ���������� ��� ������������� �� �������� � ������ ���������';
?>
<form enctype='multipart/form-data' action=<?php echo htmlspecialchars($action); ?> method=post>
<table>
<tr>
  <td class=field>����:</td>
  <td><input type=file name=name value=''></td>
</tr>
<tr>
  <td class=field>����� �������:</td>
  <td>
    <input type=checkbox 
           title='<?php echo $ur_hint; ?>' 
           name=ur <?php echo $ur; ?>>
    <input type=checkbox 
           title='<?php echo $uw_hint; ?>' 
           name=uw <?php echo $uw; ?>>
    <input type=checkbox 
           title='<?php echo $ux_hint; ?>' 
           name=ux <?php echo $ux; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $gr_hint; ?>' 
           name=gr <?php echo $gr; ?>>
    <input type=checkbox 
           title='<?php echo $gw_hint; ?>' 
           name=gw <?php echo $gw; ?>>
    <input type=checkbox 
           title='<?php echo $gx_hint; ?>' 
           name=gx <?php echo $gx; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $or_hint; ?>' 
           name=or <?php echo $or; ?>>
    <input type=checkbox 
           title='<?php echo $ow_hint; ?>' 
           name=ow <?php echo $ow; ?>>
    <input type=checkbox 
           title='<?php echo $ox_hint; ?>' 
           name=ox <?php echo $ox; ?>>
  </td>
</tr>
<tr><td>&nbsp;</td>
<td><input class=button 
           type=submit 
           value=<?php echo htmlspecialchars($button);?>></td></tr>
<input type=hidden 
       name=dir 
       value=<?php echo htmlspecialchars($directory);?>>
</table>
</form>

</td><td>

<?php
  // ���� �� �������� ��������� - �����������
  // ����� �� ���������� ����������
  $action = "mkdir.php";
  $button = "�������";
  // ����� ������� �� ��������� ���
  // ������������
  $ur = "checked";
  $uw = "checked";
  $ux = "checked";
  // ����� ������� �� ��������� ���
  // ������
  $gr = "checked";
  $gw = "";
  $gx = "checked";
  // ����� ������� �� ��������� ���
  // ��������� ������������� (�� �������� � ������)
  $or = "checked";
  $ow = "";
  $ox = "checked";

  $ur_hint = '������ ����� ��� ���������';
  $uw_hint = '�������������� ����� ��� ���������';
  $ux_hint = '���������� ����� ��� ���������';
  $gr_hint = '������ ����� ��� ������';
  $gw_hint = '�������������� ����� ��� ������';
  $gx_hint = '���������� ����� ��� ������';
  $or_hint = '������ ����� ��� ������������� �� �������� � ������ ���������';
  $ow_hint = '�������������� ����� ��� ������������� �� �������� � ������ ���������';
  $ox_hint = '���������� ����� ��� ������������� �� �������� � ������ ���������';
?>
<form action=<?php echo htmlspecialchars($action); ?> method=post>
<table>
<tr>
  <td class=field>�������� ����������:</td>
  <td><input size=31 type=text name=name value=''></td>
</tr>
<tr>
  <td class=field>����� �������:</td>
  <td>
    <input type=checkbox 
           title='<?php echo $ur_hint; ?>' 
           name=ur <?php echo $ur; ?>>
    <input type=checkbox 
           title='<?php echo $uw_hint; ?>' 
           name=uw <?php echo $uw; ?>>
    <input type=checkbox 
           title='<?php echo $ux_hint; ?>' 
           name=ux <?php echo $ux; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $gr_hint; ?>' 
           name=gr <?php echo $gr; ?>>
    <input type=checkbox 
           title='<?php echo $gw_hint; ?>' 
           name=gw <?php echo $gw; ?>>
    <input type=checkbox 
           title='<?php echo $gx_hint; ?>' 
           name=gx <?php echo $gx; ?>>
    &nbsp;&nbsp;
    <input type=checkbox 
           title='<?php echo $or_hint; ?>' 
           name=or <?php echo $or; ?>>
    <input type=checkbox 
           title='<?php echo $ow_hint; ?>' 
           name=ow <?php echo $ow; ?>>
    <input type=checkbox 
           title='<?php echo $ox_hint; ?>' 
           name=ox <?php echo $ox; ?>>
  </td>
</tr>
<tr><td>&nbsp;</td>
<td><input class=button 
           type=submit 
           value=<?php echo htmlspecialchars($button);?>></td></tr>
<input type=hidden 
       name=dir 
       value=<?php echo htmlspecialchars($directory);?>>
</table>
</form>

</td></tr></table>

<?php
  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>