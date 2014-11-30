<?php

class HlavniPrehled{
	private $db = NULL;

	public function __construct($db){
		$this->db = $db;	
	}
	
  /**
   * Zobrazi tabulka se vsemi daty
   * 
   * @param [$where] Uzivatelske filtry      
   */     
	public function vypis($where = ""){
    $wh = array();
    if(@$where['kdo'] != ""){
      $wh[] = "CONCAT(o.jmeno, ' ', o.prijmeni) LIKE :kdo";
      $values[':kdo'] = "%" . $where['kdo'] . "%";
    }
    if(@$where['firma'] != ""){
      $wh[] = "f.nazev LIKE :firma";
      $values[':firma'] = "%" . $where['firma'] . "%";
    }
    if(@$where['prichod'] != ""){
      $wh[] = "n.zadano >= :zadano";
      $values[':zadano'] = $where['prichod'];
    }
    if(@$where['za_kym'] != ""){
      $wh[] = "CONCAT(z.prijmeni, ' ', z.jmeno) LIKE :dozorce";
      $values[':dozorce'] = "%" . $where['za_kym'] . "%";
    }
    
    $im = imPlode(" AND ", $wh);
    if($im != ""){
      $im = "WHERE " . $im;
    }
    
		$sql = "
			SELECT 
				n.idn, n.ucel, f.nazev, 
				GROUP_CONCAT(CONCAT(o.jmeno, ' ', o.prijmeni) SEPARATOR '<br />') AS prichozi,
				CONCAT(z.prijmeni, ' ', z.jmeno) AS za_kym,  
				DATE_FORMAT(n.zadano, '%Y-%m-%d %H:%i') AS zadano, 
				DATE_FORMAT(n.odjezd, '%Y-%m-%d %H:%i') AS odjezd, 
				CONCAT(
					MOD(HOUR(TIMEDIFF(n.odjezd, n.zadano)), 24), 'h ',
					MINUTE(TIMEDIFF(n.odjezd, n.zadano)), 'min'
				) AS doba 
			FROM vr_osoby AS o 
			INNER JOIN vr_navstevy_osoby AS no  
			ON no.ido = o.ido  
			INNER JOIN vr_navstevy AS n 
			ON n.idn = no.idn  
			LEFT JOIN vr_firmy AS f 
			ON n.idf = f.idf  
			INNER JOIN zamestnanci AS z 
			ON n.zakym = z.idz
      $im
			GROUP BY idn  
		";
		$res = $this->db->query($sql, @$values);
		$output = array();
		while($row = $this->db->assoc($res)){
			$output[] = $row;
		}
			
		return $output;
	}
}