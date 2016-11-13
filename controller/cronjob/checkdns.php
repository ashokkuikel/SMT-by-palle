<?php

$url = base::get('url');
$wos = new Server();
$res = $wos->getAllSystem(False);
//print_r($res);
// Alte Einträge löschen
$db = new Database('SMT-ADMIN');
$db->getQuery("TRUNCATE TABLE `wos_dns_cron`", array());

// Tabelle neu einlesen und speichern
for ($i = 0; $i < count($res); $i++) {
  $tip = explode(',', $res[$i]['ipadressen']);
  if($res[$i]['live_dns'] == 'on') {
    $wos->checkDNS($tip['0'], $res[$i], True);
  }
}

if (isset($url['2'])) {
  header("Location: " . $_SERVER['HTTP_REFERER']);
}

die();
?>

