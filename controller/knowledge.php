<?php

  $url = base::get('url');
  $kb  = new Knowledge;
  $kc  = end($url);
  
  // Nach dem Editieren speichern
  if($kc == 'clear') {
    unset($_SESSION['kbsst']);
    unset($_SESSION['fst']);
    die(header("Location: " . base::get('getPath') . "/knowledge/index"));
  }
  
  if($kc == 'search') {
    Session::Set('kbsst', $_POST['search_string']);
    Session::Set('fst', $_POST['search_string']);
  }
  
  if(in_array('edit', $url)) {
    // Falls der User kein Admin ist umleiten
    if ($_SESSION['admin'] === False) {
      base::setRoute('home', 'index', TRUE);
    }    
    template::setText('edit', True);
  }

  // Nach dem Editieren speichern
  if(in_array('delete', $url)) {
    $kb->delete($kc);
    die(header("Location: " . base::get('getPath') . "/knowledge/index"));
  }

  // Nach dem Editieren speichern
  if(in_array('save', $url) && $kc != 'save') {
    $kb->saveEdit($_POST, $kc);
    die(header("Location: " . base::get('getPath') . "/knowledge/" . $kc));
  }

  // Neuen Eintrag speichern
  if(in_array('save', $url) && $kc == 'save') {
    $id = $kb->saveNew($_POST);
    die(header("Location: " . base::get('getPath') . "/knowledge/" . $id));
  }

  // Content zum Beitrag einlesen
  if($kc == 'index') {
    template::setText('content_knowledge', $kb->loadStart($kc));
    template::setText('index', True);
  } 

  // Content zum Beitrag einlesen
  if($kc != 'new' && $kc != 'index' && $kc != 'search') {
    template::setText('content_knowledge', $kb->loadContent($kc));
  } 

  // Template auf neuen Eintrag stellen
  if($kc == 'new') {
    template::setText('new', True);
  }

  // Ausgabe des Beitrags
  if($kc != 'new' && $kc != 'index' && !in_array('edit', $url)) {
    template::setText('show', True);
  }

  template::setText('submenu_knowledge', $kb->loadMenu($kc));
?>

