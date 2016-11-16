<?php

/**
 * Die Basisklasse ohne Methoden
 * <code>
 * define('project_path', dirname(__FILE__) );
 * define('project_base', filter_input(INPUT_SERVER, 'HTTP_HOST'));
 * include('library/App.php');
 * $App = new App;
 * </code>
 *
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   Library
 */

spl_autoload_register('CMS_autoload');

/**
 * globale APP
 *
 * @author Werner Pallentin
 * @version 0.3
 */

class App extends Config {

  public function __construct() {
    parent::__construct();
  }

}

$App = new App ();

/**
 * 
 * @param type $class
 */
function CMS_autoload($class) {
  // Erlabute folder für Klassen
  $allowed = array(
      'Module',
      'Plugins'
  );

  if (defined('project_lib')) {
    $allowed [] = project_lib;
  }

  // Dürfen nur im eigenen Projektverzeichnis existieren (exklusive Klasse)
  $globale = array(
      'Handler',
      'Texte'
  );

  // Aktuellen folder einlesen
  $folder = scandir(dirname(__FILE__));

  for ($i = 0; $i < count($folder); $i ++) {
    if (!strstr($folder [$i], '.')) {
      if (in_array($class, $globale) && in_array($folder [$i], $allowed) || !in_array($class, $globale)) {
        $class_folder [$i] = scandir(dirname(__FILE__) . '/' . $folder [$i]);
        for ($c = 0; $c < count($class_folder [$i]); $c ++) {
          if (is_dir(dirname(__FILE__) . '/' . $folder [$i] . '/' . $class_folder [$i] [$c])) {
            $sub_class_folder [$i] = scandir(dirname(__FILE__) . '/' . $folder [$i] . '/' . $class_folder [$i] [$c]);
            for ($s = 0; $s < count($sub_class_folder [$i]); $s ++) {
              $ClassName = str_replace('.php', '', $sub_class_folder [$i] [$s]);
              if ($ClassName == $class) {
                require_once dirname(__FILE__) . '/' . $folder [$i] . '/' . $class_folder [$i] [$c] . '/' . $sub_class_folder [$i] [$s];
              }
            }
          }

          if (strstr($class_folder [$i] [$c], '.php')) {
            $ClassName = str_replace('.php', '', $class_folder [$i] [$c]);
            if ($ClassName == $class) {
              require_once dirname(__FILE__) . '/' . $folder [$i] . '/' . $class_folder [$i] [$c];
            }
          }
        }
      }
    }

    if (strstr($folder [$i], '.php')) {
      $ClassName = str_replace('.php', '', $folder [$i]);
      if ($ClassName == $class) {
        require_once dirname(__FILE__) . '/' . $folder [$i];
      }
    }
  }
}
 
?>
