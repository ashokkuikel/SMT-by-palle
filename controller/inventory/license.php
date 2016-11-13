<?php

  $lic = new License;
  $had = new Hardware;
  $wos = new Server('vmware');
  $ses = Session::getInstance();
  $url = base::get('url');
  
  if(isset($url['2']) && $url['2'] == 'new' || isset($url['2']) && $url['2'] == 'detail' || isset($url['2']) && $url['2'] == 'new' || isset($url['2']) && $url['2'] == 'edit') {
    
    if($url['2'] == 'new' && end($url) == 'save') {
      $lic->saveLicense($_POST);  
      header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/" . base::get('methode'));
    }
    
    if($url['2'] == 'edit') {
      if(end($url) == 'save') {
        $lic->updateLicense($url['3'], $_POST);      
        header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/" . base::get('methode'));
      } else {
        template::setText('detail', $lic->getDetail(end($url)));
      }
      
    }
    
    template::setText('hardware', $had->getHardware());
    template::setText('images', $wos->getAllSystem(False, False));
    template::setText('lic_act', $url['2']);
  }
  
  if(isset($url['2']) && $url['2'] == 'delete') {
    $lic->delLicense(end($url));
    header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/" . base::get('methode'));
  }
  
  if (count($url) > 2) {
    $db_limit['start'] = (int) end($url);
  } else { $db_limit['start'] = 0; }
  
  $db_limit['limit'] = (int) base::get('iAnzahl');
  $db_limit['next'] = ($db_limit['start'] + $db_limit['limit']);
  $db_limit['prev'] = ($db_limit['start'] - $db_limit['limit']);
  $ses->set('db_limit', $db_limit);

  template::setText('Blaetter', True);
  template::setText('liste', $lic->getAll());
