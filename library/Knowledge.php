<?php

  /**
  * Knowledge Klasse für die SMT Anwedung
  * 
  * @author    Werner Pallentin <werner.pallentin@outlook.de>
  * @package   SMT
  */

  class Knowledge {
    
    /**
     * Methode zum einlesen und übergeben vom Menü
     * @param type $page
     * @return string
     */
    public function loadMenu($page) {
      $session = Session::getInstance();
      $db = new Database('SMT-ADMIN');     
      
      $q = '';
      $value = array('visible'=>'1');
      $order = 'page_name';
      
      if($session->get('kbsst')) {
        $ss = explode(' ', $session->get('kbsst'));
        
        for($i=0; $i<count($ss); $i++) {
          $q .= "page_content LIKE :sst$i && ";
          $value[':sst'.$i] = "%{$ss[$i]}%";
        }
        $order = 'datum DESC';
      }

      $db->getQuery("SELECT * FROM wos_knowledge WHERE $q visible=:visible ORDER BY $order", $value, True);
      $v = $db->getValue();
     
      for ($i = 0; $i < count($v); $i ++) {
        if($page == $v[$i]['id']) {
          $v[$i]['aktiv'] = 'active';
        }
      }
      
      return $v;
    }
    
    /**
     * Lesen und Übergabe eines Eintrages
     * @param type $page
     * @return type
     */
    public function loadContent($page) {
      $db = new Database('SMT-ADMIN');      
      $db->getQuery("SELECT * FROM wos_knowledge WHERE id=:id", array('id'=>$page), True);
      
      $v = $db->getValue();
      return $v['0'];
    }
    
    /**
     * Die letzten 5 Beiträge laden
     * @return type
     */
    public function loadStart() {
      $db = new Database('SMT-ADMIN');      
      $db->getQuery("SELECT * FROM wos_knowledge ORDER BY datum LIMIT 5", array(), True);
      
      return $db->getValue(); 
    }
    
    /**
     * Neuen Eintrag anlegen
     * @param type $post
     * @return type
     */
    public function saveNew($post) {
      $db = new Database('SMT-ADMIN');      
      
      $query = "INSERT INTO wos_knowledge (datum,page_name,page_content,keywords,version) VALUE (:datum,:name,:content,:keywords,:version)";
      $value = array(':datum' => date("Y-m-d H:i:s"), ':name'=>$post['page_name'], ':content'=>$post['page_content'], ':keywords'=>$post['keywords'], ':version'=>'1.0');      
      $db->getQuery($query, $value);
      
      $kb = $db->getLastID();
      
      $query = "INSERT INTO wos_knowledge_history (parent,version,datum,user,content) VALUE (:parent,:version,:datum,:user:,:content)";
      $value = array(':parent'=>$kb, ':version'=>'1.00', ':datum' => date("Y-m-d H:i:s"), ':user'=>$_SESSION['usernam'], ':content'=>$post['page_content']); 
      $db->getQuery($query, $value);
      
      return $kb;
    }
    
    /**
     * Geänderten Eintrag speichern
     * @param type $post
     * @param type $id
     */
    public function saveEdit($post, $id) {
      $db = new Database('SMT-ADMIN');   
      
      $query = "INSERT INTO wos_knowledge_history (parent,version,datum,user,content) VALUE (:parent,:version,:datum,:user,:content)";
      $value = array(':parent'=>$id, ':version'=>($post['version'] + 0.1), ':datum' => date("Y-m-d H:i:s"), ':user'=>$_SESSION['username'], ':content'=>$post['page_content']);
      $db->getQuery($query, $value);
      
      $query = "UPDATE wos_knowledge SET page_name=:name, page_content=:content, keywords=:keywords, version=version+0.1 WHERE id=:id";
      $value = array(':name'=>$post['page_name'], ':content'=>$post['page_content'], ':keywords'=>$post['keywords'], ':id'=>$id);      
      $db->getQuery($query, $value);
    }    
    
    /**
     * Einen Eintrag löschen
     * @param type $id
     */
    public function delete($id) {
      $db = new Database('SMT-ADMIN');      
      
      $query = "DELETE FROM wos_knowledge WHERE id=:id";
      $value = array(':id'=>$id);
      
      $db->getQuery($query, $value);
    }
    
  }

?>