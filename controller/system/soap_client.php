<?php

$server = app::get('soap-server');

/**
 * Diesen Bereich nicht modifizieren
 */
class SoapHeaderAPIToken {

  public $apikey;

  public function __construct($apikey) {
    $this->apikey = $apikey;
  }

}

$wsdl = NULL;
$uri = '/server.php?wsdl';
$location = '/server.php?modul=' . $modul;
$wsu = 'http://schemas.xmlsoap.org/ws/2002/07/utility';

$usernameToken = new SoapHeaderAPIToken($user);
$soapHeaders [] = new SoapHeader($wsu, 'APIToken', $usernameToken);
$client = new SoapClient($wsdl, array(
  "trace" => 1,
  "exceptions" => 1,
  "location" => $server . $location,
  "uri" => $server . $uri
  ));
$client->__setSoapHeaders($soapHeaders);
?>
