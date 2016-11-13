<?php

/**
 * Die Standardklasse der Applikation für Content
 *
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   Library
 */

class Content extends Template {

  public function __construct() {
    $this->loadController();
    $this->autoLoader();
    parent::__construct();
  }

  /**
   * Methode zum laden des Controllers
   * wenn kein Controller gefunden wird
   * wird der Content Controller geladen
   */
  private function loadController() {
    $session = Session::getInstance();

    if (is_object($this->get('Handler'))) {
      $ov = get_object_vars($this->get('Handler'));
      if (isset($ov ['controller']) && !empty($ov ['controller'])) {
        $this->set('controller', $ov ['controller']);
        $this->set('methode', $ov ['methode']);
      }
    }

    // Auf Projektcontroller prüfen
    if (file_exists(project_path . '/controller/' . $this->get('p_controller') . '/' . $this->get('controller') . '.php')) {
      $controller = project_path . '/controller/' . $this->get('p_controller') . '/' . $this->get('controller') . '.php';
    }

    // Auf Modulcontroller prüfen
    if (!isset($controller) && file_exists(project_path . '/controller/module/' . $this->get('controller') . '.php')) {
      $controller = project_path . '/controller/module/' . $this->get('controller') . '.php';
    }

    if (isset($controller) && file_exists(project_path . '/controller/module/' . $this->get('controller') . '/' . $this->get('methode') . '.php')) {
      $controller = project_path . '/controller/module/' . $this->get('controller') . '.php';
    }

    // Auf Systemcontroller prüfen
    if (!isset($controller) && file_exists(project_path . '/controller/system/' . $this->get('controller') . '.php')) {
      $controller = project_path . '/controller/module/' . $this->get('controller') . '.php';
    }

    if (isset($controller) && file_exists(project_path . '/controller/system/' . $this->get('controller') . '/' . $this->get('methode') . '.php')) {
      $controller = project_path . '/controller/system/' . $this->get('controller') . '/' . $this->get('methode') . '.php';
    }

    // Falls kein Controller gefunden wird ist es automatisch der content controller
    if (!isset($controller)) {
      $this->set('controller', 'content');
      $controller = project_path . '/controller/system/content.php';
    }

    template::setText('controller', $this->get('controller'));
    template::setText('methode', $this->get('methode'));
    template::setText('template', $this->get('p_template'));

    $session->set('controller', $this->get('controller'));
    $session->set('methode', $this->get('methode'));

    require_once $controller;
  }

  /**
   * Methode zum einlesen und übergeben des Subcontroller
   */
  public function getSubcontroller() {
    // Auf Projektcontroller prüfen
    if (file_exists(project_path . '/controller/' . $this->get('p_controller') . '/' . $this->get('controller') . '/' . $this->get('methode') . '.php')) {
      $sVorlage = $this->get('p_controller');
    }

    // Auf Modulcontroller prüfen
    if (file_exists(project_path . '/controller/module/' . $this->get('controller') . '/' . $this->get('methode') . '.php')) {
      $sVorlage = 'module';
    }

    // Auf Systemcontroller prüfen
    if (file_exists(project_path . '/controller/system/' . $this->get('controller') . '/' . $this->get('methode') . '.php')) {
      $sVorlage = 'system';
    }
    
    if (!is_null($this->get('methode'))) {
      $subcontroller = project_path . '/controller/' . $sVorlage . '/' . $this->get('controller') . '/' . $this->get('methode') . '.php';
    }

    if (!file_exists($subcontroller) && !is_null($this->get('methode'))) {
      $subcontroller = project_path . '/controller/system/' . $this->get('controller') . '/' . $this->get('methode') . '.php';
    }

    if (isset($subcontroller) && file_exists($subcontroller)) {
      return $subcontroller;
    }
  }

  private function autoLoader() {
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
