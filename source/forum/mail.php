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
  Error_Reporting(E_ALL & ~E_NOTICE); 

  // ���������� SoftTime FrameWork
  require_once("../config/class.config.forum.php");
  // ������������� ���������� � ����� ������
  require_once("config.php");
  // ������� ��� ������ � �����������
  require_once("../utils/utils.posts.php");
  // ������� ��� ��������� �������
  require_once("../utils/utils.time.php");

  try
  {
    // ��������� �������� ���������� �� ������ �������
    $id_forum  = intval($_GET['id_forum']);
    $id_theme  = intval($_GET['id_theme']);
    $id_author = intval($_GET['id_author']);
    // ��������� ������ ������ �� ���� ������
    $query = "SELECT * FROM $tbl_authors 
              WHERE id_author = $id_author";
    $ath = mysql_query($query);
    if(!$ath)
    {
       throw new ExceptionMySQL(mysql_error(), 
                                $query,
                               "������ ��� ����������
                                ���������� ������");
    }
    if(mysql_num_rows($ath))
    {
      $author = mysql_fetch_array($ath);
      if(trim($author['email']) == "" || $author['email'] == "-") 
      {
        $error[] = "� ������� ������ ����������� ����������� �����";
      }
    }

    if(!empty($_POST))
    {
      // ���������� HTML-�����
      define("MAIL", 1);
      $error = array();
      require_once("../utils/include.mail.handler.php");
    }

    // ������������� �������� ��������  
    $nameaction="�������� ������";
    // ������� "�����" ��������
    include "../utils/topforumaction.php"; 

    if (empty($return)) $return = $_SERVER["HTTP_REFERER"];  
    ?>
    <p class=linkbackbig><a href='<? echo $return ?>'>��������� � �����</a></p>
    <?php
    // ���� ������� ������ ������� ��
    if(!empty($error))
    {
      echo "<div class=fieldname style='color:red'>".implode("<br>", $error)."</div><br>";
    }
    ?>
<form action=mail.php method=post>
<table>
<tr><td><p class="fieldname">����:</td><td><input class=input type=text name=theme maxlength=200 size=61></td></tr>
<tr><td><p class="fieldname">���������:</td><td><textarea class=input cols=63 rows=10 name=message></textarea></td></tr>
<tr><td>&nbsp;</td><td><input class=button type=submit name=send value=���������></td></tr>
<input type=hidden name=id_author value=<? echo $id_author; ?>>
<input type=hidden name=id_theme value=<? echo $id_theme; ?>>
<input type=hidden name=id_forum value=<? echo $id_forum; ?>>
</table>
</form>
<?php
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
