<?php

$url = base::get('url');
$galerie = base::registerClass('Galerie', False, True);
template::setText('maxsize', ini_get('upload_max_filesize'));

// Methode ausführen
if (end($url) == 'upload') {
  $galerie->uploadImage($_POST ['ordner'], $_FILES);
  header("Location:" . $_SERVER ['HTTP_REFERER']);
}

if (in_array('delete', $url) && end($url) != 'delete') {
  $galerie->deleteImage($_SERVER ['REQUEST_URI']);
  header("Location:" . $_SERVER ['HTTP_REFERER']);
}

if (in_array('create', $url)) {
  $galerie->createFolder($_POST ['galerie']);
  header("Location: " . base::get('getPath') . '/admin/galerie/' . $_POST ['galerie']);
}

if (in_array('delete', $url) && end($url) == 'delete') {
  $galerie->deleteFolder($_SERVER ['REQUEST_URI']);

  base::setRoute('admin', 'galerie', True);
}

if (in_array('import', $url)) {
  session::set('HTTP_REFERER', $_SERVER ['HTTP_REFERER']);
  $galerie->importImages($url ['2']);
}

// Ende der Methoden
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

