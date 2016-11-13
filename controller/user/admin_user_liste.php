<?php

  Template::setText('admin_user_active', 'active');
  
  if(count($url) > 2 && $url['2'] == 'delete') {
    $user->delUser(end($url));
    Base::setRoute('user', 'admin_user_liste');
  }

  if (count($url) > 2) {
    $db_limit['start'] = (int) end($url);
  } else {
    $db_limit['start'] = 0;
  }
  
  $db_limit['limit'] = (int) base::get('iAnzahl');
  $db_limit['next'] = ($db_limit['start'] + $db_limit['limit']);
  $db_limit['prev'] = ($db_limit['start'] - $db_limit['limit']);
  $session->set('db_limit', $db_limit);
 
  template::setText('Blaetter', True);
  template::setText('user_liste', $user->listUsers());

?>