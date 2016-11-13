<?php

$url = base::get('url');
$service = new Service;
$server  = new Server;
$user    = Base::get('Handler')->user;

if (in_array('new', $url) || in_array('edit', $url) || in_array('delete', $url) || in_array('delete_relation', $url)) {
  if ($_SESSION['admin'] === False) {
    base::setRoute('home', 'index', TRUE);
  }
}

if (in_array('new', $url) && !in_array('save', $url)) {
  $old = explode('/', substr($_SERVER ['HTTP_REFERER'], 7));
  $_SESSION['old'] = $old['1'];
}


// Neuen Service definieren
if (in_array('new', $url)) {
  template::setText('server_detail', $server->getSystem(end($url)));
  template::setText('all_services', $service->getAllService());
  template::setText('user_liste', $user->listUsers());
}

// Einen vorhanden Service bearbeiten
if (in_array('edit', $url)) {
  $servid = end($url);
  $result = $service->getServiceDetail($servid, False, False);
  
  template::setText('service_detail', $result);
  session::set('service_detail', $result);
  template::setText('referr', $_SERVER['HTTP_REFERER']);
  template::setText('user_liste', $user->listUsers());
}

// Einen Service löschen
if (in_array('delete', $url)) {
  $service->deleteAllServices(end($url));
  header("Location: " . $_SERVER['HTTP_REFERER']);
}

// Update der Relationen
if (end($url) == 'relation') {
  header("Location: " . base::get('getPath') . "/" . $_SESSION['old'] . "/detail/" . $server->updateRelationSystem($_POST));
}

// Eine bestimmte Relation löschen
if (in_array('delete_relation', $url)) {
  $sid = $url['3'];
  $server->deleteSingleRelation($sid, end($url));

  header("Location: " . base::get('getPath') . "/server/detail/" . $sid);
}

// Einen neuen Service spreichern
if (end($url) == 'save') {
  $save = $service->saveService($_POST);
  if (!isset($_POST['referr'])) {
    header("Location: " . base::get('getPath') . "/" . $_SESSION['old'] . "/detail/" . $save);
  } else {
    header("Location: " . $_POST['referr']);
  }
}
?>