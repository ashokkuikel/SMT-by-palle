<?php

if ($_SESSION['admin'] === False) {
  base::setRoute('home', 'index', TRUE);
}

$db = new Database('SMT-ADMIN');
$url = base::get('url');

if (end($url) == 'save') {
  foreach ($_POST as $key => $value) {
    $query = "UPDATE wos_config SET value=:value WHERE id=:key";
    $value = array(':value' => $value, ':key' => $key);
    $db->getQuery($query, $value);
  }
}

$query = "SELECT * FROM wos_config";
$db->getQuery($query, array());

template::setText('config', $db->getValue());

