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

  // ������� ������� � ��������� ���������
  // ��������� ������������� ������, ��� � �������� ������ ���������,
  // ���������, ������������ � ������ �������, � � �������� ��������,
  // ����� ����� � ������� product
  $order = array();
  $order['roomsorder'] = "rooms";
  $order['districtorder'] = "district";
  $order['square_o_order'] = "square_o";
  $order['square_j_order'] = "square_j";
  $order['square_k_order'] = "square_k";
  $order['price_metr_order'] = "pricemeter";
  $order['price_order'] = "price";
  $order['floor_order'] = "floor";
  $order['floor_house_order'] = "floorhouse";
  $order['currency_order'] = "currency";
  $order['su_order'] = "su";
  $order['balcony_order'] = "balcony";
  $order['material_order'] = "material";
  // ��������� ��������� ���������� $strtmp, ������� �����
  // ������������ ��� ���������� ����������� SQL-������� ��� ����������
  // �������� ������� �� ������� product
  // �� ��������� ��������� �������� ������� �� ���� pos
  $strtmp = "pos";
  // ���� ����� �������� ������ ������� ������ ������ ��� ��������
  // ���������� �� ������ �� ����� ������� order �������� ��������
  // ��������� ���������� $strtmp
  foreach($order as $parametr => $field)
  {
    if(isset($_GET[$parametr]))
    {
      if($_GET[$parametr] == "up")
      {
        $_GET[$parametr] = "down";
        $strtmp = $field;
      } 
      else 
      {
        $_GET[$parametr] = "up";
        $strtmp = "$field DESC";
      }
    }
    else $_GET[$parametr] = "up";
  }
  // �������� �� ������� product
  $query = "SELECT * FROM $tbl_cat_position 
            WHERE id_catalog=$_GET[id_catalog] 
            ORDER BY $strtmp";
  $pos = mysql_query($query);
  if(!$pos)
  {
    throw new ExceptionMySQL(mysql_error(), 
                             $query,
                            "������ ��� ���������� 
                             ���������� �������� �������");
  }
  // ���������� ����� � ������ ������ ���� ������ ����
  if (mysql_num_rows($pos)>0)
  {
     // ��������� ������ � ������� ������� ����� ����������� ��������
     // ������� �� ��������� ����� �������
     $href = "catalog.php?id_catalog=$_GET[id_catalog]";
     echo "<table width=100% 
                  border=0 
                  cellspacing=1 
                  cellpadding=1><tr class=stable_tr_ttl_clr>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&roomsorder=$_GET[roomsorder]>���.����.</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&districtorder=$_GET[districtorder]>�����</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><span class=main_txt>�����</span></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&square_o_order=$_GET[square_o_order]>(�)</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&square_j_order=$_GET[square_j_order]>(�)</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&square_k_order=$_GET[square_k_order]>(�)</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&floor_order=$_GET[floor_order]>����</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&floor_house_order=".
                 "$_GET[floor_house_order]>��.����</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&material_order=$_GET[material_order]>���</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&su_order=$_GET[su_order]>�/�</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&balcony_order=$_GET[balcony_order]>�/�</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&price_metr_order=".
                 "$_GET[price_metr_order]>����,<br>�.��.</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&price_order=$_GET[price_order]>����, ���.</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><a class=main_txt 
                  href=$href&currency_order=$_GET[currency_order]>������</a></b>
             </td>
             <td align=center class=stable_txt>
               <b><span class=main_txt>����.</span></b>
             </td>
          </tr>";
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
        default: $distr="&nbsp";              
      }
      // ���������� �������� ����
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
        default: $material="&nbsp";               
      }
      // ���������� ��� ���.����
      switch ($position['su'])
      {
        case 'separate':
          $su = "���.";
          break;
        case 'combined':
          $su = "����.";
          break;
        default: $su="&nbsp";                         
      }
      // ���������� ������� �������
      switch ($position['balcony'])
      {
        case 'balcony':
          $balcony = "������";
          break;
        case 'loggia':
          $balcony = "������";
          break;
        default: $balcony="&nbsp";  
      }
      if($i++ % 2) $class = "stable_tr_clr2";
      else $class = "stable_tr_clr1";
      // ������� ������ �������
      echo "<tr class=\"$class\">
            <td align=center class=stable_txt>
              $position[rooms]
            </td>
            <td class=stable_txt>
              $distr
            </td>
            <td class=stable_txt>
              $position[address]
            </td>
            <td align=center class=stable_txt>
              $position[squareo]
            </td>
            <td align=center class=stable_txt>
              $position[squarej]
            </td>
            <td align=center class=stable_txt>
              $position[squarek]
            </td>
            <td align=center class=stable_txt>
              $position[floor]
            </td>
            <td align=center class=stable_txt>
              $position[floorhouse]
            </td>
            <td align=center class=stable_txt>
              $material
            </td>
            <td align=center class=stable_txt>
              $su
            </td>
            <td align=center class=stable_txt>
              $balcony
            </td>
            <td align=center class=stable_txt>
              $position[pricemeter]
            </td>
            <td align=center class=stable_txt>
              $position[price]
            </td>
            <td align=center class=stable_txt>
              $position[currency]
            </td>
            <td class=stable_txt>
              $position[note]
            </td>
          </tr>";
    }
    echo "</table>";
  }
?>