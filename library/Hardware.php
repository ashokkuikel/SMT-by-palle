<?php

/**
 * Hardware Klasse für SMT Anwendung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */

class Hardware {
  
  /**
   * Hardwaredaten einlesen, ohne ID werden alle Datensätze gelesen
   * mit ID nur die Details zu einem Datensatz
   * 
   * @param int $id
   * @return array
   */
  public function getHardware($id='') {
    $db = new Database('SMT-ADMIN');
    $ses = Session::getInstance();
    
    if(empty($id)) {
      $lim = $ses->get('db_limit');

      $sum = $db->getQuery("SELECT count(id) FROM wos_hardware", array(), True);
      $ses->set('db_summe', $sum['0']['count(id)']);
      $ord = $ses->get('order_hardware');
      
      if(isset($ord) && $ord != '') { 
        $query = "SELECT * FROM wos_hardware ORDER BY $ord ASC LIMIT ";
      } else {
        $query = "SELECT * FROM wos_hardware ORDER BY id DESC LIMIT ";
      }
      
      $db->getQuery($query . $lim['start'] . "," . $lim['limit'] . "", array());
      return $db->getValue();
      
    } else {
      $det['hardware'] = $db->getQuery("SELECT * FROM wos_hardware WHERE id=:id", array(':id'=>$id), True);
      $det['detail']   = $db->getQuery("SELECT * FROM wos_hardware_details WHERE hardware_id=:id", array(':id'=>$id), True);
      return $det;
    }        
  }
  
  /**
   * Methode zum speichern der Daten
   * Aufruf und Übergabe der Daten an die update Funktion
   */
  public function saveHardware($post) {
    $db = new Database('SMT-ADMIN');
    
    $query = "INSERT INTO wos_hardware (bezeichnung) VALUE (:bezeichnung)";
    $value = array(':bezeichnung' => $post['bezeichnung']);

    $db->getQuery($query, $value);
    $id = $db->getLastID();

    $this->updateHardware($id, $post);
    return $id;
  }
  
  /**
   * 
   * @param type $id
   */
  public function updateHardware($id, $post) {
    $db = new Database('SMT-ADMIN');
    
    if(empty($post['bezeichnung'])) {
      $post['bezeichnung'] = $post['hersteller'] . ' ' . $post['modell'];
      if(!empty($post['zuordnung'])) {
        $post['bezeichnung'] .= ' ('.$post['zuordnung'].')';
      } else {
        $post['bezeichnung'] .= ' ('.$post['inventarnummer'].')';
      }
    }

    foreach ($post as $key => $value) {
      if (!preg_match('/detail_/', $key)) {
        $query = "UPDATE wos_hardware SET $key=:value WHERE id=:id";
        $value = array(':value' => $value, ':id' => $id);

        $db->getQuery($query, $value);
      }
    }
    
    if (isset($post['detail_name'])) {
      // Vorhandene Werte löschen und dann neu schreiben
      $db->getQuery("DELETE FROM wos_hardware_details WHERE hardware_id=:id", array(':id' => $id));

      $detail_name  = $post['detail_name'];
      $detail_value = $post['detail_value'];

      for ($i = 0; $i < count($detail_name); $i++) {
        $query = "INSERT INTO wos_hardware_details (hardware_id, form_name, form_value) VALUE (:id, :name, :value)";
        $value = array(':name' => $detail_name[$i], ':value' => $detail_value[$i], ':id' => $id);

        if (!empty($detail_name[$i]) && !empty($detail_value[$i])) {
          $db->getQuery($query, $value);
        }
      }
    }
  }
  
  /**
   * Methode zum löschen eines Eintrags
   * @param type $id
   */
  public function deleteHardware($id) {
    $db = new Database('SMT-ADMIN');
    $db->getQuery("DELETE FROM wos_hardware WHERE id=:id", array(':id' => $id));
    $db->getQuery("DELETE FROM wos_hardware_details WHERE hardware_id=:id", array(':id' => $id));
  }
}
