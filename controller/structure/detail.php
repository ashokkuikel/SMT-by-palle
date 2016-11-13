<?php

$wos = new Server();
$url = base::get('url');
$sys = $url['2'];
$usr = Base::get('Handler')->user;

if(end($url) == 'favorite') {
  $usr->Favorite($sys);
  die(header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/detail/" . $sys));
}

if(!$usr->checkFavorite($sys)) {
  Template::setText('add_favorite', True);
  
}

if($usr->checkFavorite($sys)) {
  Template::setText('del_favorite', True);
}

if (end($url) == 'dnsreload') {
  $ssh = new SSH('localhost');
  $ssh->cmdExec('service nscd stop');
  $ssh->cmdExec('service nscd start');

  header("Location: " . $_SERVER['HTTP_REFERER']);
}

if (end($url) == 'save') {
  $wos = new Server();
  $wos->updateSystem($sys, $_POST);

  die(header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/detail/" . $sys));
}

$server = $wos->getSystem($sys);

if ($server['live_dns'] == 'on') {
  $tip = explode(',', $server['ipadressen']);
  for ($i = 0; $i < count($tip); $i++) {
    $server['dns'][$i] = $wos->checkDNS($tip[$i], $server);
  }
}

template::setText('server_detail', $server);
?>
