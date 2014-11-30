<?php
class DB{
	private $dsn = 'mysql:dbname=localhost;host=localhost';
	private $user = 'root';
	private $password = '';
	public $conn = NULL;

	/**
	 * Vytvori spojeni s databazi pres PDO
	 */	 	
	public function __construct(){
		try {
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    	$this->conn = new PDO($this->dsn, $this->user, $this->password, $options);
		} 
		catch (PDOException $e) {
    	die('Connection failed: ' . $e->getMessage());
		}	
	}
	
	/**
	 * Alias pro starou funkci mysql_query()
	 *
	 * @param $sql SQL kod prikazu
	 * @param $where pole parametru pokud ma $sql WHERE klauzuli	 	 
	 */	 	 	
	public function query($sql, $where = NULL){
		$sth = $this->conn->prepare($sql);
		$sth->execute($where);

		return $sth;
	}

	/**
	 * Alias pro starou funkci mysql_fetch_assoc()
	 * 
	 * @param $res Vysledek z metody DB::query()	 	 
	 */	 
	public function assoc($res){
		$result = $res->fetch(PDO::FETCH_ASSOC);
		
		return $result;	
	}
  
  /**
   * Vrati ID posledniho vlozeneho radku do databaze
   *
   * @return ID posledniho zaznamu
   */           
  public function lastInsertId(){
    return $this->conn->lastInsertId();
  }
}