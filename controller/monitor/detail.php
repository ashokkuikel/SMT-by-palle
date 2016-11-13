<?php

// Neue Serverinstance 
$wos = new Service();
$url = base::get('url');
$sys = end($url);

template::setText('detail', $wos->getServiceDetail($sys, True, True));

$arc = new UptimeArchiver;
$arc->archive($sys);

$h = new HistoryGraph;
template::setText('graphs', $h->createHTML($sys));
?>
