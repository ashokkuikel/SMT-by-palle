<?php

// Datenbank initiieren
$url = base::get('url');
$time = new Time();

if (isset($_POST['search_string'])) {
  Session::Set('fst', $_POST['search_string']);
  Session::Set('sst', "%{$_POST['search_string']}%"); // Suchbegriff setzen
  Session::Set('ssc', base::get('controller'));       // Controller setzen
  if ($_POST['methode'] != 'search') {
    Session::Set('ssm', $_POST['methode']);
  }
  $s = True;
}

/*
 * Suche nach Systemen
 */
if (Session::get('ssc') == 'server' || Session::get('ssc') == 'vmware') {
  $db = new Database('SMT-ADMIN');
  $query = "SELECT * FROM wos_server WHERE "
    . "id LIKE :sst && serverart=:controller || "
    . "ipadressen LIKE :sst && serverart=:controller || "
    . "bezeichnung LIKE :sst && serverart=:controller || "
    . "hostname LIKE :sst && serverart=:controller || "
    . "betriebssystem LIKE :sst && serverart=:controller || "
    . "standort LIKE :sst && serverart=:controller || "
    . "beschreibung LIKE :sst && serverart=:controller || "
    . "aliase LIKE :sst && serverart=:controller ORDER BY bezeichnung";
  $value = array(':sst' => Session::get('sst'), ':controller' => base::get('controller'));
  $tmp = '';

  $db->getQuery($query, $value);

  if ($db->getNumrows() >= 1 && isset($s)) {
    $new_url = $_SERVER['PHP_SELF'] . '/' . $_SERVER['QUERY_STRING'];
    header('Location:' . str_replace("index.php/", "", $new_url));
  }

  $result = $db->getValue();
  template::SetText('list_art', 'server_liste');
  template::setText('server_liste', $result);
}

/*
 * Suche nach Systemen im Inventory / IP Adresslisre
 */
if (Session::get('ssc') == 'inventory' && Session::get('ssm') == 'ipadressen') {
  $db = new Database('SMT-ADMIN');
  $query = "SELECT * FROM wos_server WHERE "
    . "ipadressen LIKE :sst || "
    . "bezeichnung LIKE :sst || "
    . "hostname LIKE :sst || "
    . "betriebssystem LIKE :sst || "
    . "standort LIKE :sst || "
    . "beschreibung LIKE :sst || "
    . "aliase LIKE :sst ORDER BY INET_ATON(ipadressen)";
  $value = array(':sst' => Session::get('sst'));

  $db->getQuery($query, $value);

  if ($db->getNumrows() >= 1 && isset($s)) {
    $new_url = $_SERVER['PHP_SELF'] . '/' . $_SERVER['QUERY_STRING'];
    header('Location:' . str_replace("index.php/", "", $new_url));
  }

  $result = $db->getValue();
  template::SetText('list_art', 'server_liste');
  template::setText('server_liste', $result);
}

/*
 * Suche nach Systemen im Inventory / Services
 */
if (Session::get('ssc') == 'inventory' && Session::get('ssm') == 'services') {
  $db = new Database('SMT-MONITOR');
  $query = "SELECT * FROM psm_servers WHERE "
    . "ip LIKE :sst || "
    . "label LIKE :sst || "
    . "port LIKE :sst || "
    . "type LIKE :sst || "
    . "user LIKE :sst ORDER BY INET_ATON(ip)";
  $value = array(':sst' => Session::get('sst'));

  $db->getQuery($query, $value);

  if ($db->getNumrows() >= 1 && isset($s)) {
    $new_url = $_SERVER['PHP_SELF'] . '/' . $_SERVER['QUERY_STRING'];
    header('Location:' . str_replace("index.php/", "", $new_url));
  }

  $all_services = $db->getValue();
  template::SetText('list_art', 'inventory_service');
  template::setText('all_services', $all_services);
}



/*
 * Suche nach Systemen
 */
if (Session::get('ssc') == 'home') {
  $db = new Database('SMT-ADMIN');
  $query = "SELECT * FROM wos_server WHERE "
    . "id LIKE :sst || "
    . "ipadressen LIKE :sst || "
    . "bezeichnung LIKE :sst || "
    . "hostname LIKE :sst || "
    . "betriebssystem LIKE :sst || "
    . "beschreibung LIKE :sst || "
    . "aliase LIKE :sst ORDER BY bezeichnung";
  $value = array(':sst' => Session::get('sst'));
  $tmp = '';

  $db->getQuery($query, $value);

  if ($db->getNumrows() >= 1 && isset($s)) {
    $new_url = $_SERVER['PHP_SELF'] . '/' . $_SERVER['QUERY_STRING'];
    header('Location:' . str_replace("index.php/", "", $new_url));
  }

  $wos = new Server();
  $server = $db->getValue();

  for ($i = 0; $i < count($server); $i++) {
    $server[$i] = $wos->getSystem($server[$i]['id'], False, True);
  }

  template::SetText('list_art', 'server_liste');
  template::setText('server_liste', $server);
  template::setText('status_title', 'Folgende Systeme gefunden');
  template::setText('page_reload', base::get('Handler')->config['auto_refresh_servers']);
}


/*
 * Suche nach Systemen im Inventory / Services
 */
if (Session::get('ssc') == 'inventory' && Session::get('ssm') == 'servicelog') {
  $db = new Database('SMT-MONITOR');
  $query = "SELECT * FROM psm_log WHERE "
    . "server_id LIKE :sst || "
    . "message LIKE :sst || "
    . "datetime LIKE :sst ORDER BY datetime";
  $value = array(':sst' => Session::get('sst'));

  $db->getQuery($query, $value);

  if ($db->getNumrows() >= 1 && isset($s)) {
    $new_url = $_SERVER['PHP_SELF'] . '/' . $_SERVER['QUERY_STRING'];
    header('Location:' . str_replace("index.php/", "", $new_url));
  }

  $all_services['log'] = $db->getValue();
  template::SetText('list_art', 'system');
  template::SetText('tal_art', 'service_log');
  template::setText('detail', $all_services);
}


/*
 * Suche nach Systemen im Inventory / Hardware
 */
if (Session::get('ssc') == 'inventory' && Session::get('ssm') == 'hardware') {
  $db = new Database('SMT-ADMIN');
  $query = "SELECT * FROM wos_hardware WHERE "
    . "bezeichnung LIKE :sst || "
    . "kategorie LIKE :sst || "
    . "inventarnummer LIKE :sst || "
    . "kaufdatum LIKE :sst || "
    . "hersteller LIKE :sst || "
    . "modell LIKE :sst || "
    . "zuordnung LIKE :sst || "
    . "beschreibung LIKE :sst || "
    . "seriennummer LIKE :sst ORDER BY kategorie, hersteller, modell DESC";
  $value = array(':sst' => Session::get('sst'));

  $db->getQuery($query, $value);

  if ($db->getNumrows() >= 1 && isset($s)) {
    $new_url = $_SERVER['PHP_SELF'] . '/' . $_SERVER['QUERY_STRING'];
    header('Location:' . str_replace("index.php/", "", $new_url));
  }

  $result = $db->getValue();
  template::SetText('list_art', 'search_hardware_liste');
  template::setText('liste', $result);
}


/*
 * Suche nach Systemen im Inventory / Lizenzen
 */
if (Session::get('ssc') == 'inventory' && Session::get('ssm') == 'license') {
  $db = new Database('SMT-ADMIN');
  $query = "SELECT * FROM wos_license WHERE "
    . "hersteller LIKE :sst || "
    . "produkt LIKE :sst || "
    . "version LIKE :sst || "
    . "licensekey LIKE :sst || "
    . "barcode LIKE :sst || "
    . "zuordnung LIKE :sst || "
    . "beschreibung LIKE :sst ORDER BY hersteller, produkt, version DESC";
  $value = array(':sst' => Session::get('sst'));

  $db->getQuery($query, $value);

  if ($db->getNumrows() >= 1 && isset($s)) {
    $new_url = $_SERVER['PHP_SELF'] . '/' . $_SERVER['QUERY_STRING'];
    header('Location:' . str_replace("index.php/", "", $new_url));
  }

  $result = $db->getValue();
  template::SetText('list_art', 'search_license_liste');
  template::setText('liste', $result);
}

?>
