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

  // ���������� SoftTime FrameWork
  require_once("config/class.config.php");
  // ������������� ���������� � ����� ������
  require_once("config/config.php");
  // ���������� ������� ���������
  require_once("utils.navigation.php");
  // ���������
  require_once("utils.title.php");

  try
  {
    // ���������� ������� ������
    $pagename = "����� �� ��������";
    $keywords = "����� �� ��������";
    require_once ("templates/top.php");

    // ��������� ��������
    echo title($pagename);

    ?>
     <form method=post>
     <input type="hidden" 
            name="id_parent" 
            value="<? echo $id_parent ?>">
     <table>
     <tr><td>
     <table width=100%>
     <tr class=main_txt>
     <td>����� :</td>
     <td><select type=text name=district>
        <option value='none' 
          <?php if($_POST['district'] == 'none')
             echo "selected"; ?>>
          �� ����� ��������</option>
        <option value='kanavinskii' 
          <?php if($_POST['district'] == 'kanavinskii')
                echo "selected"; ?>>
          �����������</option>
        <option value='nizhegorodskii' 
          <?php if($_POST['district'] == 'nizhegorodskii')
                echo "selected"; ?>>
          �������������</option>
        <option value='sovetskii' 
          <?php if($_POST['district'] == 'sovetskii') 
                echo "selected"; ?>>
          ���������</option>
        <option value='priokskii' 
          <?php if($_POST['district'] == 'priokskii')
                echo "selected"; ?>>
          ���������</option>
        <option value='moskovskii' 
          <?php if($_POST['district'] == 'moskovskii')
                echo "selected"; ?>>
          ����������</option>
        <option value='avtozavodskii' 
          <?php if($_POST['district'] == 'avtozavodskii')
                echo "selected"; ?>>
          �������������</option>
        <option value='leninskii' 
          <?php if($_POST['district'] == 'leninskii') 
                echo "selected"; ?>>
          ���������</option>
        <option value='sormovskii' 
          <?php if($_POST['district'] == 'sormovskii') 
                echo "selected"; ?>>
          ����������</option>
      </select></td></tr>
      <tr class=main_txt>
      <td>���������� ������ :</td>
      <td><select type=text name=rooms>
        <option value=0 
        <?php if($_POST['rooms'] == 0) echo "selected"; ?>>
        �� ����� ��������</option>
      <option value=1 
        <?php if($_POST['rooms'] == 1) echo "selected"; ?>>1</option>
      <option value=2 
        <?php if($_POST['rooms'] == 2) echo "selected"; ?>>2</option>
      <option value=3 
        <?php if($_POST['rooms'] == 3) echo "selected"; ?>>3</option>
      <option value=4 
        <?php if($_POST['rooms'] == 4) echo "selected"; ?>>4</option>
      <option value=5 
        <?php if($_POST['rooms'] == 5) echo "selected"; ?>>5</option>
      <option value=6 
        <?php if($_POST['rooms'] == 6) echo "selected"; ?>>6</option>
      </select></td></tr>
      <tr class=main_txt>
      <td>���� �����, ���. :</td>
      <td>�� <input type=text 
                name=price_min 
                value="<?= $_POST['price_min'] ?>"><br>
      �� <input type=text 
                name=price_max 
                value="<?= $_POST['price_max'] ?>"></td></tr>
      <tr class=main_txt>
      <td>���� �� ��.����, ���. :</td>
      <td>�� <input type=text name=pricemeter_min 
          value="<?php echo $_POST['pricemeter_min']; ?>"><br>
      �� <input type=text name=pricemeter_max 
          value="<?php echo $_POST['pricemeter_max']; ?>"></td></tr>
      </table>
   </td><td valign=top>
     <table width=100%>
     <tr class=main_txt>
     <td>���� :</td>
     <td><input type=text name=floor 
          value="<?php echo $_POST['floor']; ?>"></td></tr>
     <tr class=main_txt>
     <td>���. ���� :</td>
     <td><select type=text name=su>
      <option value='none' 
         <?php if($_POST['su'] == 'none') echo "selected"; ?>>
         �� ����� ��������
      <option value='separate' 
         <?php if($_POST['su'] == 'separate')
         echo "selected"; ?>>����������</option>
      <option value='combined' 
         <?php if($_POST['su'] == 'combined') 
         echo "selected"; ?>>�����������</option>
    </select></td></tr>
     <tr class=main_txt>
    <td>������/������ :</td>
    <td><select type=text name=balcony>
      <option value='none' 
         <?php if($_POST['balcony'] == 0) 
         echo "none"; ?>>�� ����� ��������</option>
      <option value='balcony' 
         <?php if($_POST['balcony'] == 'balcony') 
         echo "selected"; ?>>������</option>
      <option value='loggia' 
         <?php if($_POST['balcony'] == 'loggia') 
         echo "selected"; ?>>������</option>
    </select></td></tr>
    <tr class=main_txt>
    <td>�������������� :</td>
    <td><select type=text name=material>
    <option value='none' 
      <?php if($_POST['material'] == 'none') 
      echo "selected"; ?>>
      �� ����� ��������</option>
    <option value='brick' 
      <?php if($_POST['material'] == 'brick') 
      echo "selected"; ?>>���������</option>
    <option value='concrete' 
      <?php if($_POST['material'] == 'concrete')
      echo "selected"; ?>></option>
      ���������</option>
    <option value='reconcrete' 
      <?php if($_POST['material'] == 'reconcrete')
      echo "selected"; ?>>
      ����������</option>
    </select></td></tr>
    </table>
  </td>
  </tr><tr>
  <td colspan=2>
  <input class=buttonpoll type=submit value=������>
  </td></tr>
  </table>
  <input type=hidden name=search value=search>
  </form>
  <?php
    // C�����-���������� ���������� �������
    // �� �����
    if(isset($_POST['search']))
    {
      echo title("���������� ������");
      echo "<br>";
      // ���� ����� true, ���� ���� ���� �� ���� �������� ������
      $is_query = false;
      // ��������� ������� � ����� ���������� ������
      $tmp1 = $tmp2 = $tmp3 = $tmp3 = $tmp4 = $tmp5 = $tmp6 = $tmp7 = $tmp8 =
      $tmp9 = $tmp10 = $tmp11 = $tmp12 = $tmp13 = $tmp14 = $tmp15 = "";

      // �������� ������ �� SQL-��������
      if (!get_magic_quotes_gpc())
      {
        $_POST['district'] = mysql_escape_string($_POST['district']);
        $_POST['material'] = mysql_escape_string($_POST['material']);
      }
      $_POST['square_o_min'] = intval($_POST['square_o_min']);
      $_POST['square_o_max'] = intval($_POST['square_o_max']);
      $_POST['square_j_min'] = intval($_POST['square_j_min']);
      $_POST['square_j_max'] = intval($_POST['square_j_max']);
      $_POST['square_k_min'] = intval($_POST['square_k_min']);
      $_POST['square_k_max'] = intval($_POST['square_k_max']);
      $_POST['rooms']        = intval($_POST['rooms']);
      $_POST['floor']        = intval($_POST['floor']);
      $_POST['su']           = intval($_POST['su']);
      $_POST['price_min']    = intval($_POST['price_min']);
      $_POST['price_max']    = intval($_POST['price_max']);
      $_POST['pricemeter_min'] = intval($_POST['pricemeter_min']);
      $_POST['pricemeter_max'] = intval($_POST['pricemeter_max']);
      // �����
      if(!empty($_POST['district']) && $_POST['district'] != 'none') 
         $tmp1 = " AND district='$_POST[district]'";
      // �������
      if(!empty($_POST['square_o_min'])) 
         $tmp2 = " AND squareo > $_POST[square_o_min]";
      if(!empty($_POST['square_o_max'])) 
         $tmp3 = " AND squareo < $_POST[square_o_max]";
      if(!empty($_POST['square_j_min'])) 
         $tmp4 = " AND squarej > $_POST[square_j_min]";
      if(!empty($_POST['square_j_max'])) 
         $tmp5 = " AND squarej < $_POST[square_j_max]";
      if(!empty($_POST['square_k_min'])) 
         $tmp6 = " AND squarek > $_POST[square_k_min]";
      if(!empty($_POST['square_k_max'])) 
         $tmp7 = " AND squarek < $_POST[square_k_max]";
      // ���������� ������
      if(!empty($_POST['rooms'])) $tmp8 = " AND rooms=$_POST[rooms]";
      // ����
      if(!empty($_POST['floor'])) $tmp9 = " AND floor=$_POST[floor]";
      // ���. ����
      if(!empty($_POST['su']) && $_POST['su'] != 'none')
         $tmp10 = " AND su='".$_POST['su']."'";
      // ��������������
      if(!empty($_POST['material']) && $_POST['material'] != 'none')
        $tmp11 = " AND material='$_POST[material]'";
      // ����
      if(!empty($_POST['price_min'])) 
        $tmp12 = " AND price > $_POST[price_min]";
      if(!empty($_POST['price_max'])) 
        $tmp13 = " AND price < $_POST[price_max]";
      if(!empty($_POST['pricemeter_min'])) 
        $tmp14 = " AND pricemeter > $_POST[pricemeter_min]";
      if(!empty($_POST['pricemeter_max'])) 
        $tmp15 = " AND pricemeter < $_POST[pricemeter_max]";
      // ��������� ������ �� ���������� ������
      $query = "SELECT * FROM $tbl_cat_position 
                WHERE hide='show'
                ".$tmp11.$tmp1.$tmp2.$tmp3.
                  $tmp4.$tmp5.$tmp6.$tmp7.
                  $tmp8.$tmp9.$tmp10.$tmp12.
                  $tmp13.$tmp14.$tmp15." 
                ORDER BY pos";
      // ��������� SQL-������
      $pos = mysql_query($query);
      if(!$pos)
      {
        throw new ExceptionMySQL(mysql_error(), 
                                 $query,
                                "������ ��� ��������� � 
                                 ������� ����������� �����");
      }
      // ���������� ����� � ������ ������ ���� ������ ����
      if (mysql_num_rows($pos) > 0)
      {
        ?>
        <table width=100% 
               border=0 
               cellspacing=1 
               cellpadding=1>
         <tr class=stable_tr_ttl_clr>
           <td align=center class=stable_txt>���.����.</td>
           <td align=center class=stable_txt>�����</td>
           <td align=center class=stable_txt>�����</td>
           <td align=center class=stable_txt>(�)</td>
           <td align=center class=stable_txt>(�) </td>
           <td align=center class=stable_txt>(�)</td>
           <td align=center class=stable_txt>����</td>
           <td align=center class=stable_txt>��.����</td>
           <td align=center class=stable_txt>��������</td>
           <td align=center class=stable_txt>�/�</td>
           <td align=center class=stable_txt>������/������</td>
           <td align=center class=stable_txt>����,�.��.</td>
           <td align=center class=stable_txt>����, ���. </td>
           <td align=center class=stable_txt>������</td>
           <td align=center class=stable_txt>����.</td>
         </tr>
        <?
        $i = 0;
        while($position = mysql_fetch_array($pos))
        {
          // ���������� �����
          switch ($position['district'])
          {
            case 'kanavinskii':
              $distr = "�����������.";
              break;
            case 'nizhegorodskii':
              $distr = "�������������";
              break;
            case 'sovetskii':
              $distr = "���������";
              break;
            case 'priokskii':
              $distr = "���������";
              break;
            case 'moskovskii':
              $distr = "����������";
              break;
            case 'avtozavodskii':
              $distr = "�������������";
              break;
            case 'leninskii':
              $distr = "���������";
              break;
            case 'sormovskii':
              $distr = "����������";
              break;
          }
          // ���������� �������� ����
          $material = "����.";
          switch ($position['material'])
          {
            case 'brick':
              $material = "����.";
              break;
            case 'concrete':
              $material = "�����.";
              break;
            case 'reconcrete':
              $material = "�������.";
              break;
          }
          // ���������� ��� ���.����
          $su = "����.";
          switch ($position['su'])
          {
            case 'separate':
              $su = "����.";
              break;
            case 'combined':
              $su = "���.";
              break;
          }
          // ���������� ������� �������
          $balcony = "������";
          switch ($position['balcony'])
          {
            case 'balcony':
              $balcony = "������";
              break;
            case 'loggia':
              $balcony = "������";
              break;
          }
          if($i++ % 2) $class = "stable_tr_clr2";
          else $class = "stable_tr_clr1";
          echo "<tr class=\"$class\">
                  <td align=center class=stable_txt>
                    $position[rooms]</td>
                  <td class=stable_txt>
                    $distr</td>
                  <td class=stable_txt>
                    $position[address]</td>
                  <td align=center class=stable_txt>
                    $position[squareo]</td>
                  <td align=center class=stable_txt>
                    $position[squarej]</td>
                  <td align=center class=stable_txt>
                    $position[squarek]</td>
                  <td align=center class=stable_txt>
                    $position[floor]</td>
                  <td align=center class=stable_txt>
                    $position[floorhouse]</td>
                  <td align=center class=stable_txt>
                    $material</td>
                  <td align=center class=stable_txt>
                    $su</td>
                  <td align=center class=stable_txt>
                    $balcony</td>
                  <td align=center class=stable_txt>
                    $position[pricemeter]</td>
                  <td align=center class=stable_txt>
                    $position[price]</td>
                  <td align=center class=stable_txt>
                    $position[currency]</td>
                  <td align=center class=stable_txt>
                    $position[note]</td>
                </tr>";
        }
      }
      else echo "����� �� ��� �����������.
                 ���������� �������� �������� ������.";
    echo "</table>";
    }
    // ���������� ������ ������
    require_once ("templates/bottom.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require_once("exception_mysql_debug.php");
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