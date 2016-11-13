<?php

/**
 * Inventur Klasse für die SMT Anwedung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */

class Inventur {
  
  public function searchItem($barcode) {
    $db=new Database('SMT-ADMIN');
    $barcode = str_replace('/', '-', $barcode);
    
    $db->getQuery("SELECT * FROM wos_hardware WHERE inventarnummer LIKE :barcode", array(':barcode' => $barcode));    
    if($db->getNumrows() == 1) {
      $this->saveItem($db->getValue(), 'Hardware');
    }
    
    $db->getQuery("SELECT * FROM wos_license WHERE barcode LIKE :barcode", array(':barcode' => $barcode));    
    if($db->getNumrows() == 1) {
      $this->saveItem($db->getValue(), 'Software');
    }
    
  }
  
  public function saveItem($aValue, $sResult) {
    $db=new Database('SMT-ADMIN');
    
    if($sResult == 'Hardware') {
      $query = "";
      $value = "";
    }
    
    if($sResult == 'Software') {
      $query = "";
      $value = "";
    }
    
    $db->getQuery($query, $value);
  }
  
  
  public function readAllItems() {
    $db=new Database('SMT-ADMIN');
    
    $har = $db->getQuery("SELECT * FROM wos_hardware WHERE inventur=:inventur", array(':inventur'=>'ja'), True);
    for($i=0; $i<count($har); $i++) {  
      $db->getQuery("SELECT * FROM wos_inventur WHERE barcode=:barcode", array(':barcode' => $har[$i]['inventarnummer']));
      if($db->getNumrows() > 0) {
        $har[$i]['status'] = "on";
      } else {
        $har[$i]['status'] = "info";
      }
			$har[$i]['bezeichnung'] = substr($har[$i]['bezeichnung'], 0, 15);
    }
    
    // return array($lic, $har);
    return $har;
  }
  
  
  public function saveScan($post) {
    $db=new Database('SMT-ADMIN');
		$barcode = str_replace('/', '-', $post['barcode']);
    
    $query = "INSERT INTO wos_inventur (barcode) VALUE (:barcode)";
    $value = array(':barcode' => $barcode);

    $db->getQuery($query, $value);
  }
  
  
  public function clear() {
    $db=new Database('SMT-ADMIN');
    $db->getQuery("TRUNCATE wos_inventur", array(), True);
  }
  
}

