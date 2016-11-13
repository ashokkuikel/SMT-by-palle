<?php

/**
 * Klasse zur Blätterfunktion
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   System
 */

class Blaetter {

  public $start; // Aktueller Startwert
  public $ende; // Aktueller Endwert
  public $treffer; // Gesamtanzahl
  public $anzahl; // Anzahl der Ausgabe pro Seite
  public $next = False; // N�chster Startwert
  public $prev = False; // N�chster Endwert
  private static $instance;

  public function __construct() {
  }

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  /**
   * Methode zum setzen von Variablen
   *
   * @param <string> $sName        	
   * @param <integer> $iValue        	
   */
  public function set($sName, $iValue) {
    $this->$sName = (int) $iValue;
  }

  public function get($sName = '') {
    if (!empty($sName)) {
      return $this->$sName;
    } else {
      $w = array();
      $w ['start'] = $this->start;
      $w ['ende'] = $this->ende;
      $w ['anzahl'] = $this->anzahl;
      $w ['treffer'] = $this->treffer;
      $w ['next'] = $this->next;
      $w ['prev'] = $this->prev;

      return $w;
    }
  }

  /**
   * Methode zum setzen des Starts
   *
   * @param <integer> $iAnzahl        	
   */
  public function setStart($iAnzahl) {
    $this->set('start', $iAnzahl);

    if (($this->start + $this->anzahl) < $this->treffer) {
      $this->set('ende', ($this->start + $this->anzahl));
      $this->set('next', $this->ende);
    }

    if ($this->start > 0) {
      $this->set('prev', ($this->start - $this->anzahl));
    }
  }

  /**
   * Methode zum setzen der Anzahl pro Seite
   *
   * @param <integer> $iAnzahl        	
   */
  public function setAnzahl($iAnzahl) {
    $this->set('anzahl', $iAnzahl);
  }
  
  public function checkValues() {
    
  }

}
