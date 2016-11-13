<?php

if ($_SESSION['admin'] === False) {
  base::setRoute('home', 'index', TRUE);
}

$wos = new Server();
$url = base::get('url');
$sys = end($url);
$sta = $url['2'];

$wos->updateWartung($sys, $sta);
die(header("Location: " . $_SERVER['HTTP_REFERER']));
