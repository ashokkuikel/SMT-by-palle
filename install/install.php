<?php

include_once 'library/System/Database.php';

echo '<head>
    <title>Installation SMT 1.2</title>
    <meta charset="utf-8" />
		<link rel="shortcut icon" href="../assets/images/favicon.ico" />
		<link rel="stylesheet" href="../assets/css/bootstrap.css" />
		<link rel="stylesheet" href="../assets/css/styles.css" />';


if(isset($_POST['aktion']) && $_POST['aktion'] == 'install') {
  
  $file = project_path.'/assets/config/'.project_base.'.ini';
  $current = "";  
  $current .= "project = SMT\n";  
  $current .= "getPath = ".$_POST['getPath']."\n";
  $current .= "iAnzahl = ".$_POST['iAnzahl']."\n";
  $current .= "language = ".$_POST['language']."\n";
  $current .= "country = ".$_POST['language']."\n";
  $current .= "logfiles = ".$_POST['logfiles']."\n";
  $current .= "loader = Handler,Texte\n\n";  
  $current .= "[SMT-ADMIN]\n";  
  $current .= "db_host = ".$_POST['db_host']."\n";  
  $current .= "db_user = ".$_POST['db_user']."\n";
  $current .= "db_pass = ".$_POST['db_pass']."\n";
  $current .= "db_base = ".$_POST['db_name']."\n";
  $current .= "db_charset=utf8\n\n";
  $current .= "[SMT-MONITOR]\n";  
  $current .= "db_host = ".$_POST['db_host']."\n";  
  $current .= "db_user = ".$_POST['db_user']."\n";
  $current .= "db_pass = ".$_POST['db_pass']."\n";
  $current .= "db_base = ".$_POST['db_name']."\n";
  $current .= "db_charset=utf8\n\n";
  $current .= "[SMT-USER]\n";  
  $current .= "db_host = ".$_POST['db_host']."\n";  
  $current .= "db_user = ".$_POST['db_user']."\n";
  $current .= "db_pass = ".$_POST['db_pass']."\n";
  $current .= "db_base = ".$_POST['db_name']."\n";
  $current .= "db_charset=utf8\n";  
  file_put_contents($file, $current);
  
  $db = new Database('SMT-ADMIN');
  $db->importSQL(project_path.'/install/server_admin.sql');
  $db->importSQL(project_path.'/install/server_daten.sql');
  
  echo '<center><h4>Installation erfolgreich !!!<br /><br />';
  echo '<a href="'.$_POST['getPath'].'">Hier geht es weiter</a><br /><br />!!! vorher den install Ordner löschen oder umbenennen !!!</h4></center>';
} else {

echo '<div class="container-fluid" style="margin-top:-50px;">
				<div class="col-sm-8 col-sm-push-4">
          <h4>Installation von SMT 1.2</h4>
          <form name="install" method="post" action="/index.php">
          <input type="hidden" name="aktion" value="install" />
          Folgende Dateien werden bei der Installation angelegt:<br /><br />
          <li>
          /assets/config/SMT.ini<br />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Globale Konfiguration wie Pfad, Anzahl der Treffer pro Seite und die Standarsprache setzen
          </li><br />
          <li>
          /assets/config/mysql.ini<br />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datenbankkonfiguration für die Datenbank. Der Benutzer muss die Datenbank (server_admin) anlegen dürfen.
          </li>
          <br />
          Nach der Anpassung bitte auf den folgenden Button klicken, die Datenbanken werden dann eingerichtet und der Standardbenutzer<br/>
          mit dem Benutzernamen "<b>admin</b>" und Passwort "<b>admin</b>" wird dann eingerichtet. Nach der Installation das Verzeichnis "install" löschen.
          <br /><br />
          Andernfalls kommt bei jedem Aufruf der Seite dieser Installationsdialog
          <br /><br />

          <b>Den Pfad zur Anwednung:</b><input style="width:500px;" type="text" name="getPath" class="form-control" value="http://'.$_SERVER['HTTP_HOST'].'" /><br />
          <b>Anzahl der Ergebnisse pro Seite:</b><input style="width:500px;" type="text" name="iAnzahl" class="form-control" value="20" /><br />
          <b>Die Standardsprachen angeben:</b><input style="width:500px;" type="text" name="language" class="form-control" value="de" /><br />
          <b>Speicherort der Logfiles:</b><input style="width:500px;" type="text" name="logfiles" class="form-control" value="/assets/logfile/" /><br /><br />
           

          <b>Hostnamen des Mysql Server:</b><input style="width:500px;" type="text" name="db_host" class="form-control" value="localhost" /><br />
          <b>Datenbankname (muss vorhanden sein):</b><input style="width:500px;" type="text" name="db_name" class="form-control" value="server_admin" /><br />
          <b>Benutzernamen zur Mysql Verbindung:</b><input style="width:500px;" type="text" name="db_user" class="form-control" value="root" /><br />
          <b>Passwort des Mysql Benutzers:</b><input style="width:500px;" type="text" name="db_pass" class="form-control" value="" /><br />
      
          <br /><input type="submit" value="Installation starten" class="btn btn-success">
          </form>
      </div>';

echo '</div>';
}