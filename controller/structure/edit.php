<?php

if ($_SESSION['admin'] === False) {
  base::setRoute('home', 'index', TRUE);
}

$wos = new Server();
$url = base::get('url');
$sys = $url['2'];

if (end($url) == 'save') {
  $wos->updateSystem($sys, $_POST);
  header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/detail/" . $sys);
}

template::setText('server_detail', $wos->getSystem($sys));
?>
