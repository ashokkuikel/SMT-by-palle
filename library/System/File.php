<?php

/**
 * Klasse für File und Directory Funktionen
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   System
 */
class File {

  public $DIR_ROOT = project_path;
  public $CONTROLLER = 'controller/';
  public $TEMPLATE = 'template/';
  public $CONTENT = 'content/';

  /**
   * Metohde zum auslesen eines Verzeichnisses
   *
   * @param <string> $sFolder        	
   * @return boolean
   */
  public function readDir($sFolder) {
    $i = 1;
    $o = 1;
    $files = array();
    $error = new Error ();

    if (!is_dir($this->getDir() . $sFolder)) {
      $error->setError('Kann den Ordner ' . $sFolder . ' nicht finden');
    } else {
      $ordner = opendir($this->getDir() . $sFolder);
      while ($liste = readdir($ordner)) {
        if (!strstr($liste, '.')) {
          $files ["ordner"] [$i] ["name"] = $liste;
          $files ["ordner"] [$i] ["date"] = date("d.m.Y", filemtime($this->getDir() . '' . $sFolder . '/' . $files ["ordner"] [$i] ['name']));
          $files ["ordner"] [$i] ["time"] = date("H:i:s", filemtime($this->getDir() . '' . $sFolder . '/' . $files ["ordner"] [$i] ['name']));

          if (is_writable($this->getDir() . '' . $sFolder . '/' . $files ["ordner"] [$i] ['name'])) {
            $files ["ordner"] [$i] ["writeable"] = true;
          } else {
            $files ["ordner"] [$i] ["writeable"] = false;
          }
          $i ++;
        }

        if (strstr($liste, '.') && substr($liste, 0, 1) != '.') {
          $files ["dateien"] [$o] ["name"] = $liste;
          $files ["dateien"] [$o] ["datum"] = date("d.m.Y", filemtime($this->getDir() . '' . $sFolder . '/' . $files ['dateien'] [$o] ['name']));
          $files ["dateien"] [$o] ["zeit"] = date("H:i:s", filemtime($this->getDir() . '' . $sFolder . '/' . $files ['dateien'] [$o] ['name']));
          $files ["dateien"] [$o] ["size"] = round((filesize($this->getDir() . '' . $sFolder . '/' . $files ['dateien'] [$o] ['name']) / 1024), 2);

          if (is_writable($this->getDir() . '' . $sFolder . '/' . $files ['dateien'] [$o] ['name'])) {
            $files ["dateien"] [$o] ["image"] = 'check';
            $files ["dateien"] [$o] ["write"] = true;
          } else {
            $files ["dateien"] [$o] ["image"] = 'fail';
            $files ["dateien"] [$o] ["write"] = false;
          }
          $o ++;
        }
      }
      return $files;
    }
  }

  /**
   * Methode zum ermitteln von Dateidetails
   *
   * @param <string> $path        	
   * @param <string> $size        	
   * @return <string>
   */
  function get_size($path, $size) {
    $measure = "Byte";

    if (!is_dir($path)) {
      $size += filesize($path);
    } else {
      $dir = opendir($path);
      while ($file = readdir($dir)) {
        if (is_file($path . "/" . $file)) {
          $size += filesize($path . "/" . $file);
        }
        if (is_dir($path . "/" . $file) && $file != "." && $file != "..") {
          $size = $this->get_size($path . "/" . $file, $size);
        }
      }
    }

    if ($size >= 1024) {
      $measure = "KB";
      $size = $size / 1024;
    }
    if ($size >= 1024) {
      $measure = "MB";
      $size = $size / 1024;
    }
    if ($size >= 1024) {
      $measure = "GB";
      $size = $size / 1024;
    }

    $size = sprintf("%01.2f", $size);
    $size = $size . " " . $measure;

    return ($size);
  }

  /**
   * Verschienden Rückgabefunktionen für die vereinfachte Handhabung der Ordnerstruktur
   */
  public function getControllerDir() {
    return '/' . $this->CONTROLLER . project_lib;
  }

  public function getControllerPath() {
    return $this->CONTROLLER . project_lib;
  }

  public function getTemplateDir() {
    return '/' . $this->TEMPLATE . project_lib;
  }

  public function getTemplatePath() {
    return $this->TEMPLATE . project_lib;
  }

  public function getContentDir() {
    return $this->getTemplateDir() . '/' . $this->CONTENT;
  }

  public function getContentPath() {
    return $this->getTemplatePath() . '/' . $this->CONTENT;
  }

  public function getDir() {
    return $this->DIR_ROOT;
  }

}

?>