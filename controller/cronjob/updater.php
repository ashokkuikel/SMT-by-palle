<?php

$url = base::get('url');
$wos = new Service();
$upd = new Updater();
$las = Base::get('Handler')->getLastUpdate();

// Alles Service die geprüft werden sollen einlesen
$result = $wos->getAllUpdateService($las['counter']);
//die(print_r($result));

if (!empty($_SERVER['argv'])) {
  foreach ($_SERVER['argv'] as $argv) {
    $argi = explode('=', ltrim($argv, '--'));
    if (count($argi) !== 2) {
      continue;
    }
    switch ($argi[0]) {
      case 'uri':
        define('BASE_URL', $argi[1]);
        break;
      case 'timeout':
        $cron_timeout = intval($argi[1]);
        break;
    }
  }
}

for ($i = 0; $i < count($result); $i++) {
  for ($s = 0; $s < count($result[$i]); $s++) {
    $v = $upd->update($result[$i][$s]['server_id']);
  }
}

/*
 * Automatisches Update
 */
if (!isset($url['2'])) {
  die('FERTIG');
}

if (isset($url['2'])) {
  header("Location: " . $_SERVER['HTTP_REFERER']);
} 
