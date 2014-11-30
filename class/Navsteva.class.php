<?php

class Navsteva{
  private $db = NULL;
  
  public function __construct($db){
    $this->db = $db;
  }
  
  /**
   * Prida navstevu do systemu
   *	   
   * @param $assoc Asociativni pole z $_POST
   * @return MIXED[IDN (-1 pri chybe), IDZ, Zprava - String]   
   */     
  public function pridatNavstevu($assoc){
  	$idn = $idz = -1;
    foreach($assoc as $key => $value){
      $$key = $value;
    }

    if($jmeno == "" || $zodpovedny == "" || strLen($ucel) < 2){
      $mess = "Nejsou vyplněna všechna povinná pole!";
    }
    else{
    	$idz = $this->vyhledatZamestnance($zodpovedny); 
      
      if($idz == NULL){
        $mess = "Takový zaměstnanec neexistuje!";
      }
      else{    
        $idf = $this->pridatFirmu($nazev_firmy, $spz);       
        $idn = $this->pridatPrichod($idf, $ucel, $idz);      
        $ido = $this->pridatOsoby($jmeno, $cop);             
        $this->svazatOsobyNavstevu($idn, $ido);             
      } 
    }
    
    return array(
			"idn" => $idn, 
			"idz" => $idz, 
			"mess" => @$mess,
		);
  }
  
  /**
   * Prida firmu nebo vreati IDF odpovidajici
   *
   * @param $nazev Nazev firmy
   * @param $spz SPZ vozidla, pokud neni, tak NULL nebo empty string      
   * @return IDF
   */           
  private function pridatFirmu($nazev, $spz){
    $sql = "SELECT idf FROM " . TABLE_FIRMY . " WHERE nazev = :nazev AND spz = :spz";
    $where = array(
      ":nazev" => $nazev, 
      ":spz" => $spz, 
    );
    
    $firma = $this->db->assoc($this->db->query($sql, $where)); 
    if($firma['idf'] == NULL && ($nazev != "" || $spz != "")){   
      # firma neexistuje
      $sql = "INSERT INTO " . TABLE_FIRMY . " (nazev, spz) VALUES (:nazev, :spz)";
      $this->db->query($sql, $where);
      
      $firma['idf'] = $this->db->lastInsertId();
    }

    return $firma['idf'];
  }
  
  /**
   * Vytvori novou navstevu
   *
   * @param $idf IDF firmy
   * @param $ucel Ucel navstevy podniku
   * @param $idz IDZ zodpovedneho zamestnance
   * @return IDN        
   */           
  private function pridatPrichod($idf, $ucel, $idz){
    $sql = "INSERT INTO " . TABLE_NAVSTEVY . " (idf, ucel, zakym, zadano) VALUES (:idf, :ucel, :zakym, NOW())"; 
    $values = array(
      ":idf" => $idf, 
      ":ucel" => $ucel, 
      ":zakym" => $idz, 
    );
    
    $this->db->query($sql, $values);
    $idn = $this->db->conn->lastInsertId();
    
    return $idn;
  }
  
  /**
   * Vrati IDO prislusnych osob, pokud neexistuji, tak se vytvori
   * 
   * @param $jmena Pole o 1-4 prvcich se jmeny
   * @param [$cop] Pole o 1-4 prvcich s cisli obcanek nebo jineho dokladu      
   * @return Pole o 1-4 prvcich obsehujicich IDO
   */        
  private function pridatOsoby($jmena, $cop){
    # nelze count - vrati celkovy pocet; nutno pocet !empty
    $count = 0;
    foreach($jmena as $value){
      if(!empty($value)) $count++;
    }

    $sql = "SELECT ido FROM " . TABLE_OSOBY . " WHERE jmeno = :jmeno AND prijmeni = :prijmeni AND cop = :cop";
    
    $ido = array();
    for($i = 0; $i < $count; $i++){
      $ex = exPlode(" ", $jmena[$i]);
      $values = array(
        ":jmeno" => $ex[0], 
        ":prijmeni" => $ex[1], 
        ":cop" => @$cop[$i],
      ); 
      
      $res = $this->db->assoc($this->db->query($sql, $values));
      if($res == NULL){
        $sqli = "INSERT INTO " . TABLE_OSOBY . " (jmeno, prijmeni, cop) VALUES (:jmeno, :prijmeni, :cop)";
        $this->db->query($sqli, $values);
        
        $res['ido'] = $this->db->lastInsertId();
      }
      $ido[] = $res['ido'];
    }
    return $ido;
  }
  
  /**
   * Postara se o propojeni tabulek vr_osoby a vr_navstevy
   *
   * @param $idn ID dane navstevy
   * @param $ido Pole s ID vsech osob  
   */           
  private function svazatOsobyNavstevu($idn, $ido){
    $count = sizeOf($ido);
    $sql = "INSERT INTO " . TABLE_NAVSTOS . " (ido, idn) VALUES (:ido, :idn)";
    
    for($i = 0; $i < $count; $i++){
      $values = array(
        ":ido" => $ido[$i], 
        ":idn" => $idn, 
      );
      $this->db->query($sql, $values);
    }
  }
  
  /**
   * Vyhleda IDZ zamestnance
   *
   * @param $prettyname Cele jmeno ve tvaru: Lubos Hubacek || Hubacek Lubos   
   * @return IDZ   
   */	 	   
	public function vyhledatZamestnance($prettyname){
    $ex = exPlode(" ", $prettyname);
    $sql = "SELECT idz FROM " . TABLE_ZAMESTNANCI . " WHERE (jmeno = :jmeno AND prijmeni = :prijmeni) OR (jmeno = :prijmeni AND prijmeni = :jmeno)";
    $values = array(
      ":jmeno" => $ex[0], 
      ":prijmeni" => $ex[1],
    );
    $zakym = $this->db->assoc($this->db->query($sql, $values)); 
    
    return($zakym['idz']);
  }
  
  /**
   * Prida poznamku k dane navsteve
   *
   * @param $text Text poznamky
   * @param $idz ID zamestnance
   * @return MIXED PDO:query() vraci FALSE pri chybe	    
   */	 	 	   
  public static function pridatPoznamku($text, $idz, $idn, $db){
  	if(strLen($text) < 2 || intVal($idz) == 0 || intVal($idn) == 0){
			return FALSE;
		}
		
		$sql = "INSERT INTO " . TABLE_POZNAMKY . " (idn, text, zadal, zadano) VALUES (:idn, :text, :zadal, NOW())";
		$values = array(
			":idn" => $idn, 
			":text" => $text, 
			":zadal" => $idz, 
		);
		
		return($db->query($sql, $values));
	}
}