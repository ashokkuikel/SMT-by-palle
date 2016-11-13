<?php

/**
 * Klasse zum debuggen
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   Module
 */

class Debugger {

  public function __construct() {
		if(preg_match('/dev./', filter_input(INPUT_SERVER, 'SERVER_NAME'))) {
			include_once project_path.'/library/Plugins/Debug.php';
		}
  }

}
?>

