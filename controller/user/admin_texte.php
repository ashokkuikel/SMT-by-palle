<?php

Template::setText('admin_texte_active', 'active');

if ($_SESSION['admin'] === False) {
  base::setRoute('home', 'index', TRUE);
}

if (end($url) == 'save') {
  Texte::saveTexte($_POST);
  base::setRoute('user', 'admin_texte', TRUE);
}

if (end($url) == 'new') {
  Texte::insertText($_POST);
  base::setRoute('user', 'admin_texte', TRUE);
}

if(isset($url['2'])) {
  template::setText('texte', texte::loadAdminTexte($url['2']));
  template::setText('sprache', $url['2']);
} else {
  template::setText('texte', texte::loadAdminTexte($session->get('language')));
  template::setText('sprache', $session->get('language'));
}


if(in_array('install', $url)) {
  texte::activateLanguage(end($url));
  base::setRoute('user', 'admin_texte', TRUE);
}

if(in_array('delete', $url)) {
  texte::deleteText(end($url));
  base::setRoute('user', 'admin_texte', TRUE);
}

template::setText('texte_inaktiv', texte::getSprachen());

