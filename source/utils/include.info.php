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
<table>
  <tr><td><p class="fieldname">���</td><td><p class=authortext><?php echo htmlspecialchars($author['name'], ENT_QUOTES);?></td></tr>
  <?php
    if(!empty($author['email']))
    {
      $pattern = "/^[-0-9a-z_\.]+@[0-9a-z_^\.]+\.[a-z]{2,6}$/i";
      if(preg_match($pattern, $author['email']))
      {
        echo "<tr>
                <td><p class=\"fieldname\">e-mail</td>
                <td><p class=text><a href=\"mail.php?id_forum=$id_forum&id_author=$author[id_author]\">�������� ������</a>
                </td>
              </tr>";
      }
      else
      {
        echo "<tr>
                <td><p class=\"fieldname\">e-mail</td>
                <td><p class=text>".htmlspecialchars($author['email'], ENT_QUOTES)."</td>
              </tr>";
      }
    }
  ?>
  <tr><td><p class="fieldname">URL</td><td><p class=text><a target="_blank" href="<?php echo is_http($author['url']);?>"><?php echo is_http($author['url']);?></a></td></tr>
  <tr><td><p class="fieldname">ICQ</td><td><p class=text>
  <?php
    $icq = $author['icq'];
    if(!empty($icq) && $icq != "-")
    {
      $icq = htmlspecialchars($icq, ENT_QUOTES);
      echo "<a href='http://www.icq.com/scripts/search.dll?to=$icq' 
               title='�������� � ��� ������� ����' target='_blank'> 
            <img src='http://wwp.icq.com/scripts/online.dll?icq=$icq&img=5'
                 width=18 height=18 border=0>$icq</a>";
    }
  ?></td></tr>
  <tr><td><p class="fieldname">� ����</td><td><p><?php echo nl2br(htmlspecialchars($author['about'], ENT_QUOTES));?></td></tr>
  <tr><td><p class="fieldname">���������� �����</td><td><p class=texthelp><?php echo htmlspecialchars($id_author, ENT_QUOTES); ?></td></tr>
  <tr><td><p class="fieldname">���������� ���������</td><td><p class=texthelp><?php echo $author['themes'];?></td></tr>
  <tr><td><p class="fieldname">��������� ���������</td><td><p class=texthelp><?php echo convertdate($author['time']); ?></td></tr>
  <tr>
    <td><p class="fieldname">��� ���� ������</td>
    <td><p class=texthelp>
      <a href=authorthmes.php?id_author=<?php echo $author['id_author']; ?>&id_forum=<?php echo htmlspecialchars($_GET['id_forum']);?>>����� �����</a>
      <a href=authorthmes.php?id_author=<?php echo $author['id_author']; ?>&id_forum=<?php echo htmlspecialchars($_GET['id_forum']);?>&arch=archiv>�����</a>&nbsp;&nbsp;
    </td>
  </tr>
  <?php
    if(!empty($author['photo']) && $author['photo'] != "-" && is_file($author['photo']))
    {
      // ���� ���� �� ������� ����� ������� ���
      if(filesize($author['photo'])) 
      {
        echo "<tr><td colspan=2><p class=fieldname align=center >����<br><br><img border=1 src='".$author['photo']."'></td></tr>";
      }
    }
  ?>
</table>