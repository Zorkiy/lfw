<?php
  ////////////////////////////////////////////////////////////
  // ����� - LiteForum
  // 2003-2008 (C) IT-������ SoftTime (http://www.softtime.ru)
  // ���������: http://www.softtime.ru/forum/
  // �������� �.�. (simdyanov@softtime.ru)
  // �������� �.�. (kuznetsov@softtime.ru)
  // ������� �.�. (softtime@softtime.ru)
  // ���������� �.�. (akira_bad@mail.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ 
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE); 

  // ���������� SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ������� ��� ������ �� ��������
  require_once("../utils/utils.time.php");
  // ������� ��� ������ � �����������
  require_once("../utils/utils.posts.php");
  // ��������� ������
  require_once("../utils/utils.settings.php");
  // ������� ��� ������ � ��������������
  require_once("../utils/utils.users.php");
  // ������� ��� ������ � �������
  require_once("../utils/utils.files.php");
  // ���������� ������������ ���������
  require_once("../utils/utils.pager.php");

  try
  {
    // ����� �������� ������
    $nameaction="����� �� ������";
    // ������� "�����" ��������
    require_once("../utils/topforumaction.php");
    if(!isset($_GET['logic'])) $_GET['logic'] = 1;
  ?>
  <p class=linkbackbig><a href=index.php?id_forum=<?php echo $_GET['id_forum']; ?>>���������</a></p>
  <div class=blockremark><p class=texthelp align=left>������� �������� ����� ��� ������,
    ��������� ��������� � ������� ������ "�����".<br>
    ������ "���" ��������, ��� � ����������� ������ ����� �� ����, ��� ����������� ���� �� ����
    �� ��������� ���� ����. ������ "�" ��������, ��� ����� ������� ������ �� ���������, ���
    ����������� ��� ��������� ���� ����� ������������.<br>
    �������� ����� ������������� �������� ���������, �.�. ���� �� ����� "������� �������", ���
    ������ ���� ���������� ����� ������ ������ ����� ���� "����� �����", ��� ��������� �����
    ���� "������� ���������", "�������� ����������" � �.�.<br>
    ������� ����� ������ ��������� ������ ��� ����� ��������, �.�. ����� "sms", "WAP", "���"
    ���������� �� �������, ��� ������� � ��������������� ������������� ������������ � ������
    ���� ������.
    </div>
  <form action=srch.php?id_forum=<?php echo $_GET['id_forum']; ?> method=get>
  <input type=hidden name=id_forum value=<?php echo $_GET['id_forum']; ?>>
  <table border="0">
    <tr>
      <td><p class="fieldname">�������� �����</td>
      <td><input class=input type=text name=name size=60 maxlength=200 value="<?php echo htmlspecialchars(stripslashes($_GET['name']),ENT_QUOTES); ?>"></td>
    </tr>
    <tr>
      <td><p class="fieldname">���������� <br>��������� ���</td>
      <td><input class=input type=text name=numberthemes size=3 maxlength=10 value=<?php if(empty($_GET['numberthemes'])) echo 30; else echo htmlspecialchars($_GET['numberthemes']); ?>></td>
    </tr>
    <tr>
      <td><p class="fieldname"><nobr>������ �...</nobr></td>
        <td>
          <select class=input type=text name=srchwhere>
             <option value=1 <?php if($_GET['srchwhere'] == 1) echo "selected"; ?>>� ��������� ���
             <option value=2 <?php if($_GET['srchwhere'] == 2) echo "selected"; ?>>� ����������
          </select>
       </td>
    </tr>
    <tr>
      <td><p class="fieldname"><nobr>������ � ������...</nobr></td>
        <td>
          <select class=input type=text name=id_forum>
            <?php
              if($_GET['id_forum'] == 0) $strtmp = "selected";
              else $strtmp = "";
              echo "<option value=0 $strtmp>�� ����� ��������";
              $query = "SELECT * FROM forums 
                        WHERE hide != 'hide' 
                        ORDER BY pos";
              $frm = mysql_query($query);
              if($frm)
              {
                while($forums = mysql_fetch_array($frm))
                {
                  if($forums['id_forum'] == $_GET['id_forum']) $strtmp = "selected";
                  else $strtmp = "";
                  echo "<option value=".$forums['id_forum']." $strtmp>".$forums['name'];
                }
              }
            ?>
          </select>
       </td>
    </tr>
    <tr>
      <td><p class="fieldname">������</td>
      <td><p>
          <input name=logic type=radio value=1 <?php if($_GET['logic'] == 1) echo "checked"; ?>>�&nbsp;&nbsp;&nbsp;
          <input name=logic type=radio value=0 <?php if($_GET['logic'] == 0) echo "checked"; ?>>���
      </td>
    </tr>
    <tr><td>&nbsp;</td><td><input class=button type=submit name=send value=�����></td></tr>
  </table>
  </form>
  <?php
    // ��������� ���������� ���������� ������� POST
    if(!preg_match("|^[\d]+$|",$_GET['numberthemes']) && !empty($_GET['numberthemes'])) exit("������������ ������ URL");
    if(!preg_match("|^[\d]+$|",$_GET['id_forum']) && !empty($_GET['id_forum'])) exit("������������ ������ URL");
    if(!preg_match("|^[\d]+$|",$_GET['page']) && !empty($_GET['page'])) exit("������������ ������ URL");
    if($_GET['numberthemes']>100) $_GET['numberthemes'] = 100;
    if (!get_magic_quotes_gpc())
    {
      $_GET['name']         = mysql_escape_string($_GET['name']);
      $_GET['numberthemes'] = mysql_escape_string($_GET['numberthemes']);
      $_GET['srchwhere']    = mysql_escape_string($_GET['srchwhere']);
      $_GET['logic']        = mysql_escape_string($_GET['logic']);
    }
    
    // ����� ��������� ����� ������������ 70 ���������
    $_GET['name'] = substr($_GET['name'],0,70);
    // ���������� ���� ������������ 6 �������
    $arr_words = explode(" ", $_GET['name'],7);
    if (count($arr_words) > 6) unset($arr_words[6]);
    $_GET['name'] = implode(" ", $arr_words);
    
    if(!isset($_GET['logic'])) $_GET['logic'] = 1;
  
    $name         = $_GET['name'];
    $numberthemes = $_GET['numberthemes'];
    $srchwhere    = $_GET['srchwhere'];
    $logic        = $_GET['logic'];
    $page         = $_GET['page'];
    $id_forum     = $_GET['id_forum'];
    if(!empty($name) && !empty($numberthemes))
    {
      if (!get_magic_quotes_gpc())
      {
        $name = mysql_escape_string($name);
      }
  
      if(empty($id_forum)) $id_forum = 0;
      if($id_forum === 0) $tmp_id_forum = "";
      else $tmp_id_forum = "AND id_forum = $id_forum ";
      // ������� ������������ ���������
      if(empty($page)) $page = 1;
      $start = ($page - 1)*$numberthemes;
  
      // ������������ � ���������� SQL-��������
      switch($srchwhere)
      {
        case 1: // ����� � ��������� ��� � �������
        {
          $name = trim($name);
          $temp = strtok($name," ");
          if($logic==0) $logstr = "or";
          else $logstr = "and";
          while ($temp)
          {
            // �������������� ����� � �������� name � authors
            if($is_query)
              $tmp1 .= " $logstr MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            else
              $tmp1 .= "MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            $is_query = true;
            $temp = strtok(" ");
		  $num_words ++;
          }       
          // ��������� SQL-������
          $query = "SELECT * FROM $tbl_themes 
                    WHERE ($tmp1)
                          $tmp_id_forum AND
                          hide != 'hide'
                    UNION
                    SELECT id_theme, 
                           name, 
                           author, 
                           id_author, 
                           last_author, 
                           id_last_author, 
                           hide, 
                           `time`, 
                           id_forum 
                    FROM $tbl_archive_themes 
                    WHERE ($tmp1)
                          $tmp_id_forum AND
                          hide != 'hide'
                          ORDER BY time DESC
                          LIMIT $start, $numberthemes";
          $thm = mysql_query($query);
  
          // �������� ����� ����� ���
          $query = "SELECT COUNT(*) FROM $tbl_themes
                    WHERE ($tmp1) 
                          $tmp_id_forum AND
                          hide != 'hide'";
          $tot = mysql_query($query);
          if(!$tot)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� ������");
          }
          $total = mysql_result($tot,0);
  
          // �������� ����� ����� ���
          $query = "SELECT COUNT(*) FROM $tbl_archive_themes
                    WHERE ($tmp1) 
                          $tmp_id_forum AND
                          hide != 'hide'";
          $tot = mysql_query($query);
          if(!$tot)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� ������");
          }
          $total += mysql_result($tot,0);
          break;
        }
        case 2: // �������������� ����� �� ����������
        {
          $name = trim($name);
          $temp = strtok($name," ");
          if($logic==0) $logstr = "or";
          else $logstr = "and";
          while ($temp)
          {
            // �������������� ����� � �������� name � authors
            if($is_query)
              $tmp1 .= " $logstr MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            else
              $tmp1 .= "MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
            $is_query = true;
            $temp = strtok(" ");
          }       
          // ��������� SQL-������
          $query = "SELECT id_theme FROM $tbl_posts
                    WHERE ($tmp1) AND
                          hide != 'hide' 
                    GROUP BY id_theme
                    UNION
                    SELECT id_theme FROM $tbl_archive_posts
                    WHERE ($tmp1) AND
                          hide != 'hide' 
                    GROUP BY id_theme";
          $post = mysql_query($query);
          if(!$post)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� ������� ��� ������");
          }
          $numtot = mysql_num_rows($post);
          if($numtot>0)
          {
            $query = "SELECT * FROM $tbl_themes WHERE id_theme IN (";
            $query_tot = "SELECT COUNT(*) FROM $tbl_themes WHERE id_theme IN (";
            $query_arch = "SELECT id_theme, name, author, id_author, last_author, id_last_author, hide, time, id_forum 
                           FROM $tbl_archive_themes WHERE id_theme IN (";
            $query_tot_arch = "SELECT COUNT(*) FROM $tbl_archive_themes WHERE id_theme IN (";
            while($posts = mysql_fetch_array($post))
            {
              $query .= $posts['id_theme'].","; 
              $query_tot .= $posts['id_theme'].",";
              $query_arch .= $posts['id_theme'].","; 
              $query_tot_arch .= $posts['id_theme'].","; 
            }
            $query .= "0) $tmp_id_forum AND
                              hide != 'hide' 
                      GROUP BY id_theme 
                      UNION
                      $query_arch 0) $tmp_id_forum AND
                              hide != 'hide'
                      GROUP BY id_theme 
                      ORDER BY time DESC
                      LIMIT $start, $numberthemes";
            $thm = mysql_query($query);
            $query_tot .= "0) $tmp_id_forum AND
                                   hide != 'hide'
                      GROUP BY id_theme
                      ORDER BY time DESC";
            $tot = mysql_query($query_tot);
            if(!$tot)
            {
              throw new ExceptionMySQL(mysql_error(), 
                                       $query,
                                      "������ ��� ������");
            }
            $total = mysql_num_rows($tot);
            $query_tot_arch .= "0) $tmp_id_forum AND
                                   hide != 'hide'
                      GROUP BY id_theme
                      ORDER BY time DESC";
            $tot = mysql_query($query_tot_arch);
            if(!$tot)
            {
              throw new ExceptionMySQL(mysql_error(), 
                                       $query,
                                      "������ ��� ������");
            }
            $total += mysql_num_rows($tot);
          }
          else
          {
            $tmp = explode(" ", $name);
            if(count($tmp) > 1)
            {
              foreach($tmp as $line) if(strlen($line)<4) exit("<p class=result>������ �������� �����, � ������ �������� ������ 4 - ���������� ��������� �� �� ������.</p>");
            }
            else
            {
              if(strlen($name)<4) exit("<p class=result>������ �������� �����, � ������ �������� ������ 4 - ���������� ��������� �� �� ������.</p>");
            }
            exit("<p class=result>� ���������, �� ������ ������� ������ �� �������. ���������� �������� ������� ������.</p>");
          }
          break;
        }
      }
      if($thm) // && $tot)
      {
        // �������� ���������� ���
        $numtot = mysql_num_rows($thm);
        if($numtot>0)
        {
          // ������ ������� � ������
          ?>
             <p class="zagtext">����������:</p>
             <table class=srchtable border="0" width="100%" cellpadding="4" cellspacing="1" >
                <tr class="tableheadern" align="center">
                  <td class="tableheadern"><p class=fieldnameindex><nobr>���-��</nobr> �����.</p></td>
                  <td class="tableheadern"><p class=fieldnameindex>�������� ����</p></td>
                  <td class="tableheadern"><p class=fieldnameindex>�����</p></td>
                  <td class="tableheadern"><p class=fieldnameindex>��������� ���������</p></td>
                </tr>
          <?php
          if($lasttime == "") $lasttime = date("Y-m-d H:i:s",time()-3600*2);
  
          // ��������� ��������� ���� ����, ������� ��������� � 
          // �������� �������
          $query = "SELECT id_theme FROM $tbl_archive_number LIMIT 1";
          $arh = mysql_query($query);
          if(!$arh)
          {
            throw new ExceptionMySQL(mysql_error(), 
                                     $query,
                                    "������ ��� ������");
          }
          if(mysql_num_rows($arh)) $id_theme_archive = @mysql_result($arh, 0);
          // ��� ����, ������� ����� ��������� ���� ���� $id_theme_archive
          // ��������� � ������, ���, ��� ���� - � "����� ������"
  
          while($themes = mysql_fetch_array($thm))
          {
            if($themes['id_theme'] > $id_theme_archive) $tbl = $tbl_posts;
            else $tbl = $tbl_archive_posts;
            // ������� ���������� ��������� � ������ ����.
            $query = "SELECT COUNT(*) FROM $tbl
                      WHERE id_theme = $themes[id_theme] AND hide != 'hide'";
            $pst = mysql_query($query);
            if(!$pst) 
            {
              throw new ExceptionMySQL(mysql_error(), 
                                       $query,
                                      "������ ��� �������� ���������� ��������� ����");
            }
            if(mysql_num_rows($pst)) $theme_count = mysql_result($pst,0);
            
            // ������� ����
            // �������������� ������������ ������� ������ � ������
            // ������������ ���� [b],[/b],[i] � [/i]
            $namet = theme_work_up($themes['name']);
            // ������� ������ ���
            if($page != "") $strpage = "&page=".$page;
  
            // ���������� ��������� � ����
            echo "<tr class=trtablen><td class=trtemaheight align=center><p class=nametema><nobr><a class=nametema href=read.php?id_forum=$themes[id_forum]&id_theme=$themes[id_theme]>$theme_count</nobr></p></td>";
            // ��������
            echo "<td><p><a target='_blank' href=read.php?id_forum=$themes[id_forum]&id_theme=$themes[id_theme]>$namet</a></td>";
            // �����
            if($themes['id_author'] != 0) echo "<td><p class=authorreg><a class=authorreg href=info.php?id_forum=$themes[id_forum]&id_author=$themes[id_author]>".htmlspecialchars($themes['author'])."</a></td>";
            else echo "<td><p class=author>".htmlspecialchars($themes['author'])."</td>";
            // ����� ���������� ���������� ����
            echo "<td><p class=texthelp>".substr($themes['time'],0,10)." � ".substr($themes['time'],10,6)."</p></td></tr>";
          }
          // ����� ������� �� ������ ��� ������
          // ������� ������ �� ������ ���� ������
          $page_link = 1;
          $number = (int)($total/$numberthemes);
          if((float)($total/$numberthemes)-$number != 0) $number++;
          $url = "&id_forum=$id_forum&name=".urlencode($name)."&numberthemes=$numberthemes&srchwhere=$srchwhere&logic=$logic";
          echo "<tr><td class=bottomtablen colspan=4><p class=texthelp>";
          pager($page, $total, $numberthemes, $page_link, $url);
          echo "</td></tr>";
        }
      }
    }
    // ������� ���������� ��������
    include "../utils/bottomforumaction.php";
  }
  catch(ExceptionObject $exc) 
  {
    require_once("exception_object_debug.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require_once("exception_member_debug.php"); 
  }
?>