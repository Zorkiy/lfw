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
<table border=0 width=100%>
  <tr valign="bottom">
    <td><p class=menu><img src="<?php echo $skin; ?>images/newtema.gif" border="0" width="20" height="15"><a title="������� ����� ����" class=menu href=addtheme.php?id_forum=<?php echo $id_forum; ?>>�����&nbsp;����</a></td>
    <td><p class=menu><img src="<?php echo $skin; ?>images/enterforum.gif" border="0" width="20" height="15">&nbsp;<a title="������������������ �� ������" class=menu href=register.php?id_forum=<?php echo $id_forum; ?>>�����������</a></td>   
    <td><p class=menu><nobr><img src="<?php echo $skin; ?>images/enter.gif" border="0" width="20" height="15">&nbsp;<a title="���� �� �����" class=menu href=enter.php?id_forum=<?php echo $id_forum; ?>>����</a> /
        <a title="�����" class=menu href=exit.php?id_forum=<?php echo $id_forum;?>>�����</a></nobr></td>
    <td>
      <p class=menu>
        <img src="<?php echo $skin; ?>images/find.gif" border="0" width="20" height="15">
          <a title="����� �� �����" class=menu href=search.php?id_forum=<?php echo $id_forum;?>>����� �� �����</a>
          <a title="����� �� ������" class=menu href=srchform.php?id_forum=<?php echo $id_forum;?>>(�� ������)</a>
    </td>
    <?php
      // ���� ������ ������ ���
      //if ($showlisttopics)
      if(basename($_SERVER['PHP_SELF']) == 'read.php')
      { 
        // ���������� $id_theme � $id_theme_archive ������������ � utils/newpostslist.php
        if($id_theme > $id_theme_archive)
        {
          echo "<td><p class=menu><img src=".$skin."images/listforum.gif border=0 width=20 height=15>&nbsp;<a title='�������� ������ ��� ������' class=menu href=index.php?id_forum=".$id_forum."&page=".$page.">������&nbsp;���</a></td>";
        }
        else
        {
          echo "<td><p class=menu><img src=".$skin."images/listforum.gif border=0 width=20 height=15>&nbsp;<a title='�������� ������ ��� ������' class=menu href=archive.php?id_forum=".$id_forum."&page=".$page.">������&nbsp;���</a></td>";
        }
      }
    ?> 
    <td><p class=menu><img src="<?php echo $skin; ?>images/check.gif" border="0" width="20" height="15"><a title="�������� ��� ���� ������ ��� �����������" class=menu href=readall.php?id_forum=<?php echo $id_forum;?>>��������&nbsp;��</a></td>  
    
      <?php
        if($show_switch && $settings['show_struct_switch'] == 'yes') {
          ?>
            <td class="switchtypeforum"  align="center">
            <p class=texthelp><nobr>��� ������:</nobr><br>
            <nobr>
          <?php
          // ���� ���������� ���������� ���������� �������� �����
          if($lineforum)
          {   
            if($lineforumdown == "")
            {
            ?>
              <a href="setstruct.php?struct=1&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>&down=true" title="�������� ����� (����� ��������� ����)">
              <img src="<?php echo $skin; ?>images/lineforumup.gif" border="0" width="20" height="15" alt="�������� ����� (����� ��������� ����)"></a>
            <?php 
            }
            else
            {
            ?>
               <a href="setstruct.php?struct=1&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>" title="�������� ����� (����� ��������� �����)">
               <img src="<?php echo $skin; ?>images/lineforumdown.gif" border="0" width="20" height="15" alt="�������� ����� (����� ��������� �����)"></a>
            <?php 
            }
            ?>
            <a href="setstruct.php?struct=0&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>" title="����������� �����">
            <img src="<?php echo $skin; ?>images/structforumhide.gif" border="0" width="20" height="15" alt="����������� �����"></a><?
          }
          else
          {
          $imgstructforum = "structforum.gif";
          ?>
            <a href="setstruct.php?struct=1&id_forum=<?php echo $id_forum;?>&id_theme=<?php echo $id_theme;?>" title="�������� �����"><img src="<?php echo $skin; ?>images/lineforumuphide.gif" border="0" width="20" height="15" alt="�������� �����"></a>
            <img src="<?php echo $skin; ?>images/<? echo $imgstructforum ?>" border="0" width="20" height="15" alt="����������� �����">
          <?php } ?>
        </nobr>
       </td>
    <? } ?>
  </tr>
</table>