<?php

if ($_SESSION['admin'] === False) {
  base::setRoute('home', 'index', TRUE);
}

$wos = new Server(base::get('controller'));

if (in_array('save', base::get('url'))) {
  $id = $wos->saveSystem($_POST);

  header("Location: " . base::get('getPath') . "/" . base::get('controller') . "/detail/" . $id);
}
?>