<?php

/**
 * SSH Klasse für die SMT Anwedung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */

class SSH {

  private $host = 'host';
  private $user = 'root';
  private $port = '22';
  private $password = 'DEFAULT ROOT PASSWORD!';
  private $con = null;
  private $shell_type = 'xterm';
  private $shell = null;
  private $log = '';

  public function __construct($host = '', $user = '', $password = '') {
    if ($host != '')
      $this->host = $host;
    if ($user != '')
      $this->user = $user;
    if ($password != '')
      $this->password = $password;

    $this->con = ssh2_connect($this->host, $this->port);
    if (!$this->con) {
      $this->log .= "Connection failed !";
    }
  }

  /**
   * Methode zum authentifizieren an der Shell
   * @param type $user
   * @param type password
   */
  protected function authPassword($user = '', $password = '') {
    if ($user != '')
      $this->user = $user;
    if ($password != '')
      $this->password = $password;

    if (!ssh2_auth_password($this->con, $this->user, $this->password)) {
      $this->log .= "Authorization failed !";
    }
  }

  /**
   * Methode zum ausführen des Kommandos auf der Shell
   */
  public function cmdExec() {
    $this->authPassword();
    $argc = func_num_args();
    $argv = func_get_args();

    $cmd = '';
    for ($i = 0; $i < $argc; $i++) {
      if ($i != ($argc - 1)) {
        $cmd .= $argv[$i] . " && ";
      } else {
        $cmd .= $argv[$i];
      }
    }

    $stream = ssh2_exec($this->con, $cmd);
    stream_set_blocking($stream, true);
    return fread($stream, 4096);
  }

  /**
   * Methode zum lesen des logs
   */
  public function getLog() {
    return $this->log;
  }

}

?>