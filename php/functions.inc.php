<?php
  /**
   * @param $idn ID navstevy ve tvaru N.cislo
   */     
  function idNavstevy($idn){
    $idn = str_replace("N.", "", $idn);
    return intVal($idn);
  }
  
  /**
   * Alternativa k print_r()
   * 
   * @param $array Pole
   */
	function printr($array){
	  echo "<pre>";
	  print_r($array);
	  echo "</pre>";
	}	 	 	   