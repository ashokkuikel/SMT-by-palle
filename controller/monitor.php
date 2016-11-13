<?php

  // Datenbank initiieren
  $db = new Database('SMT-MONITOR');

  // Standardabfragen für Menü und Nachrichten
  template::setText('submenu_content', Base::get('Handler')->loadMenu(Base::get('controller'), Base::get('methode')));
  template::setText('news_content', Base::get('Handler')->loadNews(Base::get('controller')));
  template::setText('psm_last_update', Base::get('Handler')->getLastUpdate());

  require_once base::getSubcontroller();

?>