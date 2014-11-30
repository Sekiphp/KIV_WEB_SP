<?php
  require 'config.inc.php';
  require '../class/DB.class.php';
  $db = new DB();
  
  # uprava parametru dotazu dle stavu zadavani  
  $get = idNavstevy($_GET['data']); 
        
	$sql = "
		SELECT z.jmeno, z.prijmeni, z.telefon, z.email  
		FROM " . TABLE_ZAMESTNANCI . " AS z 
		INNER JOIN " . TABLE_NAVSTEVY. " AS n 
		ON n.zakym = z.idz 
		WHERE n.idn = ?
	";
	$res = $db->assoc($db->query($sql, array($get)));
	
	echo "<p>Za návštěvu odpovídá: <strong>" . $res['prijmeni'] . " " . $res['jmeno'] . "</strong></p>";
	echo "<ul>";
	echo "<li>Telefon: " . $res['telefon'] . "</li>";
	echo "<li>E-mail: " . $res['email'] . "</li>";
	echo "</ul>";
	
	