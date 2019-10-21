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

  // ������� ��� ������ � �������
  require_once("../utils/utils.files.php");
?>
<table border="0" width="100%">
  <tr>
     <td><p class="fieldname">e-mail:</td>
     <td><input size=45 class=input type=text name=email maxlength=100 size=61 value='<?php echo htmlspecialchars($_REQUEST['email'], ENT_QUOTES); ?>'></td>
  </tr>
  <tr>
    <td><p class="fieldname">ICQ:</td>
    <td><input size=45 class=input type=text name=icq size=61 maxlength=100 value='<?php echo htmlspecialchars($_REQUEST['icq'], ENT_QUOTES); ?>'></td>
  </tr>
  <tr>
    <td><p class="fieldname">URL:</td>
    <td><input size=74 class=input type=text name=url size=61 maxlength=200 value='<?php echo htmlspecialchars($_REQUEST['url'], ENT_QUOTES); ?>'></td>
  </tr>
  <tr>
    <td><p class="fieldname">�&nbsp;����:</td>
    <td><textarea class=input cols=76 rows=3 name=about maxlength=500><?php echo htmlspecialchars($_REQUEST['about'], ENT_QUOTES); ?></textarea></td>
  </tr>
  <tr><td colspan="2"><p class="fieldname">
    <input type="checkbox" name="sendmail" <?php echo $_REQUEST['sendmail']; ?>>&nbsp;�������� ��������� � ����� ����� �� �����</td>
  </tr>
  <?php
    if(!empty($auth['photo']) && $auth['photo'] != '-')
    {
      // ���� ���� � ����������� �� ������, ��������� 
      // ���������� �
  ?>
  <tr><td colspan="2"><p class="fieldname">
    <input type="checkbox" name="delete_photo" <?php echo $_REQUEST['delete_photo']; ?>>&nbsp;������� �����������</td>
  </tr>
  <?php
    }
  ?>
  <tr valign="top">
    <td><p class="fieldname">����:</td>
    <td><p class="texthelp"><input class=input type=file name=photo size=61> (�� ����� <?php echo valuesize($settings['size_photo']); ?>)</td>
  </tr>
  <tr>
    <td height="50" valign="bottom">&nbsp;</td>
    <td><input class=button type=submit name=send value='<?php echo htmlspecialchars($button, ENT_QUOTES); ?>'></td>
  </tr>
</table>