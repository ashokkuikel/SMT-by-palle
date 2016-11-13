# SMT by palle

Server Monitor Tool & Server Management Tool

Einleitung
SMT wurde von einem Administrator für Administratoren entwickelt. Es soll den Alltag mit verschiedenen Systemen vereinfachen und die richtigen Mitarbeiter über die richtigen Ereignisse benachrichtigen. Dazu gehört natürlich auch eine vernünftige und sinnvolle Systemadministration.

Die Unterscheidung sind physische und virtuelle Systeme, die Verwaltung und auch das Monitoring machen hier keine Unterschiede. Die Aufteilung dient lediglich zur Analyse und den Berichten. Mit SMT können Sie verschiedene Dienste und Services überwachen, Reminder konfigurieren und mit regulären Ausdrücken arbeiten.

Aus den erfassten Systemen erzeugt SMT eine exportierbare IP Adressliste, zum Abgleich können Sie mit SMT ihr Netzwerk nach Geräten und Systeme scannen. Desweiteren haben wir einen Portscanner mit Referenztabelle eingebaut, sofern es ein bekannter Port ist wird der entsprechende Name / Verwendungszweck dazu angezeigt und zum System gespeichert.

Eine Besonderheit ist der integrierte “DNS Checker”, dieser wird einmal pro Tag aufgerufen (Cronjob) und listet dann alle bekannten DNS Fehler zu den eingetragenen Systemen, dieses ist gerade in der heutigen Zeit sehr hilfreich. Durch die einfache Bereitstellung von System via Virtualisierung kann man da schon mal den Überblick verlieren.

Zusätzlich haben wir eine Lizenz und Inventarverwaltung integriert, mit dieser kann die IT Abteilung leicht eine Inventur durchführen und Lizenzen Hardwaregeräten oder Images zuweisen.

**Installation SMT**

Die Installation von STM ist einfach, es sind lediglich zwei Schritte notwendig, falls LDAP Anbindung erwünscht sind es drei Schritte. Dazu einfach die Seite aufrufen, der Installationsdialog ertscheint automatisch, nach der Installation gibt es dann den Standarduser “admin” mit dem Passwort “admin” (nach der erfolgreichen Installation bitte den Ordner "install" löschen oder umbenennen).

**Konfiguration des SMT**

Nach der Installation und dem erfolgreichen Login gehen wir in die Konfiguration, hier sind es nur wenige Parameter die angepasst werden müssen. Falls LDAP (Domäne) als Login benutzt werden soll stellen wir das bei “authentication” ein. Standardwert ist hier “intern” (für die lokale DB), bei Umstellung auf “ldap” müssen wir die Parameter der LDAP Konfiguration noch anpassen.

Wichtiger Hinweis zu den Benutzergruppen, SMT unterstützt aktuell 3 Gruppen. Die höchste Gruppe ist der Administrator, dieser ist in der Benutzerdatenbank mit den “adm” Rechten registriert, als weitere Gruppe gibt es den Manager “mod”, dieser kann keine Konfiguration des SMT verändern oder Benutzer verwalten. Der Manager kann aber Devices und Images anlegen, Dienste konfigurieren und zuweisen (vollständige Nutzung des SMT). Die dritte Gruppe ist der User “usr”, dieser kann nur den Status von System einsehen.