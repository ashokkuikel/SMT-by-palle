<?php

/**
 * Initialklasse für die SMT Anwendung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */
class Handler extends Texte{

  public $config;
  public $user;

  public function __construct() {
    $session=Session::getInstance();
    
    if(!isset($_SESSION['admin'])) {
      $_SESSION['admin'] = False;
    }
    
    // Config einlesen
    $this->loadConfig();
    // LDAP Authentifizierung
    $this->user = new User($this->config);
    // Prüfung ob es Systemfehler gibt
    $this->checkSystemDNS();
  }

  /**
   * Menü für die Seite einlesen
   * @param type $controller
   * @param type $methode
   * @return string
   */
  public function loadMenu($controller, $methode = '') {
    $db = new Database('SMT-ADMIN');
    
    if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
      $query = "SELECT * FROM wos_submenu WHERE controller=:controller ORDER BY lfdnr";
      $db->getQuery($query, array(':controller' => $controller));
    } else {
      $query = "SELECT * FROM wos_submenu WHERE controller=:controller && admin=:admin ORDER BY lfdnr";
      $db->getQuery($query, array(':controller' => $controller, ':admin' => False));
    }

    $menu = $db->getValue();
    for ($i = 0; $i < count($menu); $i ++) {
      $submenu_content[$i]['link'] = $menu[$i]['controller_ziel'] . '/' . $menu[$i]['methode'];
      $submenu_content[$i]['bezeichnung'] = parent::getText($menu[$i]['bezeichnung']);

      if ($methode == $menu[$i]['methode']) {
        $submenu_content[$i]['aktiv'] = 'active';
      }
    }

    if ($db->getNumrows() > 0) {
      return $submenu_content;
    }
  }

  /**
   * News einlesen
   * @param type $controller
   * @return type
   */
  public function loadNews($controller) {
    $db = new Database('SMT-ADMIN');

    $query = "SELECT * FROM wos_news WHERE controller=:controller || controller=:keineangabe ORDER BY id DESC";
    $db->getQuery($query, array(':controller' => $controller, ':keineangabe' => ''));

    $news = $db->getValue();
    for ($i = 0; $i < count($news); $i ++) {
      $news_content[$i]['titel'] = $news[$i]['titel'];
      $news_content[$i]['nachricht'] = $news[$i]['nachricht'];
      $news_content[$i]['datum'] = $news[$i]['datum'];
      $news_content[$i]['author'] = $news[$i]['author'];
    }

    if ($db->getNumrows() > 0) {
      return $news_content;
    }
  }

  /**
   * Lade Konfiguration für die Seite
   */
  protected function loadConfig() {
    $db = new Database('SMT-ADMIN');

    $query = "SELECT * FROM wos_config";
    $db->getQuery($query, array());

    $config = $db->getValue();

    for ($i = 0; $i < count($config); $i ++) {
      $this->config[$config[$i]['id']] = $config[$i]['value'];
    }
  }

  public function checkSystemDNS() {
    $db = new Database('SMT-ADMIN');
    $session = Session::getInstance();

    $db->getQuery("SELECT * FROM wos_dns_cron", array());
    if ($db->getNumrows() > 0) {
      $session->set('DNS_ALERT', 'Es gibt ' . $db->getNumrows() . ' Fehler im DNS');
    } else {
      unset($_SESSION['DNS_ALERT']);
    }
  }

  public function getLastUpdate() {
    $db = new Database('SMT-MONITOR');
    $result = $db->getQuery("SELECT * FROM psm_last_update ORDER BY last_update DESC LIMIT 1", array(), True);
    
    if($db->getNumrows() > 0) {
      return $result['0'];
    }
  }
}
