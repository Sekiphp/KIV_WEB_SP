<?php
class Ucet{
  private $db;
  
  public function __construct($db){
    $this->db = $db;  
  } 
  
  /**
   * Overi uzivatele v systemu
   * 
   * @param $user Prihlasovaci login
   * @param $pass Heslo
   * @return True || False   
   */              
  public function logIn($user, $pass){
    $passy = md5($pass);
    
    $sql = "SELECT idz, heslo, admin FROM zamestnanci WHERE email = :email";   
    $user_data = $this->db->assoc($this->db->query($sql, array(":email" => $user)));

		# neexistujici zaznam
		if($user_data == NULL){
			return FALSE;
		}
		else{
	    if($passy != $user_data['heslo']){
	      return FALSE;
	    }
	    else{
	      $_SESSION['username'] = $user;
	      $_SESSION['admin'] = $user_data['admin'];
	      $_SESSION['idz'] = $user_data['idz'];
        $_SESSION['logged'] = 1;         
	      return TRUE;
	    }		
		}
  }
  
  /**
   * Odhlasi daneho uzivatele
   */     
  public function logOut(){
    $_SESSION = array();
    unSet($_SESSION);
    session_unset();  
    session_destroy(); 
  }     
}