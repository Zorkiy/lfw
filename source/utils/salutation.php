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

  // ��������� ������
  require_once("../utils/utils.settings.php");
  // ������� ��� ������ � ��������������
  require_once("../utils/utils.users.php");

  // ��������� ��� ���������� �� cookie
  $new = $author_themes ="";
  if(isset($_COOKIE['current_author']))
  {
    $current_author = $_COOKIE['current_author'];
    $wrdp = $_COOKIE['wrdp'];
    if (!get_magic_quotes_gpc())
    {
      $current_author = mysql_escape_string($current_author);
      $wrdp = mysql_escape_string($wrdp);
    }

    // ���� ������ ��������� �������� - ���������
    // ������� �� ��� ������� ���������� ����� ���������
    // �������������� �������� �����������
    $query = "SELECT * FROM $tbl_authors 
              WHERE name = '$current_author' AND
                    passw = ".get_password($wrdp)." AND
                    statususer != 'wait'";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��������������");
    }
    // ���� ������� ������, �������������, ���������� ���������������
    if(mysql_num_rows($ath))
    {
      // ���� �������� ������ ��������� - ������� ������ �� ���
      if($settings['show_personally'] == 'yes')
      {
          $new = "<a href=personally.php>(������ ���������)</a>";
      }
      $author = mysql_fetch_array($ath);
      $author_themes = "(<a title=\"�������������� ���� ����\" href=authorthmes.php?id_author=$author[id_author]&id_forum=$_GET[id_forum]>��� ����</a>, ".
                       "<a title=\"��������� ����, � ������� �� ��������� �������\" href=authorlstthm.php?id_author=$author[id_author]&id_forum=$_GET[id_forum]>��������� ����</a>)";
    }
  }
  else
  {
    $current_author = "����������";
  }
?>
<p class=salutation><?php echo $settings['hello']." ".htmlspecialchars($current_author, ENT_QUOTES)."! $new $author_themes"; ?></p>