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
  $title = '��������� ������ �� ����������';
  $pageinfo = '<p class=help>������ ������ ��������� ���������� 
               ������ �� ���������� ����������� ���������������� 
               ������ .htaccess � .htpasswd. ����� .htaccess � 
               .htpasswd, �������� ���������� ���������� �� 
               ��������� ����� �������.<br>
               PHP-������� ����� ���������� ��������� ��������� 
               ��� ���������� ���� � ������ ��� �����-����
               �����������, ������ ����� ������������� ������ 
               ���� ������������ ������ ������� ����, ��������,
               http://www.site.ru/����������_����������/</p>';

  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // �������� ��������� ��������
  require_once("../utils/top.php");
  // ������������� ���������� � FTP-��������
  require_once("../../config/ftp_connect.php");
  // ���������� ������� ��� ������ � 
  // ������� .htaccess � .htpasswd
  require_once("../utils/uitls.htfiles.php");

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
          <td align=center>������������</td>
          <td align=center colspan=3>��������</td>
        </tr>
    <?php
    $i = 0;
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

      $url = urlencode(str_replace("//","/",$directory."/".$file));
      $dir = str_replace("//","/",$directory."/".$file);
      if($acc[0] == 'd')
      {
        // ��������� ������� �� � ���������� 
        // ����� .htaccess � .htpasswd
        $flag = false;
        if(is_htaccess($ftp_handle, $dir))
        {
          $content = get_htaccess($ftp_handle, $dir);
          $flag = (strpos($content, "require") !== false) && 
                  (strpos($content, "valid-user") !== false);
        }
        if($flag)
        {
          $delete = "<p><a href=# 
            onClick=\"delete_position('pasdel.php?dir=$url',".
            "'�� ������������� ������ ����� ������ � ����������?');\"".
            ">����� ������</a></p>";
          $addcom = "<p><a href=pasglobset.php?dir=$url".
            "title=\"�������� ���������� ����������� �������� �����\"".
            ">�������� ����������� ��������</a></p>";
          if(is_htpasswd($ftp_handle, $dir))
          {
            $add = "<p><a href=pasadd.php?dir=$url ".
                   "title='�������� ��� ���� ������� � ������������ ".
                   "(��� ����������)'>�������� ������������</a></p>";
            // �������� ���������� .htpasswd ����� � 
            // ��������� ����� �������������
            $edit = "";
            $content = get_htpasswd($ftp_handle, $dir);
            $pattern = "#([^\n:]+):#";
            preg_match_all($pattern, $content, $out);
            if(!empty($out[1]))
            {
              foreach($out[1] as $user)
              {
                $edit_arr[] = "$user (<a title='�������� ��������� ������".
                " ������������' href=pasadd.php?dir=$url&name=".urlencode($user).
                ">������� ������</a>,".
                "<a title='������� ���������� ������������' ".
                "href=# onClick=\"delete_position('pasusrdel.php?dir=$url&name=".
                urlencode($user)."','�� ������������� ������ ������".
                " ������������ ������� � ������ ����������?')\">�������</a>)";
              }
            }
            if(!empty($edit_arr))
            {
              $users = implode("<br>", $edit_arr);
            }
            else $users .= "&nbsp";
          }
          else
          {
            $users .= "&nbsp";
            $add    = "<p><a href=pasadd.php?dir=$url 
              title='�������� ���������� ��������� �������'
              >�������� ��������� �������</a></p>";
            $addcom = "<p>���������� �������� ����������� ��������</p>";
          }
        }
        else
        {
          $delete = "<p>���������� �� ��������</p>";
          $add    = "<p><a href=pasadd.php?dir=$url 
             title='�������� ���������� ��������� �������'
             >�������� ��������� �������</a></p>";
          $users = "&nbsp;";
          $addcom = "<p><a href=pasglobset.php?dir=$url 
             title=\"�������� ���������� ���������� ������� �����\"
             >�������� ����������� ��������</a></p>";
        }
        // ����������
        $file   = "<b><a href=index.php?dir=$url 
                   title='������� ����������'>$file</a></b>";
        echo "<tr>
                <td align=right>$file</td>
                <td align=center>$users</td>
                <td align=center>$add</td>
                <td align=center>$delete</td>
                <td align=center>$addcom</td>
              </tr>";
      }
    }
    echo "</table><br><br>";
  }

  echo '<p class=help>����� �� ������ ����� ��������� ������ 
        ��� ������ �� ���������� ���������� �����, �������� 
        ���� ����� ���������� �����. � ���� ������ ������ 
        ����� ���������� ������ ��� ���� ���������� �����. 
        ����� ������ �������� � ����� .htpasswd �� ���� 
        ������� ���� �������� ���������� ������������ �����. 
        �������� ���������� ����������� �������� ����� ��� 
        ������ ����������� ������ "�������� ����������� ��������".<br><br>';
  echo '<a href=pasglobadd.php?dir='.$url.' 
           title="�������� ���������� ������ ��� ����� �����"
        >�������� ���������� ������</a><br><br>';

  // ��������� ����������� ����� .htpasswd �������� ����������
  $content = get_htpasswd($ftp_handle, "/");
  $pattern = "#([^\n:]+):#";
  preg_match_all($pattern, $content, $out);
  if(!empty($out[1]))
  {
    ?>
      <table width="100%" 
             class="table" 
             border="0" 
             cellpadding="0" 
             cellspacing="0">      
        <tr class="header" align="center">
          <td align=center>������������</td>
          <td align=center colspan=2>��������</td>
        </tr>
    <?php
    foreach($out[1] as $user_name)
    {
      echo "<tr>
          <td align=center>$user_name</td>
          <td align=center>
            <a title='�������� ������ ������������' 
                href=pasglobadd.php?dir=$url&name=".urlencode($user_name).
            ">������� ������</a>
          </td>
          <td align=center>
            <a title='������� ������������' 
               href=# 
               onClick=\"delete_position(".
              "'pasglobdel.php?dir=$url&name=".urlencode($user_name)."',".
              "'�� ������������� ������ ������� ������������".
              " �� ������ ���������� ������� �� ����?')\">�������</a></td>
        </tr>";
    }
    echo "</table>";
  }

  // ��������� ���������� � FTP-��������
  ftp_close($ftp_handle);

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>