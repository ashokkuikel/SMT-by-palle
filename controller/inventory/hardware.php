<?php

  $had = new Hardware;
  $ses = Session::getInstance();
  $url = base::get('url');
  template::setText('kategorie', explode(',', Base::get('Handler')->config['hardware_kategorie']));
  
  if (count($url) > 2) {
    $db_limit['start'] = (int) end($url);
  } else { $db_limit['start'] = 0; }
  
  $db_limit['limit'] = (int) base::get('iAnzahl');
  $db_limit['next'] = ($db_limit['start'] + $db_limit['limit']);
  $db_limit['prev'] = ($db_limit['start'] - $db_limit['limit']);
  $ses->set('db_limit', $db_limit);
  
  if(isset($url['2'])) {
    
    if($url['2'] == 'new' && end($url) == 'save') {
      $had->saveHardware($_POST);      
      header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/" . base::get('methode'));
    }
    
    if($url['2'] == 'edit') {
      if(end($url) == 'save') {
        $had->updateHardware($url['3'], $_POST);      
        header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/" . base::get('methode'));
      } else {
        template::setText('detail', $had->getHardware(end($url)));
      }      
    }
    
    template::setText('det_act', $url['2']);
  }
  
  if(isset($url['2']) && $url['2'] == 'delete') {
    $had->deleteHardware(end($url));
    header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/" . base::get('methode'));
  }
  
  if(isset($url['2']) && $url['2'] == 'order') {
    Session::set('order_hardware', end($url));
    header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/" . base::get('methode'));
  }
  
  template::setText('Blaetter', True);
  template::setText('liste', $had->getHardware());
  
 ?>
