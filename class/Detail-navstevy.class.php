<?php
class Detail{
	private $db = NULL;
  private $idn;

	public function __construct($db, $idn){
		$this->db = $db;
    $this->idn = $idn;	
	}
	
  /**
   * Zobrazi tisknutelnou tabulku o navsteve - na format A5
   */     
	public function tabulkaDetail(){
		$sql = "
			SELECT 
				n.idn, n.ucel, f.nazev, f.spz,  
				GROUP_CONCAT(CONCAT(o.jmeno, ' ', o.prijmeni) SEPARATOR '<br />') AS prichozi,
				CONCAT(z.prijmeni, ' ', z.jmeno) AS za_kym,  
				DATE_FORMAT(n.zadano, '%d.%m.%Y %H:%i') AS zadano,
				DATE_FORMAT(n.uvolneno, '%d.%m.%Y %H:%i') AS uvolneno, 
				DATE_FORMAT(n.odjezd, '%d.%m.%Y %H:%i') AS odjezd  
			FROM " . TABLE_OSOBY . " AS o 
			INNER JOIN " . TABLE_NAVSTOS . " AS no  
			ON no.ido = o.ido  
			INNER JOIN " . TABLE_NAVSTEVY . " AS n 
			ON n.idn = no.idn  
			LEFT JOIN " . TABLE_FIRMY . " AS f 
			ON n.idf = f.idf  
			INNER JOIN " . TABLE_ZAMESTNANCI . " AS z 
			ON n.zakym = z.idz 
      WHERE n.idn = :idn
			GROUP BY idn  
		";
		$res = $this->db->query($sql, array(":idn" => $this->idn));
    
  	$output = array();
  	while($row = $this->db->assoc($res)){
  		$output[] = $row;
  	}   			
  	return $output;
	}
  
  /**
   * Zobrazi vsechny poznamky k dane navsteve
   * 
   * @return FALSE pri neuspechu nebo data            
   */     
  public function vypisPoznamek(){
    $sql = "
      SELECT p.text, p.zadano, CONCAT(z.jmeno, ' ', z.prijmeni) AS kdo 
      FROM " . TABLE_POZNAMKY . " AS p 
      INNER JOIN " . TABLE_ZAMESTNANCI . " AS z 
      ON p.zadal = z.idz 
      WHERE idn = :idn
    ";     
    $res = $this->db->query($sql, array(":idn" => $this->idn));
    
  	$output = array();
  	while($row = $this->db->assoc($res)){
  		$output[] = $row;
  	}   			
  	return $output;
  }
  
  /**
   * Zpracovani kliknuti na talcitko, ze zacinaji odchazet
   */	 	 	   
  public static function navstevaOdchazi($idn, $db){
    $sql = "SELECT uvolneno FROM " . TABLE_NAVSTEVY . " WHERE idn = ?";
    $res = $db->assoc($db->query($sql, array($idn)));
    
    if($res['uvolneno'] != NULL){
      return FALSE;
    }
    else{
    	$sql = "UPDATE " . TABLE_NAVSTEVY . " SET uvolnil = ?, uvolneno = NOW() WHERE idn = ?";
    	$res = $db->query($sql, array($_SESSION['idz'], $idn));
    	
    	return $res;
    }
	}
	
	/**
	 * Tlacitko pro vratneho, ze lidi odesli zcela z arealu
	 */	 	
	public static function navstevaOdesla($idn, $db){
    $sql = "UPDATE " . TABLE_NAVSTEVY . " SET odjezd = NOW() WHERE idn = ?";
		return $db->query($sql, array($idn));
	}
}