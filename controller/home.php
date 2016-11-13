<?php

// Datenbank initiieren
$db = new Database('SMT-ADMIN');
$time = new Time();

// Standardabfragen für Menü und Nachrichten
template::setText('submenu_content', Base::get('Handler')->loadMenu(Base::get('controller'), Base::get('methode')));
template::setText('news_content', Base::get('Handler')->loadNews(Base::get('controller')));
template::setText('psm_last_update', Base::get('Handler')->getLastUpdate());

if (file_exists(project_path . '/controller/structure/' . base::get('methode') . '.php')) {
  include(project_path . '/controller/structure/' . base::get('methode') . '.php');
} else {
  require_once base::getSubcontroller();
}
?>