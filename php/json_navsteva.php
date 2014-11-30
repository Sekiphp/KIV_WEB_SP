<?php
  require 'config.inc.php';
  require '../class/DB.class.php';
  $db = new DB();
  
  # uprava parametru dotazu dle stavu zadavani  
  $get = $_GET['data']; 

	$sql = "
		SELECT DISTINCT f.nazev, f.spz, o.cop, CONCAT(o.jmeno,  ' ', o.prijmeni) AS pretty
		FROM vr_osoby AS o
		INNER JOIN " . TABLE_NAVSTOS . " AS no ON no.ido = o.ido
		INNER JOIN " . TABLE_NAVSTEVY . " AS n ON n.idn = no.idn
		LEFT JOIN " . TABLE_FIRMY . " AS f ON n.idf = f.idf
		WHERE 
			CONCAT(o.jmeno, ' ', o.prijmeni) LIKE :like
			OR CONCAT(o.prijmeni, ' ', o.jmeno) LIKE :like	
	";
  
  $res = $db->query($sql, array(":like" => '%' . $get . '%'));
 
  # vystupem je JSON s daty
  $json = array();
  while($row = $db->assoc($res)) {
  	$nazevf = ($row['nazev'] != "") ? "(" . $row['nazev'] . ")" : "";
    $json[] = array(
      'label' => $row['pretty'] . " " . $nazevf,
      'pretty' => $row['pretty'],
      'firma' => $row['nazev'], 
      'spz' => $row['spz'], 
      'cop' => $row['cop'],
    );
  }
 
  echo json_encode($json);