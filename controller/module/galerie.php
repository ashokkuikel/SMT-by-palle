<?php

$url = base::get('url');
$galerie = base::registerClass('Galerie', False, True);

$folders = $galerie->readFolders();

if (count($url) > 2) {
  $read = '/' . end($url);
  $images = $galerie->readImages($read);

  template::setText('read', $read);
  template::setText('bilder', $images);
}

template::setText('ordner', $folders);
template::setText('modul', True);
?>

