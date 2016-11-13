<?php

if ($_SESSION['admin'] === False) {
  base::setRoute('home', 'index', TRUE);
}

// Datenbank initiieren
$db = new Database('SMT-ADMIN');
$url = base::get('url');

if (end($url) == 'save') {
  if (!isset($_POST['id'])) {
    $query = "INSERT INTO wos_news (author,titel,nachricht) VALUE (:author, :titel, :nachricht)";
    $value = array(':author' => Session::get('user'), ':titel' => $_POST ['titel'], ':nachricht' => $_POST ['nachricht']);

    $db->getQuery($query, $value);
    header("Location: " . $_SERVER ['HTTP_REFERER']);
  }
}

if (end($url) == 'save') {
  if (isset($_POST['id'])) {
    $query = "UPDATE wos_news SET titel=:titel, nachricht=:nachricht WHERE id=:server_id";
    $value = array(':titel' => $_POST ['titel'], ':nachricht' => $_POST ['nachricht'], ':server_id' => $_POST['id']);

    $db->getQuery($query, $value);
    header("Location: " . $_SERVER ['HTTP_REFERER']);
  }
}

if (in_array('delete', $url)) {
  $query = "DELETE FROM wos_news WHERE id=:id";
  $value = array(':id' => end($url));

  $db->getQuery($query, $value);
  header("Location: " . $_SERVER ['HTTP_REFERER']);
}


$query = "SELECT * FROM wos_news ORDER BY id DESC";
$db->getQuery($query, array(''));

// Eingelesene Nachrichten ins Template übergeben
template::setText('news_edit', $db->getValue());
?>
