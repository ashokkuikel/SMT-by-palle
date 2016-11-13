<?php

/**
 * Die Standardklasse der Applikation fürs Template
 *
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   Library
 */

class Template {

  public function __construct() {
    include ('Plugins/PHPTAL.php');

    if (!empty($this->vorlage)) {
      $this->vorlage = project_path . '/template/' . $this->get('p_template') . '/' . $this->vorlage . '.xhtml';
    } else {
      $this->vorlage = project_path . '/template/' . $this->get('p_template') . '/' . $this->get('controller') . '.xhtml';
    }
    $this->setText('page_title', $this->get('page_title'));
    $this->set('phptal', new PHPTAL($this->vorlage));
    $this->showOuput();
  }

  public function setText($sName, $sValue) {
    $tpl = $this->get('tpl');
    $number = count($tpl);

    if (!is_array($sName)) {
      $tpl [$number] ['name'] = $sName;
      $tpl [$number] ['value'] = $sValue;
    }

    if (is_array($sName) && !empty($sValue)) {
      $anzahl = count($sName);
      for ($i = 0; $i < $anzahl; $i ++) {
        $tpl [$number] ['name'] = $sName [$i];
        $tpl [$number] ['value'] = $sValue [$i];

        $number ++;
      }
    }

    if (is_array($sName) && empty($sValue)) {
      foreach ($sName as $name => $value) {
        $tpl [$number] ['name'] = $name;
        $tpl [$number] ['value'] = $value;

        $number ++;
      }
    }
    $this->set('tpl', $tpl);
  }

  /**
   * Methode zur Ausgabe der Seite
   */
  public function showOuput() {
    $this->setText('getPath', $this->get('getPath'));
    $this->setText('getPathUrl', $this->get('getPath'));

    if (is_object($this->get('Handler')) && property_exists($this->get('Handler'), 'getPathUrl')) {
      $this->setText('getPathUrl', $this->get('Handler')->get('getPathUrl'));
    }

    $text = $this->get('text');

    if (is_object($this->get('Handler')) && property_exists($this->get('Handler'), 'tpl')) {
      $t ['1'] = $this->get('tpl');
      $t ['2'] = $this->get('Handler')->get('tpl');
      $tpl = array_merge($t ['1'], $t ['2']);
    } else {
      $tpl = $this->get('tpl');
    }

    if (is_array($text)) {
      $this->get('phptal')->text = $text;
    }

    for ($i = 0; $i < count($tpl); $i ++) {
      $name = $tpl [$i] ['name'];
      $value = $tpl [$i] ['value'];

      $this->get('phptal')->$name = $value;
    }

    if (is_object($this->get('Texte'))) {
      $ov = get_object_vars($this->get('Texte'));
      if (isset($ov ['tpl'])) {
        for ($i = 0; $i < count($ov ['tpl']); $i ++) {
          $name = $ov ['tpl'] [$i] ['name'];
          $value = $ov ['tpl'] [$i] ['value'];

          $this->get('phptal')->$name = $value;
        }
      }
    }

    $this->get('phptal')->aSession = $_SESSION;
    $this->get('phptal')->getURL = filter_input(INPUT_SERVER, 'REQUEST_URI');
    $this->showTemplate();
  }

  /**
   * Template ausfuehren / anzeigen
   */
  public function showTemplate() {
    $this->get('phptal')->execute();
  }

}

?>
