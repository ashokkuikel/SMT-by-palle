<?php

/**
 * IP Scan umbauen mit der TCP PORT Liste, allerdings als Cron und in einer MYSQL Tabelle speichern
 */
if (preg_match("|\Ahttps://(www\.)?" . $_SERVER['HTTP_HOST'] . "|", $_SERVER['HTTP_REFERER'])) {
  $ajxData = $_POST;
  echo main($ajxData);
}

function main($ajxData) {
  $message = 'Linux System';
  $fp = @fsockopen($ajxData['ip'] . '.' . $ajxData['port'], '22', $errno, $errstr, 1);
  
  if (!$fp) {
    $fp = @fsockopen($ajxData['ip'] . '.' . $ajxData['port'], '1720', $errno, $errstr, 1);
    $message = 'Host Coll (1720-SIP)';
  }
  
  if (!$fp) {
    $fp = @fsockopen($ajxData['ip'] . '.' . $ajxData['port'], '515', $errno, $errstr, 1);
    $message = 'Drucker';
  }
  
  if (!$fp) {
    $fp = @fsockopen($ajxData['ip'] . '.' . $ajxData['port'], '443', $errno, $errstr, 1);
    $message = 'Webservice (443)';
  }

  if (!$fp) {
    $fp = @fsockopen($ajxData['ip'] . '.' . $ajxData['port'], '135', $errno, $errstr, 1);
    $message = 'Windows System';
  } 

  if (!$fp) {
    $fp = @fsockopen($ajxData['ip'] . '.' . $ajxData['port'], '80', $errno, $errstr, 1);
    $message = 'Webservice (80)';
  }
  

  if (!$fp) {
    $message = '<i>Kein System gefunden</i>';
    $link = False;
  } else {
    $link = True;
  }


  $dns = gethostbyaddr($ajxData['ip'] . '.' . $ajxData['port']);

  if ($dns == $ajxData['ip'] . '.' . $ajxData['port']) {
    if (!$fp) {
      $dns = '<i>ACHTUNG, kein Eintrag</i>';
    } else {
      $dns = '<b>ACHTUNG, kein Eintrag</b>';
    }
  }

  @fclose($fp);

  $return_content = '<table border="0" width="100%"><tr class="list-group-item">';
  $return_content.= '<td style="width:150px;">' . $ajxData['ip'] . '.' . $ajxData['port'] . '</td>';
  $return_content.= '<td style="width:200px;">' . $message . '</td>';
  $return_content.= '<td style="width:300px;">' . $dns . '</td>';
  if ($link === True) {
    $return_content.= '<td style="width:200px;"><a href="/inventory/portscan/' . $ajxData['ip'] . '.' . $ajxData['port'] . '">Portscan</a></td>';
  } else {
    $return_content.= '<td style="width:200px;">Portscan</td>';
  }
  $return_content.= '</tr></table>';

  return $return_content;
}

?>