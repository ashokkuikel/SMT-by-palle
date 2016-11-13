<?php

/**
 * Klasse zum Sessionhandling
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   System
 */
class Session {

  const SESSION_STARTED = TRUE;
  const SESSION_NOT_STARTED = FALSE;

  private $sessionState = self::SESSION_NOT_STARTED;
  private static $instance;

  public function __construct() {
    
  }

  /**
   * Methode zum instanzieren
   *
   * @return type
   */
  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self ();
    }

    self::$instance->startSession();
    return self::$instance;
  }

  /**
   * Session starten
   *
   * @return <string>
   */
  public function startSession() {
    if ($this->sessionState == self::SESSION_NOT_STARTED) {
      $this->sessionState = session_start();
    }

    $this->set('ID', session_id());
    return $this->sessionState;
  }

  /**
   * Methode zum setzen eines Wertes in der Session
   *
   * @param <string> $name        	
   * @param <string> $value        	
   */
  public static function set($name, $value) {
    $_SESSION [$name] = $value;
  }

  /**
   * Methode zum abfragen eines Wertes aus der Session
   *
   * @param <string> $name        	
   * @return boolean
   */
  public static function get($name) {
    if (isset($_SESSION [$name])) {
      return $_SESSION [$name];
    } else {
      return False;
    }
  }

  /**
   * Methode zum prüfen ob es den Wert in der Session gibt
   *
   * @param <string> $name        	
   * @return <string>
   */
  public function __isset($name) {
    return isset($_SESSION [$name]);
  }

  /**
   * Methode um einen Wert aus der Session zu löschen
   *
   * @param <string> $name        	
   */
  public function __unset($name) {
    unset($_SESSION [$name]);
  }

  /**
   * Methode zum löschen der aktuellen Session
   *
   * @return boolean
   */
  public function destroy() {
    if ($this->sessionState == self::SESSION_STARTED) {
      $this->sessionState = !session_destroy();
      unset($_SESSION);

      return !$this->sessionState;
    }

    return FALSE;
  }

}

?>
