<?php

$file = new File ();
$page = substr($_SERVER ['REQUEST_URI'], 1);

if (empty($page)) {
  $page = 'index';
}

$content = project_path . $file->getContentDir() . $page . '.html';

if (!file_exists($content)) {
  $content = project_path . $file->getContentDir() . base::get('methode') . '/' . $page . '.html';
}

if (!file_exists($content)) {
  $content = '/template/system/' . $page . '.html';
}

if (file_exists($content)) {
  template::setText('content', file_get_contents($content));
}
?>