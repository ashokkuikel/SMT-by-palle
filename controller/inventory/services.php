<?php

$url = base::get('url');
$wos = new Service;
$ses = Session::getInstance();

if (end($url) == 'export') {
  $fp = fopen(project_path . '/assets/export/services.csv', 'w');

  foreach ($wos->getAllService() as $fields) {
    fputcsv($fp, $fields);
  }
  fclose($fp);

  header("Location: " . base::get('getPath') . "/assets/export/services.csv");
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
template::setText('all_services', $wos->getAllService(False));

?>