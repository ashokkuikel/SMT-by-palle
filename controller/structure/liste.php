<?php

$wos = new Server(base::get('controller'));
$ses = Session::getInstance();
$url = base::get('url');

if (count($url) > 2) {
  $db_limit['start'] = (int) end($url);
} else {
  $db_limit['start'] = 0;
}
$db_limit['limit'] = (int) base::get('iAnzahl');
$db_limit['next'] = ($db_limit['start'] + $db_limit['limit']);
$db_limit['prev'] = ($db_limit['start'] - $db_limit['limit']);
$ses->set('db_limit', $db_limit);
template::setText('Blaetter', True);

template::setText('server_liste', $wos->getAllSystem());
?>
