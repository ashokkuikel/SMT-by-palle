<?php

/**
 * Klasse zum Errorhandling
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   System
 */
class Error extends Exception {

  const ERROR_FILE = 'assets/logfile/error.log';
  const ERROR_WRITE = False;
  const ERROR_SCREEN = True;

  /**
   * Methode zum schreiben eines Fehlers in die LogDatei
   *
   * @param <string> $message        	
   */
  public function setError($message) {
    $error_date = date("d.m.Y") . ' - ' . date("H:i") . 'Uhr: ';
    $error_url = filter_input(INPUT_SERVER, 'REQUEST_URI');
    $message = $error_url . chr(10) . $message . chr(10);

    if (self::ERROR_SCREEN === TRUE) {
      echo $message . '<br />';
    }

    if (self::ERROR_WRITE === TRUE) {
      $file = self::ERROR_FILE;
      $current = file_get_contents($file) . $message;
      file_put_contents($file, $current);
    }
  }

}

?>
