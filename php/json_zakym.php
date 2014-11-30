<?php
  require 'config.inc.php';
  require '../class/DB.class.php';
  $db = new DB();
  
  # uprava parametru dotazu dle stavu zadavani  
  $get = $_GET['data']; 

  if(strPos($get, " ") !== FALSE && $get[strLen($get)-1] != " "){
    $ex = exPlode(" ", $get);
    
    $like = array(
      ":jmeno" => "%" . $ex[0] . "%", 
      ":prijmeni" => "%" . $ex[1] . "%",  
    );
    $sql = "
      SELECT jmeno, prijmeni 
      FROM " . TABLE_ZAMESTNANCI . " 
      WHERE 
        (jmeno LIKE :jmeno AND prijmeni LIKE :prijmeni) OR 
        (jmeno LIKE :prijmeni AND prijmeni LIKE :jmeno)    
    "; 
  }
  else{   
    // pro sichr
    $get = str_replace(" ", "", $get);
    
    $like = array(
      ":jmeno" => "%" . $get . "%", 
      ":prijmeni" => "%" . $get . "%",  
    );
    $sql = "
      SELECT jmeno, prijmeni 
      FROM " . TABLE_ZAMESTNANCI . " 
      WHERE 
        (jmeno LIKE :jmeno OR prijmeni LIKE :prijmeni) OR 
        (jmeno LIKE :prijmeni OR prijmeni LIKE :jmeno)    
    "; 
  }
  
  $res = $db->query($sql, $like);
 
  # vystupem je JSON s daty
  $json = array();
  while($row = $db->assoc($res)) {
    $json[] = array(
      'label' => $row['prijmeni'] . " " . $row['jmeno'],
    );
  }
 
  echo json_encode($json);