<?php

$wos = new Service();
$ses = Session::getInstance();
$url = base::get('url');

if(end($url) == 'empty') {
  $wos->clearLogfile();
  header("Location: " . $_SERVER ['HTTP_REFERER']);
}

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

$detail['log'] = $wos->getLog();
template::setText('detail', $detail);
