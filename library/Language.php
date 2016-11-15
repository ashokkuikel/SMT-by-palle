<?php

/**
 * Die Standardklasse der Applikation für Sprachen
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   Library
 */

class Language extends Base {

  public function __construct() {
    $session = Session::getInstance();
    $language = $session->get('language');

    if (!isset($language) || $language == '') {
      $this->setLanguage();
    }

    parent::__construct();
  }

  /**
   * Methode zum setzen einer Sprache
   * @param string $language        	
   */
  public function setLanguage($language = '') {
    $session = Session::getInstance();

    if (empty($language)) {
      $this->set('language', $this->get('language'));
      $session->set('language', $this->get('language'));
    } else {
      $this->set('language', $language);
      $session->set('language', $language);
    }
  }

  /**
   * Methode zum lesen der aktuelle Sprache
   * @return string
   */
  public function getLanguage() {
    $session = Session::getInstance();
    return $session->get('language');
  }

}

?>