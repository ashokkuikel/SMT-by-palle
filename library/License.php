<?php

/**
 * Lizenz Klasse für SMT Anwendung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */
class License {
  
  /**
   * Methode zum auslesen aller Services eines bestimmten Systems
   * 
   * @param type $iId
   * @return type
   */
  public function getAll() {
    $db = new Database('SMT-ADMIN');
    $ses = Session::getInstance();
    $lim = $ses->get('db_limit');

    $sum = $db->getQuery("SELECT count(id) FROM wos_license", array(), True);
    $ses->set('db_summe', $sum['0']['count(id)']);

    $db->getQuery("SELECT * FROM wos_license ORDER BY hersteller LIMIT " . $lim['start'] . "," . $lim['limit'] . "", array());

    return $db->getValue();
  }
  
  
  /**
   * Methode zum auslesen aller Services eines bestimmten Systems
   * 
   * @param type $iId
   * @return type
   */
  public function getDetail($id) {
    $db = new Database('SMT-ADMIN');
    $result = $db->getQuery("SELECT * FROM wos_license WHERE id=:id", array(':id'=>$id), True);
    
    return $result['0'];
  }
  
  
  /**
   * Löschen einer Lizenz
   * @param type $id
   */
  public function delLicense($id) {
    $db = new Database('SMT-ADMIN');

    $query = "DELETE FROM wos_license WHERE id=:id";
    $db->getQuery($query, array(':id' => $id));
  }
  
  
  
  /**
   * Methode zum speichern von Lizenzen
   * @param type $post
   * @return type
   */
  public function saveLicense($post) {
    $db = new Database('SMT-ADMIN');
    $ses= Session::getInstance();

    $query = "INSERT INTO wos_license (anzahl,vmware) VALUES (:anzahl,:vmware)";
    $value = array(':anzahl' => 1, ':vmware'=> 0);
    
    $db->getQuery($query, $value);
    $this->updateLicense($db->getLastID(), $post);
  }
  
  /**
   * Methode zum speichern von Lizenzen
   * @param type $post
   * @return type
   */
  public function updateLicense($id, $post) {
    $db = new Database('SMT-ADMIN');
    
    foreach ($post as $key => $value) {
      if($value != '') {
        $query = "UPDATE wos_license SET $key=:value WHERE id=:id";
        $value = array(':value' => $value, ':id' => $id);        
        
        $db->getQuery($query, $value);
      }
    }
  }
  
}
