<?php

if ($_SESSION['admin'] === False) {
  base::setRoute('home', 'index', TRUE);
}

$url = base::get('url');
$sys = end($url);
$wos = new Server();
$wos->deleteSystem($sys);

header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/liste");
?>