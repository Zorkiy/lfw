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

  // ���������� ��������� � ����
  function get_number_posts($id_theme, $lasttime = 0)
  {
    // ��������� ���������� � ������� ������ �����������
    global $tbl_posts;

    // ������������� SQL-��������
    $id_theme = intval($id_theme);

    // ���� ������� ����� ���������� ��������� - ����������
    // ������ ����� ���������
    if(!empty($lasttime)) $where = "'$lasttime' < `time` AND";
    else $where = "";
    // ������������ ���������� ����� ��������� � �������
    // ����
    $query = "SELECT COUNT(*) FROM $tbl_posts
              WHERE id_theme=$id_theme AND
                    $where
                    hide != 'hide'";
    $tim = mysql_query($query);
    if(!$tim)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� �������� 
                                ���������� ���������");
    }
    if(mysql_num_rows($tim)) return @mysql_result($tim, 0);
    else return 0;
  }
  function theme_work_up($theme)
  {
    // ������������ ���� [b],[/b],[i] � [/i]
    $theme = preg_replace_callback(
              "|([a-z�-�\d!]{35,})|i",
              "split_text",
              $theme);
    $theme = htmlspecialchars($theme);
    $theme = preg_replace("#\[b\](.+)\[\/b\]#iU",'<b>\\1</b>',$theme);
    $theme = preg_replace("#\[i\](.+)\[\/i\]#iU",'<i>\\1</i>',$theme);
    $theme = str_replace("`","'",$theme);
    return $theme;
  }
  function post_work_up($postbody)
  {
    $postbody = preg_replace_callback(
              "|([a-z�-�\d!]{35,})|i",
              "split_text",
              $postbody);
    // ������������ ���� [code], [/code]
    $text = "[code]";
    $lastocc = 0;
    $sndocc = 1;
    $result = "";
    while($sndocc)
    {
      $fstocc = strpos($postbody,"[code]",$lastocc);
      $sndocc = strpos($postbody,"[/code]",$fstocc);
      if(($fstocc>0 && $sndocc>0 && $lastocc>0) || ($fstocc >= 0 && $sndocc>0 && $lastocc == 0))
      {
        $result .= nl2br(htmlspecialchars(substr($postbody,$lastocc,$fstocc - $lastocc)));
        if(strpos($_SERVER['PHP_SELF'], "cpp") !== false) $result .= "<table border=0 ><tr><td class=codeblock>".cpp_highlight(substr($postbody,$fstocc + 6,$sndocc - $fstocc - 6),true)."</td></tr></table>";
        else $result .= "<table border=0 ><tr><td class=codeblock>".highlight_string(substr($postbody,$fstocc + 6,$sndocc - $fstocc - 6),true)."</td></tr></table>";
        $lastocc = $sndocc + 7;
      }
      else
      {
        $result .= nl2br(htmlspecialchars(substr($postbody,$lastocc,strlen($postbody)-$lastocc)));
        break;
      }
    }
    $postbody = $result;
    // ��������
    $dirname = '../skins/'.$GLOBALS['skin'].'smiles';
    if (file_exists($dirname))
    {
      $postbody = str_replace("[:","<img align=middle src=../skins/".$GLOBALS['skin']."smiles/",$postbody);
      $postbody = str_replace(":]",".gif />",$postbody);
    }    
    // ����
    $postbody = preg_replace("#\[b\](.+)\[\/b\]#isU",'<b>\\1</b>',$postbody);
    $postbody = preg_replace("#\[i\](.+)\[\/i\]#isU",'<i>\\1</i>',$postbody);
    $postbody = preg_replace("#\[url\][\s]*((?=http:)[\S]*)[\s]*\[\/url\]#si",'<a href="\\1" target=_blank>\\1</a>',$postbody);
    $postbody = preg_replace("#\[url\][\s]*((?=https:)[\S]*)[\s]*\[\/url\]#si",'<a href="\\1" target=_blank>\\1</a>',$postbody);
    $postbody = preg_replace("#\[url\][\s]*((?=ftp:)[\S]*)[\s]*\[\/url\]#si",'<a href="\\1" target=_blank>\\1</a>',$postbody);
    $postbody = preg_replace("#\[url[\s]*=[\s]*((?=http:)[\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU",
                             '<a href="\\1" target=_blank>\\2</a>',
                             $postbody);
    return $postbody;
  }
  function cpp_highlight($document)
  {
    // ����������� ������� ������, ��� ����������� HTML-�����
    $document = str_replace(array("<",">"," "), array("&lt;","&gt;","&nbsp;"), $document);
    // ����������� ��������� �������������
    $document = preg_replace("'([#][^\n]+)\n'si",
                             "<font color=#007700>\\1</font>",
                             $document);
    // ����������� �����������
    $document = preg_replace("'(//[^\n]*)\n'si",
                             "<font color=#FF8000>\\1</font>",
                              $document);
    $document = preg_replace("'/\*(.*?)\*/'si",
                             "<font color=#FF8000>/*\\1*/</font>",
                              $document);
    // ������������ �������� �����
    $document = nl2br($document); //preg_replace("'(\n)'si","<br>\\1", $document);
    // ����������� ������ ����������� � ��������� � ������� �������
    $str = array("'(\".*?(?<!\\\)\")'si",
                   "'(\'.*?(?<!\\\)\')'si");
    $replace = array("<font color=#DD0000>\\1</font>",
                     "<font color=#DD0000>\\1</font>");
    $document = preg_replace($str, $replace, $document);
    // ����������� ����������������� �����
    $str = array("|\basm\b|si",
                 "|\bauto\b|si",
                 "|\bbool\b|si",
                 "|\bbreak\b|si",
                 "|\bcase\b|si",
                 "|\bcatch\b|si",
                 "|\bchar\b|si",
                 "|\bclass\b|si",
                 "|\bconst\b|si",
                 "|\bconst_cast\b|si",
                 "|\bcontinue\b|si",
                 "|\bdefault\b|si",
                 "|\bdelete\b|si",
                 "|\bdo\b|si",
                 "|\bdouble\b|si",
                 "|\bdynamic_cast\b|si",
                 "|\belse\b|si",
                 "|\benum\b|si",
                 "|\bexplicit\b|si",
                 "|\bexport\b|si",
                 "|\bextern\b|si",
                 "|\bfalse\b|si",
                 "|\bfloat\b|si",
                 "|\bfor\b|si",
                 "|\bfriend\b|si",
                 "|\bgoto\b|si",
                 "|\bif\b|si",
                 "|\binline\b|si",
                 "|\bint\b|si",
                 "|\blong\b|si",
                 "|\bmutable\b|si",
                 "|\bnamespace\b|si",
                 "|\bnew\b|si",
                 "|\boperator\b|si",
                 "|\bprivate\b|si",
                 "|\bprotected\b|si",
                 "|\bpublic\b|si",
                 "|\bregister\b|si",
                 "|\breinterpret_cast\b|si",
                 "|\breturn\b|si",
                 "|\bshort\b|si",
                 "|\bsigned\b|si",
                 "|\bsizeof\b|si",
                 "|\bstatic\b|si",
                 "|\bstatic_cast\b|si",
                 "|\bstruct\b|si",
                 "|\bswitch\b|si",
                 "|\btemplate\b|si",
                 "|\bthis\b|si",
                 "|\bthrow\b|si",
                 "|\btrue\b|si",
                 "|\btry\b|si",
                 "|\btypedef\b|si",
                 "|\btypeid\b|si",
                 "|\btypename\b|si",
                 "|\bunion\b|si",
                 "|\bunsigned\b|si",
                 "|\busing\b|si",
                 "|\bvirtual\b|si",
                 "|\bvoid\b|si",
                 "|\bvolatile\b|si",
                 "|\bwchar_t\b|si",
                 "|\bwhile\b|si");
    $replace = array_fill(0,
                          count($str),
                          "<font color=#0000CC>\\0</font>");
    $document = preg_replace($str, $replace, $document);
    // ����������� �������
    $document = preg_replace ("'([\w]+)([\s]*)[\(]'si",
                              "<font color=#0000CC>\\1</font>\\2(",
                              $document);
  
    // ���������� ��������� ������ �������
    return "<code>$document</code>";
  }
  function split_text($matches) 
  {
    return wordwrap($matches[1], 35, ' ',1);
  }
  // �������� ������� http:\\
  function is_http($text)
  { 
    $text = htmlspecialchars($text);
    if(trim($text) != "")
    if(substr(trim($text),0,5) != "http:" && substr(trim($text),0,4)!="ftp:") $text = "http://".trim($text);
    return trim($text);
  }

  // ������� ������������ ������ ��������� ��� ��������� � MySQL
  // 1. ($id_parent) - ������������� ����� ��������
  // 2 ($id_theme)  - ������������� ����
  // 3 ($post_arr)  - ����������� ������ ��������� ������
  //                  ���� ������� - ������������� ����� (id_post)
  //                  ������ 2 ������ - ���� �������� ����� �� ������ ���� ������
  // 4 ($post_par)  - ����������� ������ ������������
  //                  id_parent � ����������� � ���� ������
  //                  ���� ������� - id_parent (id_post ��������)
  //                  ������ 2 ������ - �������������� ������, ����������� � id_parent
  // 5 ($indent)  -   ������
  // 6 ($last_time) - ����� ���������� ��������� ��� �����������
  // 					����������� ����� ���������
  // 7 ($current_author) - ��� �������� ������
  // 8 ($id_forum) - 	������� �����
  // 9 ($lineforum) - ��� ������
  // 10 ($lineforumdown) - ����������� ���������� (��� ��������� ������)
  // 11 ($skin) - ���� � ����� �� ������
  // 12 ($themehide) - ������ ����
  // 13 ($tbl_posts) - ��� ������� ���� ������ ��� ������
  // 14 ($tbl_themes) - ��� ������� ���� ������ ��� ���

  function putpost_arr($id_parent,
				 $id_theme,
				 &$post_arr,
				 &$post_par,
                 $indent,
                 $last_time,
                 $current_author,
                 $id_forum,
                 $lineforum,
                 $lineforumdown,
                 $skin,
                 $themehide)
  {
    // ��������� ����� ������ �����������
    global $tbl_posts, $tbl_themes;
  
    // ���� � ������� ����������� ���������� $lineforum ������� �������� �����
    if(!empty($lineforum))
    {
      // ���� ���������� ���������� $lineforumdown ��������� ��������� �� ������ � �����
      if(!empty($lineforumdown)) $sort_msg = "";
      else $sort_msg = "DESC";
      $query = "SELECT * FROM $tbl_posts
                WHERE id_theme = $id_theme AND
                      hide != 'hide'
                ORDER BY time $sort_msg";
      $pst = mysql_query($query);
      if(!$pst)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                 "������ ��� ���������� ���������");
      }
      if(mysql_num_rows($pst))
      {
        while($posts = mysql_fetch_array($pst))
        {
          // �������� ������ ����������� ���������, �� ������� �������� ����������
          $parent_author = "";
          if($posts['parent_post'] != 0)
          {
            $query = "SELECT author, time, id_author
                      FROM $tbl_posts
                      WHERE id_post = $posts[parent_post]";
            $auth = mysql_query($query);
            if(!$auth)
            {
               throw new ExceptionMySQL(mysql_error(), 
                                        $query,
                                       "������ ��� ��������� � ����");
            }
            if(mysql_num_rows($auth)>0)
            {
              $parent = mysql_fetch_array($auth);
              $parent_author = $parent['author'];
              $parent_time = $parent['time'];
              $parent_id_author = $parent['id_author'];
            }
          }
  
          post_down_arr($posts['id_post'],
                    $id_theme,
                    $indent,
                    $last_time,
                    $current_author,
                    $id_forum,
                    $posts['id_author'],
                    $posts['author'],
                    $posts['time'],
                    $posts['putfile'],
                    $posts['name'],
                    $posts['url'],
                    $skin,
                    $themehide,
                    $parent_author,
                    $parent_time,
                    $parent_id_author,
                    $count_answer,
                    strtotime($posts['time'])
                    );
        }
      }
    }
    // ����� ����������� �����
    else
    {
      if(count($post_par[$id_parent]))
      {
        foreach($post_par[$id_parent] as $id_post_tmp)
        {
          $posts = $post_arr[$id_post_tmp];
          // ������� ����������� ���������
          $count_answer = count($post_par[$id_post_tmp]);
          // ����� ���������
          post_down_arr($id_post_tmp,
                    $id_theme,
                    $indent,
                    $last_time,
                    $current_author,
                    $id_forum,
                    $posts['id_author'],
                    $posts['author'],
                    $posts['time'],
                    $posts['putfile'],
                    $posts['name'],
                    $posts['url'],
                    $skin,
                    $themehide,
                    $parent_author,
                    $parent_time,
                    $parent_id_author,
                    $count_answer,
                    strtotime($posts['time'])
                    );
          // ���� � ������� ���������� ���������
          $num_rows=count($post_par[$id_post_tmp]);
          if ($num_rows>0)
          {
            $shap_indent=3;
            if ($num_rows*$shap_indent>350) $shap_indent=2;
            // ��������� ������
            if($indent<50) $temp = ($shap_indent + $indent*(95)/100);
            else $temp = (5 + $indent*(100 - $indent)/100);
  
            putpost_arr($id_post_tmp,
                    $id_theme,
                    $post_arr,
                    $post_par,
                    $temp,
                    $last_time,
                    $current_author,
                    $id_forum,
                    $lineforum,
                    $lineforumdown,
                    $skin,
                    $themehide);
          }
        }
      }
    }
  }
  // ������� ������ ����� �� ��������
  function post_down_arr($id_post,
                   $id_theme,
                   $indent,
                   $last_time,
                   $current_author,
                   $id_forum,
                   $id_author,
                   $author,
                   $time,
                   $file,
                   $name,
                   $puturl,
                   $skin,
                   $themehide,
                   $parent_author,
                   $parent_time,
                   $parent_id_author,
                   $count_answer,
                   $post_time)
  {
    // ��������� ����� ������ �����������
    global $tbl_posts, $tbl_themes;
    // ������ � ����������� ��������� ����������
    global $settings;
    // ������� ��������� - ��� ������ ��������� - ���� �������
    echo "<tr><td>";
    // ���� ��������� ����� ������� new
    if($last_time == "") $last_time = date("Y-m-d H:i:s",time()-3600*2);
    if($last_time < $time)
    {
      $new = "<img src={$skin}images/new.gif border=0>";
      $style_postbody=" class=postbodynew ";
    }  
    else 
    {
      $new = "";
      $style_postbody=" class=postbody ";
    }
    $time = convertdate($time, 0);
    $last_time = convertdate($last_time, 0);
    $parent_time = convertdate($parent_time, 0);
    echo "<table border=0 width=100% $style_postbody cellpadding=3 cellspacing=0>";
    // ������� ��������� ���������: ���, ����� �������� ���������

    // ���� ���� ������������ ����(�������) ��������� ������ �� ����
    $writefile = "";
    if($file != "" && $file != "-" && is_file($file))
    {
      // ���� ���� �� ������� ����� ����� �� ���� ������
      if(filesize($file)) $writefile = "<div class=attachfile><a target=_blank href={$file}><img border=0 src={$skin}images/flopy.gif title='������� ����'></a>".getfilesize($file)."</div>";
    }

    if($settings['show_personally'] == 'yes')
    {
      $personally = "<a href=addper.php?id_forum=$id_forum&id_theme=$id_theme&id_author=$id_author>������ ���������</a>";
    }
    else
    {
      $personally = "<a href=mail.php?id_forum=$id_forum&id_theme=$id_theme&id_author=$id_author>������ ������</a>";
    }

    // ������� ������
    if($id_author != 0)
    {
      // ���� id_author �� ����� 0 ������ ����� ��������������� - ����� ������ �� ���� ������
      echo "<tr>
              <td width='".$indent."%'>&nbsp;$new</td>
              <td class=infopost><div style='float: left'>&nbsp;�����:
                  <a class=authorreg href=info.php?id_forum=$id_forum&id_author=$id_author>".htmlspecialchars($author, ENT_QUOTES)."</a>&nbsp;&nbsp;&nbsp;($time)&nbsp;&nbsp;&nbsp;$personally</div>$writefile</td>
              <td class=infopost width=50>&nbsp;</td>
              </tr>";
    }
    else
    {
      // �������������������� �����
      echo "<tr>
              <td width='".$indent."%'>&nbsp;$new</td>
              <td class=infopost><div style='float: left'>&nbsp;�����: <em class=author>".htmlspecialchars($author, ENT_QUOTES)."</em>&nbsp;&nbsp;&nbsp;($time)</div>$writefile</td>
              $parent_author_text
              <td class=infopost width=50>&nbsp;</td>
            </tr>";
    }

    // ������� ����, ���� �������� �����
    $parent_author_text = "";
    if(!empty($parent_author))
    {
       if($parent_id_author != 0)
       {
         $parent_author_text = "<tr>
                                <td width='".$indent."%'>&nbsp;</td>   
                                <td colspan=2 class=toauthor>&nbsp;<b>to: ".htmlspecialchars($parent_author, ENT_QUOTES)."</b>
                                  &nbsp;&nbsp;(".$parent_time.")
                                </td>
                                </tr>";
       }
       else
       {
         $parent_author_text = "<tr>
                                <td width='".$indent."%'>&nbsp;</td>   
                                <td colspan=2 class=toauthor>&nbsp;<b>to: ".htmlspecialchars($parent_author, ENT_QUOTES)."</b>
                                    &nbsp;&nbsp;(".$parent_time.")
                                </td></tr>";
       }
       echo $parent_author_text;
    }

    // ������� ���� ���������
    // ���� ��������� url - �������
    $tmpurl = is_http(strip_tags($puturl));
    if($puturl != "" && $puturl != "-") $url = "<br><br><a href=$tmpurl target=_blank>$tmpurl</a>";
    else $url = "";

    if(basename($_SERVER['PHP_SELF']) == 'personallyread.php') $personally = "&personally=set";
    else $personally = "";

    // ���� �� ��������� ��� ������� � ������� ������������ �������� ���
    // ������� - ��������� ��� ������������� ��� ���������.
    $edit = "";
    if($count_answer==0)
    {
      if(strtolower($author)==strtolower($current_author))
      {
        $edit = "<img src='{$skin}images/editpen.gif' border='0' width='20' height='15' ><a href=editpost.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post{$personally} title='������������� ���������'>�������&nbsp���������</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
    }
	
    // ������������ ����� �����
    $postbody = post_work_up($name);
    // ��������� ������ ��� ������
    if($themehide != 'lock') $answer = "<td class=postmenu>$edit<img src='{$skin}images/pen.gif' border='0' width='20' height='15'><a href=addpost.php?id_forum=$id_forum&id_theme=$id_theme&id_post=$id_post{$personally}>��������</a></td>";
    else $answer = "";
    // ������� ���� ���������
    echo "<tr valign=top><td width='{$indent}%'>&nbsp;</td><td><p class=posttext>$postbody{$url}</p></td><td></td></tr>";
    echo "<tr>
            <td width='{$indent}%'>&nbsp;</td>
            $answer
            <td>&nbsp;</td></tr>";
    echo "</table>";
    echo "</td></tr>";

  }
?>