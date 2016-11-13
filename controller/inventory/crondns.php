<?php

$url = base::get('url');
$wos = new Server;

if (end($url) == 'dnsreload') {
  $ssh = new SSH('localhost');
  $ssh->cmdExec('service nscd stop');
  $ssh->cmdExec('service nscd start');

  header("Location: " . $_SERVER['HTTP_REFERER']);
}

$res = $wos->getCronIPs();
template::setText('server_liste', $res);
