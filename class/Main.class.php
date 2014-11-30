<?php
class Main{
  private $get = NULL;
  private $db = NULL;
  public $tpl = NULL;
  public $render = array();

  /**
   * Hlavni ridici metoda cele aplikace
   * 
   * @param $get Pole $_GET
   * @param $db Odkaz na DB objekt         
   */     
  public function __construct($get, $db){
    $this->get = $get;
    $this->db = $db;
    
    # kontrola opravneni
    $neverejne = array('nova-navsteva', 'konec-navstevy', 'detail-navstevy');
    if(in_array($get, $neverejne) && !isSet($_SESSION['username'])){
      $this->tpl = 'neprihlasen.htm';
      return;
    }  
    
    # rozjedem to!!! :)  
    if($get == NULL || $get == 'historie-navstev'){
      $this->prehledNavstev();
    }
    elseif($get == 'prihlasit'){
      $this->prihlaseni();
    }
    elseif($get == 'odhlasit'){
      $this->odhlaseni();
    } 
    elseif($get == 'konec-navstevy'){
      $this->najitNavstevu();
    } 
    elseif($get == 'detail-navstevy'){
      $this->detailNavstevy();
    }  
    elseif($get == 'nova-navsteva'){
      $this->novaNavsteva();
    }
  
    
    # tpl je nutno priradit priponu
    if($this->tpl == ""){
      $this->prehledNavstev();
    }
    $this->tpl .= ".htm";
    
    # session pole je nutne vzdy
    $this->render['session'] = @$_SESSION; 	
  }
  
  /**
   * Prihlaseni do systemu
   */     
  private function prihlaseni(){
    $this->tpl = "prihlasit";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $ucet = new Ucet($this->db);
      $res = $ucet->logIn($_POST['email'], $_POST['passy']);
      
      if($res != FALSE){ 
        header("Location: index.php?page=historie-navstev");
      }
      else{
        $this->render['error'] = "Přihlášení do systému proběhlo neúspěšně";
      }
    }
  }
  
  /**
   * Odhlaseni ze systemu
   */     
  private function odhlaseni(){
    $this->tpl = "odhlasit";
    Ucet::logOut();
  }
  
  /**
   * Nova navsteva - formular pro admina
   */     
  private function novaNavsteva(){
    $this->tpl = "nova-navsteva";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $navsteva = new Navsteva($this->db);
      $res = $navsteva->pridatNavstevu($_POST);
      
      if($res['idn'] >= 1){       
        if(isSet($_POST['poznamka'])){
					Navsteva::pridatPoznamku($_POST['poznamka'], $_SESSION['idz'], $res['idn'], $this->db);
				}
        
        header("Location: index.php?page=detail-navstevy&idn=".$res['idn']);
      }
      else{
        $this->render['error'] = $res['mess'];
      }
    }
  }
  
  /**
   * Seznam vsech uskutecnenych navstev
   */     
  private function prehledNavstev(){
    $this->tpl = "historie-navstev";
    
  	$prehled = new HlavniPrehled($this->db);
    
    $this->render = array(
			'navstevy' => $prehled->vypis(@$_POST), 
		);	
  }
  
  /**
   * Najde navstevu dle ID - slouzi k vyhledani tiketu pro nasledne ukonceni
   */     
  private function najitNavstevu(){
    $this->tpl = "konec-navstevy";
  }
  
  /**
   * Zobrazi detail navstevy - "tiket pro tisk"
   */     
  private function detailNavstevy(){
    $this->tpl = "detail-navstevy";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    	$get_idn = idNavstevy($_GET['idn']);
    	
    	# pridat poznamku
      if(isSet($_POST['poznamka'])){
				$res = Navsteva::pridatPoznamku($_POST['poznamka'], $_SESSION['idz'], $get_idn, $this->db);
        
        if($res == FALSE){
          $this->render['error'] = "Nepodařilo se přidat poznámku, zkuste to znovu";
        }
        else{
          $this->render['info'] = "Poznámka byla úspěšně přidána k této návštěvě";
        }
      }
      
      # navsteva zacla odchazet
      if(isSet($_POST['odchazi'])){
				$res = Detail::navstevaOdchazi($get_idn, $this->db);
        
        if($res == FALSE){
          $this->render['error'] = "Něco se pokazilo - zkuste opakovat operaci";
        }
        else{
          $this->render['info'] = "Návštěva právě začala odcházet - je na cestě na vrátnici";
        }
      }
      
      # navsteva opousti areal
      if(isSet($_POST['odesla'])){
				$res = Detail::navstevaOdesla($get_idn, $this->db);
        
        if($res == FALSE){
          $this->render['error'] = "Něco se pokazilo - zkuste opakovat operaci";
        }
        else{
          $this->render['info'] = "Návštěva byla úspěšně uzavřena.";
        }
      }
    }
    
		$detail = new Detail($this->db, idNavstevy($_GET['idn']));
    $data = $detail->tabulkaDetail();
    if($data == NULL){
      $this->render['error'] = "Takový tiket neexistuje";  
    }
    
    $this->render['data'] = $data; 
		$this->render['poznamky'] = $detail->vypisPoznamek();
  }
}