<?php

/**
 * Die Standardklasse der Applikation für Config
 *
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   Library
 */

class Config extends Language {

  /**
   * Konstruktor, Config setzen und Autoloader starten
  **/
  public function __construct() {
    $this->set('url', explode('/', substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1)));

    $this->loadConfig();
    $this->autoLoader();

    $this->registerClass('Session', True, False);
    parent::__construct();
  }

  /**
   * Einlesen der Konfiguration
  **/
  public function loadConfig() {
    $url = $this->get('url');

    if (strlen(filter_input(INPUT_SERVER, 'REQUEST_URI')) > 1) {
      $this->set('controller', $url ['0']);

      if (isset($url ['1']) && !empty($url ['1'])) {
        $this->set('methode', $url ['1']);
      } else {
        $this->set('methode', 'index');
      }
    } else {
      $this->set('controller', 'home');
      $this->set('methode', 'index');
    }
    
    $this->getConfig('assets/config/SMT.ini');

    $project = array();

    $project ['name'] = $this->get('project');
    $project ['path'] = project_path;
    $project ['base'] = project_base;

    define('project_lib', $this->get('p_controller'));
    define('USER_DB', $this->get('USER_DB'));
    define('CONTENT_DB', $this->get('CONTENT_DB'));
    $this->set('project', $project);
  }

  /**
   * Konstruktor, Config setzen und authentifizieren
  **/
  public function autoLoader() {
    if (!is_null($this->get('loader'))) {
      $class = explode(',', $this->get('loader'));

      if (count($class) >= 1 && !empty($class ['0'])) {
        for ($i = 0; $i < count($class); $i ++) {
          $this->registerClass($class [$i]);
        }
      }
    }
  }

}

?>
