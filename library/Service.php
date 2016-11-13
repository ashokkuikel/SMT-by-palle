<?php

/**
 * Serviceklasse für die SMT Anwendung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */
class Service {

  /**
   * Methode zum auslesen eines Services / Dienstes
   * @param type $server_id
   * @return type
   */
  public function getService($server_id) {

    $db = new Database('SMT-MONITOR');
    $time = new Time();

    $result = $db->getQuery("SELECT * FROM psm_servers WHERE server_id=:server_id", array(':server_id' => $server_id), True);

    $result['0']['last_online'] = $time->ago(new DateTime(date($result['0']['last_online'])));
    $result['0']['last_check'] = $time->ago(new DateTime(date($result['0']['last_check'])));

    return $result['0'];
  }

  /**
   * Alle Details zu einem Service auslesen
   * @param type $server_id
   * @return type
   */
  public function getServiceDetail($server_id, $wLog = False, $wUptime = False) {

    $db = new Database('SMT-MONITOR');
    $time = new Time();

    $result = $db->getQuery("SELECT * FROM psm_servers WHERE server_id=:server_id", array(':server_id' => $server_id), True);
    $result = $result['0'];

    $result['last_online'] = $time->ago(new DateTime(date($result['last_online'])));
    $result['last_check'] = $time->ago(new DateTime(date($result['last_check'])));

    if ($wLog === True) {
      $result['log'] = $this->getLog($server_id);
    }

    if ($wUptime === True) {
      $result['uptime'] = $this->getUptime($server_id);
    }

    return $result;
  }

  /**
   * Alle Uptime Daten zu einem Service auslesen
   * @param type $server_id
   * @return type
   */
  public function getUptime($server_id) {
    $db = new Database('SMT-MONITOR');
    return $db->getQuery("SELECT * FROM psm_servers_uptime WHERE server_id=:server_id", array(':server_id' => $server_id), True);
  }

  /**
   * Alle Logfiles zu einem Service auslesen
   * @param type $server_id
   * @return type
   */
  public function getLog($server_id = '') {
    $db = new Database('SMT-MONITOR');
    $ses = Session::getInstance();
    $lim = $ses->get('db_limit');

    if (!empty($server_id)) {
      $result = $db->getQuery("SELECT * FROM psm_log WHERE server_id=:server_id && user_id=:user_id ORDER BY datetime DESC", array(':server_id' => $server_id, ':user_id' => 'sys'), True);
    } else {
      $sum = $db->getQuery("SELECT count(log_id) FROM psm_log WHERE user_id=:user_id ORDER BY datetime DESC", array(':user_id' => 'sys'), True);
      $ses->set('db_summe', $sum['0']['count(log_id)']);

      $result = $db->getQuery("SELECT * FROM psm_log WHERE user_id=:user_id ORDER BY datetime DESC LIMIT " . $lim['start'] . "," . $lim['limit'] . "", array(':user_id' => 'sys'), True);
    }

    for ($i = 0; $i < count($result); $i++) {
      $d = explode('<br/>', $result[$i]['message']);
      $result[$i]['message'] = $d['0'];
      $result[$i]['message'] = str_replace("seite '", 'seite \'<a href="/monitor/detail/' . $result[$i]['server_id'] . '">', $result[$i]['message']);
      $result[$i]['message'] = str_replace("' ist", "</a>' ist", $result[$i]['message']);
    }

    return $result;
  }

  /**
   * Methode zum auslesen aller Services eines bestimmten Systems
   * 
   * @param type $iId
   * @return type
   */
  public function getAllUpdateService($prio) {
    $db = new Database('SMT-ADMIN');

    $newprio = ($prio + 1);
    $server = $db->getQuery("SELECT id,prio,wartung FROM wos_server WHERE prio=:prio", array(':prio' => 3), True);

    if ($prio == 5) {
      $server = $db->getQuery("SELECT id,prio,wartung FROM wos_server WHERE prio=:prio_3 || prio=:prio_2", array(':prio_3' => 3, ':prio_2' => 2), True);
    }

    if ($prio == 10) {
      $server = $db->getQuery("SELECT id,prio,wartung FROM wos_server WHERE prio=:prio_3 || prio=:prio_2 || prio=:prio_1", array(':prio_3' => 3, ':prio_2' => 2, ':prio_1' => 1), True);
    }

    if ($prio == 15) {
      $server = $db->getQuery("SELECT id,prio,wartung FROM wos_server", array(), True);
      $newprio = 0;
    }

    $db = new Database('SMT-MONITOR');
    for ($i = 0; $i < count($server); $i++) {
      if ($server[$i]['wartung'] == 0) {
        $result[] = $db->getQuery("SELECT server_id, type, home_system FROM psm_servers WHERE active=:active && home_system=:home_system", array(':active' => 'yes', ':home_system' => $server[$i]['id']), True);
      }
    }

    $db = new Database('SMT-MONITOR');

    if ($newprio == 0) {
      $db->getQuery("TRUNCATE TABLE `psm_last_update`", array());
    }

    $db->getQuery("INSERT INTO psm_last_update (last_update,counter,updatet) VALUES (:last_update,:counter,:updatet)", array(':last_update' => date('Y-m-d H:i:s'), ':counter' => $newprio, ':updatet' => count($result)));

    return $result;
  }

  /**
   * Methode zum auslesen aller Services eines bestimmten Systems
   * 
   * @param type $iId
   * @return type
   */
  public function getAllSystemService($iId) {
    $db = new Database('SMT-MONITOR');
    $result = $db->getQuery("SELECT server_id FROM psm_servers WHERE home_system=:iId ORDER BY label", array(':iId' => $iId), True);

    for ($i = 0; $i < count($result); $i++) {
      $result[$i] = $this->getServiceDetail($result[$i]['server_id']);
    }

    return $result;
  }

  /**
   * Methode zum auslesen aller Services eines bestimmten Systems
   * 
   * @param type $iId
   * @return type
   */
  public function getAllService($all = True) {
    $db = new Database('SMT-MONITOR');
    $ses = Session::getInstance();
    $lim = $ses->get('db_limit');

    if ($all === False) {
      $sum = $db->getQuery("SELECT count(server_id) FROM psm_servers", array(), True);
      $ses->set('db_summe', $sum['0']['count(server_id)']);

      $db->getQuery("SELECT * FROM psm_servers ORDER BY label LIMIT " . $lim['start'] . "," . $lim['limit'] . "", array());
    } else {
      $db->getQuery("SELECT * FROM psm_servers ORDER BY label", array());
    }

    return $db->getValue();
  }

  /**
   * Methode zum prüfen ob es Services mit einem bestimmten Status gibt
   * 
   * @param type $iId
   * @return type
   */
  public function getHomeStatusService($iId, $sStatus) {
    $db = new Database('SMT-MONITOR');
    $db->getQuery("SELECT * FROM psm_servers WHERE home_system=:iId && status=:status && active=:active", array(':iId' => $iId, ':status' => $sStatus, ':active' => 'yes'));

    return $db->getNumrows();
  }

  /**
   * Methode zum prüfen ob es Services mit einem bestimmten Status gibt
   * 
   * @param type $iId
   * @return type
   */
  public function getHomeMonitorService($iId) {
    $db = new Database('SMT-MONITOR');

    $query = "SELECT * FROM psm_servers WHERE home_system=:iId && active=:active && status=:status && email=:email && pushover=:pushover";
    $value = array(':iId' => $iId, ':active' => 'yes', ':status' => 'off', ':email' => 'no', ':pushover' => 'no');

    $db->getQuery($query, $value);

    return $db->getNumrows();
  }

  /**
   * Methode zum prüfen ob es Services mit einem bestimmten Status gibt
   * 
   * @param type $iId
   * @return type
   */
  public function getRelationStatusService($iId, $sStatus) {
    $db = new Database('SMT-MONITOR');
    $db->getQuery("SELECT * FROM psm_servers WHERE server_id=:iId && status=:status", array(':iId' => $iId, ':status' => $sStatus));

    return $db->getNumrows();
  }

  /**
   * Löschen eines Services
   * @param type $server_id
   */
  public function deleteAllServices($server_id) {
    $db = new Database('SMT-MONITOR');

    $query = "DELETE FROM psm_servers WHERE server_id=:server_id";
    $db->getQuery($query, array(':server_id' => $server_id));

    $query = "DELETE FROM psm_servers_uptime WHERE server_id=:server_id";
    $db->getQuery($query, array(':server_id' => $server_id));

    $query = "DELETE FROM psm_log WHERE server_id=:server_id";
    $db->getQuery($query, array(':server_id' => $server_id));

    $query = "DELETE FROM psm_servers_history WHERE server_id=:server_id";
    $db->getQuery($query, array(':server_id' => $server_id));
  }

  /**
   * Methode zum speichern von Services
   * @param type $post
   * @return type
   */
  public function saveService($post) {
    $db = new Database('SMT-MONITOR');
    $up = new Updater();

    if (isset($post['home_system'])) {
      $return = $post['home_system'];
      $post['user'] = implode(',', $post['user']);

      $query = "INSERT INTO psm_servers (home_system) VALUE (:home_system)";
      $value = array(':home_system' => $post['home_system']);

      $db->getQuery($query, $value);
      $id = $db->getLastID();
    } else {
      $return = $post['return'];
      $id = $post['server_id'];
    }

    foreach ($post as $key => $value) {
      if ($key != 'home_system') {
        $query = "UPDATE psm_servers SET $key=:value WHERE server_id=:id";
        $value = array(':value' => $value, ':id' => $id);
        $db->getQuery($query, $value);
      }
    }

    if ($post['type'] == 'reminder') {
      $db->getQuery("UPDATE psm_servers SET status=:status, isWarning=:isWarning WHERE server_id=:server_id", array(':status' => 'on', ':isWarning' => NULL, ':server_id' => $id));
    }

    $up->update($id);

    return $return;
  }
  
  public function clearLogfile() {
    $db=new Database('SMT-MONITOR');
    $db->getQuery("TRUNCATE psm_log", array(), True);
  }

}

?>