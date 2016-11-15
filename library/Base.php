<?php

/**
 * Die Standardklasse der Applikation
 *
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   Library
 */

class Base extends Content {

  public function __construct() {
    parent::__construct();
  }

  /**
   * Standardsetter der Anwendung
   *
   * @param <string> $sName        	
   * @param <beliebig> $sValue        	
   */
  public function set($sName, $sValue, $text = False) {
    $this->$sName = $sValue;
  }

  /**
   * Standardgetter der Anwendung
   *
   * @param <string> $sName        	
   * @return <beliebig>
   */
  public function get($sName) {
    if (property_exists($this, $sName)) {
      return $this->$sName;
    } else {
      return NULL;
    }
  }

  /**
   * INI Konfiguration oeffnen
   *
   * @param <string> $sFile        	
   */
  public function getConfig($sFile) {
    $aData = array();
    $aData = parse_ini_file($sFile, TRUE);
    $this->readConfig($aData);
  }

  /**
   * Methode zum umleiten bzw.
   * registrieren eines Controllers / Methode
   *
   * @param <type> $controller        	
   * @param <type> $methode        	
   * @param <type> $full        	
   */
  public function setRoute($controller, $methode, $full = TRUE) {
    if ($controller == '') {
      $url = $this->get('url');

      if (is_array($url)) {
        $controller = $url ['0'];
        $methode = $url ['1'];
      } else {
        $controller = 'home';
        $methode = 'index';
      }
    }

    if ($full === TRUE && $controller != 'content') {
      die(header("Location: " . $this->get('getPath') . '/' . $controller . '/' . $methode));
    } elseif ($full === TRUE && $controller == 'content') {
      die(header("Location: " . $this->get('getPath') . '/' . $methode));
    } else {
      $this->set('controller', $controller);
      $this->set('methode', $methode);
    }
  }

  /**
   * Klasse dem System bereit stellen
   *
   * @param <type> $sClass        	
   * @param <type> $bReturn        	
   * @return <type> $gInstance
   */
  public function registerClass($sClass, $gInstance = False, $bReturn = False) {
    if ($gInstance === True) {
      $this->set($sClass, True, False);
    } else {
      if ($gInstance === False) {
        $this->set($sClass, new $sClass());

        if ($bReturn === TRUE) {
          return $this->get($sClass);
        }
      } else {
        $this->set($gInstance, new $sClass($gInstance));

        if ($bReturn === TRUE) {
          return $this->get($gInstance);
        }
      }
    }
  }

  /**
   * Erstellt einen beliebigen Zufallscode
   *
   * @staticvar <string> $code
   * @param <integer> $iLength        	
   * @return <string> code
   */
  public static function createCode($iLength = 8) {
    static $code = '';

    $zeichen = "qwertzupasdfghkyxcvbnm";
    $zeichen .= "123456789";
    $zeichen .= "WERTZUPLKJHGFDSAYXCVBNM";

    srand((double) microtime() * 1000000);
    for ($i = 0; $i < $iLength; $i ++) {
      $code .= substr($zeichen, (rand() % (strlen($zeichen))), 1);
    }

    return $code;
  }

  /**
   * Methode zum speichern eines LogFiles
   *
   * @param <string> $file        	
   * @param <string> $text        	
   */
  public function logFile($file, $text) {
    $folder = $this->get('logfiles') . date("Y") . date("m") . date("d");
    $file = $folder . '/' . $file;
    $text = str_replace('\n', chr(10), $text);
    if (!is_dir($folder)) {
      mkdir($folder, 0777);
    }
    if (file_exists($file)) {
      $text = file_get_contents($file) . $text;
    }
    file_put_contents($file, $text);
  }

  /**
   * Konfigurationsdaten verabeiten und ueber
   * den Standardgetter der Anwedung verarbeiten
   *
   * @param <array> $aData        	
   */
  public function readConfig($aData) {
    foreach ($aData as $name => $value) {
      $this->set($name, $value);
    }
  }

  /**
   * Methode zum erzeugen eines Downloads
   *
   * @param <string> $file        	
   * @param <string> $dir        	
   * @param <string> $type        	
   */
  public function makeDownload($file, $dir, $type) {
    header("Content-Type: $type");
    header("Content-Disposition: attachment; filename=\"$file\"");
    readfile($dir . $file);
  }
  
  /**
   * Methode zur optimerten Darstellung der Speichereinheiten
   *
   * @param <int> $v
   */
  public function size($v) {
    $measure = "Byte";

      if ($v >= 1024) {
      $measure = "KB";
      $v = $v / 1024;
      }
      if ($v >= 1024) {
        $measure = "MB";
        $v = $v / 1024;
      }
      if ($v >= 1024) {
        $measure = "GB";
        $v = $v / 1024;
      }

      $v = sprintf("%01.2f", $v);
      $v =  $v. " " . $measure;

      return $v;
  }

}

?>
