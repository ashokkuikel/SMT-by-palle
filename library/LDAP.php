<?php

/**
 * LDAP für die SMT Anwendung
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   SMT
 */
class LDAP {

  // Active Directory server
  public $ldap_host;
  // Active Directory DN
  public $ldap_dn;
  // Active Directory user group
  public $ldap_user_group;
  // Active Directory manager group
  public $ldap_manager_group;
  // Domain, for purposes of constructing $user
  public $ldap_usr_dom;
  // ldap User
  protected $ldap_user;
  // ldap Pass
  protected $ldap_pass;
  // Verbindung wird hier zwischengepseichert
  protected $ldap;
  // Benutzerlogin speichern
  public $isAuth = False;

  public function __construct($user, $pass, $conf) {
    $this->setConfig($conf);
    $this->ldapAuth($user, $pass);
  }
  
  public function getAuth() {
    return $isAuth;
  }
  
  public function setConfig($conf) {
    $this->ldap_host = $conf['ldap_host'];
    $this->ldap_dn = $conf['ldap_dn'];
    $this->ldap_user_group = $conf['ldap_user_group'];
    $this->ldap_manager_group = $conf['ldap_manager_group'];
    $this->ldap_usr_dom = $conf['ldap_usr_dom'];
    $this->ldap_user = $conf['ldap_user'];
    $this->ldap_pass = $conf['ldap_pass'];
  }

  /*
   * Prüfung ob der User eingeloggt ist
   */

  public function checkUser($sUser) {
    // Gruppe prüfen ob Admin
    if (!$_SESSION['admin']) {
      if ($this->ldapGroupSearch('SMT', $sUser)) {
        $_SESSION['admin'] = True;
      } 
    }
  }

  /**
   * Methode zum verbinden zum LDAP
   * @param type $user
   * @param type $pass
   * @return type
   * @throws Exception
   */
  public function ldapAuth($user, $pass) {
    $ldap = ldap_connect($this->ldap_host);

    if ($ldap == false) {
      throw new Exception('LDAP connnect failed');
    }

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $res = @ldap_bind($ldap, $user . $this->ldap_usr_dom, $pass);
    $this->isAuth = $res;
  }

  /**
   * Methode zum verbinden zum LDAP
   * @param type $user
   * @param type $pass
   * @return type
   * @throws Exception
   */
  public function ldapConnect($user, $pass) {
    $ldap = ldap_connect($this->ldap_host);

    if ($ldap == false) {
      throw new Exception('LDAP connnect failed');
    }

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $res = ldap_bind($ldap, $user . $this->ldap_usr_dom, $pass);
    
    if ($res === True && !isset($_SESSION['username'])) {
      $_SESSION['username'] = $user;
    }

    return $ldap;
  }

  /**
   * Auslesen der Email Adresse zu einem Benutzerkürzel
   * @param type $field
   * @param type $search
   * @return type
   */
  public function ldapSearch($field, $sUser) {
    $this->ldap = $this->ldapConnect($this->ldap_user, $this->ldap_pass);
    $result = ldap_search($this->ldap, $this->ldap_dn, "(sAMAccountName=" . $sUser . ")", $field) or exit("Unable to search LDAP server");
    $entries = ldap_get_entries($this->ldap, $result);

    return $entries['0'];
  }

  /**
   * Prüfung ob der User in einer bestimmten Gruppe ist
   * @param type $group
   * @param type $search
   * @return boolean
   */
  public function ldapGroupSearch($group, $search) {
    $return = False;
    $this->ldap = $this->ldapConnect($this->ldap_user, $this->ldap_pass);
    $result = ldap_search($this->ldap, $this->ldap_dn, "(sAMAccountName=" . $search . ")", array('memberof')) or exit("Unable to search LDAP server");
    $entries = ldap_get_entries($this->ldap, $result);

    unset($entries['0']['memberof']['count']);
    $groups = array_merge($entries['0']['memberof']);

    for ($i = 0; $i < count($groups); $i++) {
      if (preg_match("/$group/i", $groups[$i]))
        $return = True;
    }

    return $return;
  }

}
