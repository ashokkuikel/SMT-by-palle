<?php

if (preg_match("|\Ahttps://(www\.)?" . $_SERVER['HTTP_HOST'] . "|", $_SERVER['HTTP_REFERER'])) {
  $ajxData = $_POST;
  echo main($ajxData);
}

function main($ajxData) {
  $dir_root = dirname(__FILE__);
  define('DIR_ROOT', str_replace('controller' . DIRECTORY_SEPARATOR . 'inventory' . DIRECTORY_SEPARATOR . 'ajax', '', $dir_root));

  include(DIR_ROOT . 'library/System/Database.php');
  
  $ip = $ajxData['ip'];  
  $db = new Database('SMT-ADMIN');  
  $fp = @fsockopen($ip, $ajxData['port'], $errno, $errstr, 0.1);

  if (!$fp) {
    $content = '';
  } else {
    
    $db->getQuery("SELECT * FROM wos_tcp_port WHERE port=:port", array(':port' => $ajxData['port']));  
    
    if ($db->getNumrows() == 1) {
      $bezeichnung  = $db->getValue('bezeichnung', 0);
      $beschreibung = $db->getValue('beschreibung', 0);    
      $content      = ' &nbsp; Port:' . $ajxData['port'] . ' - is open (' . $bezeichnung . ' - ' . $beschreibung . ')<br />';
    } else {
      $bezeichnung  = '(unbekannter Service)';
      $beschreibung = '';      
      $content      = ' &nbsp; Port:' . $ajxData['port'] . ' - is open '.$bezeichnung.'<br />';
    }
    
    $query = "INSERT INTO wos_server_ports (lastcheck,ipadresse,port,bezeichnung,beschreibung) VALUE (:lastcheck,:ipadresse,:port,:bezeichnung,:beschreibung)";
    $value = array(':lastcheck'=>date('Y-m-d H:i:s'),':ipadresse'=>$ip, ':port' => $ajxData['port'], ':bezeichnung'=>$bezeichnung, ':beschreibung'=>$beschreibung);
    $db->getQuery($query, $value);
    
    fclose($fp);
  }

  return $content;
}

?>