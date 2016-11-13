<?php

/**
 * Klasse Bearbeitung von Bildern
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   System
 */
class image {

  public $width = 1280;
  public $height = 800;
  public $twidth = 100;
  public $theight = 125;

  /**
   * Methode zum erneuern eines Bildes nach vorgegebenen Maßen inklsusive Erstellung
   * eines Thumbnails mit Wasserzeichen
   *
   * @param <string> $image        	
   */
  public function resize($image) {
    $file = new File ();

    $datei = explode('/', $image);
    $ordner = $datei ['0'];
    $image = $datei ['1'];

    $size = getimagesize(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
    $breite = $size [0];
    $hoehe = $size [1];
    $bildtyp = $size [2];

    if ($breite > $this->width) {
      $neueBreite = $this->width;
      $neueHoehe = intval($hoehe * $neueBreite / $breite);

      $altesBild = ImageCreateFromJPEG(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
      $neuesBild = imagecreatetruecolor($neueBreite, $neueHoehe);

      ImageCopyResampled($neuesBild, $altesBild, 0, 0, 0, 0, $neueBreite, $neueHoehe, $breite, $hoehe);
      ImageJPEG($neuesBild, project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
    }

    $thumbsize = getimagesize(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
    $thumbbreite = $thumbsize [0];
    $thumbhoehe = $thumbsize [1];

    $thumb_neueBreite = $this->width;
    $thumb_neueHoehe = intval($thumbhoehe * $thumb_neueBreite / $thumbbreite);

    $thumb_altesBild = ImageCreateFromJPEG(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
    $thumb_neuesBild = imagecreatetruecolor($thumb_neueBreite, $thumb_neueHoehe);

    ImageCopyResampled($thumb_neuesBild, $thumb_altesBild, 0, 0, 0, 0, $thumb_neueBreite, $thumb_neueHoehe, $thumbbreite, $thumbhoehe);
    ImageJPEG($thumb_neuesBild, project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/thumb_' . $image);

    $thumbsize = getimagesize(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/thumb_' . $image);
    $thumbbreite = $thumbsize [0];
    $thumbhoehe = $thumbsize [1];

    if ($thumbhoehe > $this->theight) {
      $thumb_neueHoehe = $this->theight;
      $thumb_neueBreite = intval($thumbbreite * $thumb_neueHoehe / $thumbhoehe);

      $thumb_altesBild = ImageCreateFromJPEG(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/thumb_' . $image);
      $thumb_neuesBild = imagecreatetruecolor($thumb_neueBreite, $thumb_neueHoehe);

      ImageCopyResampled($thumb_neuesBild, $thumb_altesBild, 0, 0, 0, 0, $thumb_neueBreite, $thumb_neueHoehe, $thumbbreite, $thumbhoehe);
      ImageJPEG($thumb_neuesBild, project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/thumb_' . $image);
    }

    /*
    // Wasserzeichen ins Bild laden
    $size = getimagesize(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
    $width = $size [0];
    $height = $size [1];

    $old_picture = imagecreatefromjpeg(project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
    $new_picture = imagecreatetruecolor($width, $height);

    imagecopyresampled($new_picture, $old_picture, 0, 0, 0, 0, $width, $height, $width, $height);

    $transition = 50;
    $watermarkfile = imagecreatefrompng(project_path . $file->getContentDir() . 'bilder/wasserzeichen.png');
    $waternarkpic_width = imagesx($watermarkfile);
    $waternarkpic_height = imagesy($watermarkfile);
    $watermarkdest_x = $width / 2 - ($waternarkpic_width / 2);
    $watermarkdest_y = $height / 2 - ($waternarkpic_height / 2);

    imagecopymerge($new_picture, $watermarkfile, 0, 0, 0, 0, $waternarkpic_width, $waternarkpic_height, $transition);
    imagejpeg($new_picture, project_path . $file->getContentDir() . 'galerie' . '/' . $ordner . '/' . $image);
    */
  }

}

?>
