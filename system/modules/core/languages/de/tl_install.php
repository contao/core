<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/de/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Installation';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Installtool-Anmeldung';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Das Installtool wurde gesperrt';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Aus Sicherheitsgründen wurde das Installtool gesperrt, nachdem dreimal hintereinander ein falsches Passwort eingegeben wurde. Um es zu entsperren, öffnen Sie die lokale Konfigurationsdatei und setzen Sie <em>installCount</em> auf <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Passwort';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Bitte geben Sie das Installtool-Passwort ein. Das Installtool-Passwort ist nicht gleich dem Contao Backend-Passwort.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Installtool-Passwort';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Um das Contao-Installtool zusätzlich abzusichern, können Sie die Datei <strong>contao/install.php</strong> nach der Installation von Contao entweder umbenennen oder komplett von Ihrem Server entfernen.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Einen Verschlüsselungsschlüssel erstellen';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Der Schlüssel wird zur verschlüsselten Datenspeicherung verwendet. Beachten Sie, dass einmal verschlüsselte Daten nur mit diesem Schlüssel wiederhergestellt werden können! Notieren Sie ihn sich daher und ändern Sie ihn nicht, wenn es bereits verschlüsselte Daten gibt. Lassen Sie das Feld leer, um einen zufälligen Schlüssel zu generieren.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Datenbankverbindung';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Bitte geben Sie nachfolgend Ihre Datenbank-Zugangsdaten ein.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Kollation';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Weitere Informationen finden Sie im <a href="http://dev.mysql.com/doc/refman/5.1/de/charset-unicode-sets.html" target="_blank">MySQL-Handbuch</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Tabellen prüfen';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Beachten Sie, dass der Update-Assistent bisher nur mit MySQL- und MySQLi-Treibern getestet wurde. Wenn Sie eine andere Datenbank verwenden (z.B. Oracle), müssen Sie die Datenbank ggf. manuell installieren bzw. aktualisieren.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Ein Template importieren';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Hier können Sie eine <em>.sql</em>-Datei aus dem <em>templates</em>-Verzeichnis mit einer vorkonfigurierten Beispielwebseite importieren. Dabei werden alle bestehenden Daten gelöscht! Wenn Sie stattdessen lediglich ein Theme importieren wollen, nutzen Sie bitte den Theme-Manager im Contao-Backend.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Ein Administratorkonto anlegen';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Wenn Sie die Beispielwebseite importiert haben, lautet der Benutzername des Administrators <strong>k.jones</strong> und das Passwort <strong>kevinjones</strong>. Rufen Sie die Beispielwebseite (Frontend) auf, um weitere Informationen zu erhalten.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Gratulation!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Melden Sie sich nun im <a href="contao/">Contao-Backend</a> an und prüfen Sie die Systemeinstellungen. Rufen Sie danach Ihre Webseite auf, um sicher zu gehen, dass Contao korrekt funktioniert.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Dateien via FTP bearbeiten';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Ihr Server unterstützt den Dateizugriff per PHP nicht; vermutlich läuft PHP als Apache-Modul unter einem anderen Benutzer. Bitte geben Sie daher Ihre FTP-Zugangsdaten ein, damit Contao Dateien via FTP bearbeiten kann (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Lizenz akzeptieren';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao Backend-Login';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Bitte vergeben Sie ein Passwort, um unberechtigte Zugriffe zu vermeiden!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Ein individuelles Passwort wurde hinterlegt.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Passwort speichern';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Bitte erstellen Sie einen Schlüssel!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Der Schlüssel muss mindestens 12 Zeichen lang sein!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Ein Schlüssel wurde erstellt.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Verschlüsselungsschlüssel';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Schlüssel erstellen bzw. speichern';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Datenbankverbindung hergestellt.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Keine Verbindung zur Datenbank vorhanden!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Treiber';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Benutzername';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Datenbank';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Dauerhafte Verbindung';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Zeichensatz';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Kollation';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Portnummer';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Einstellungen speichern';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Das Ändern der Kollation betrifft alle Tabellen mit <em>tl_</em>-Präfix.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Die Datenbank ist nicht aktuell!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Die Datenbank ist aktuell.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Datenbank aktualisieren';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Kollation ändern';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Es scheint, als würden Sie ein Update von einer Contao-Version kleiner als %s durchführen. Falls das zutrifft, ist es <strong>unbedingt notwendig, das Version %s-Update auszuführen</strong>, um die Integrität der Daten zu gewährleisten!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Version %s-Update ausführen';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Version %s-Update – Schritt %s ausführen';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Der Import konnte nicht abgeschlossen werden! Ist die Datenbankstruktur aktuell und die Template-Datei kompatibel mit Ihrer Contao-Version?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Bitte wählen Sie eine Template-Datei!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Template importiert am %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Alle bestehenden Daten werden gelöscht!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Templates';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Tabellen nicht leeren';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Ein Template importieren';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Alle bestehenden Daten werden gelöscht. Möchten Sie trotzdem fortfahren?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Bitte füllen Sie alle Felder aus, um ein Administratorkonto zu erstellen!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Ein Administratorkonto wurde erstellt.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Ein Administratorkonto erstellen';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Sie haben Contao erfolgreich installiert.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP-Hostname';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relativer Pfad zum Contao-Verzeichnis (z.B. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP-Benutzername';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP-Passwort';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Sichere Verbindung';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Über FTP-SSL verbinden';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP-Port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'FTP-Einstellungen speichern';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Keine Verbindung zum FTP-Server %s möglich';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Anmeldung als "%s" fehlgeschlagen';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Das Contao-Verzeichnis %s wurde nicht gefunden';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Das konfigurierte Upload-Verzeichnis existiert nicht!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Haben Sie das <strong>tl_files</strong>-Verzeichnis in <strong>files</strong> umbenannt? Sie können den Ordner nicht einfach umbenennen, weil die Referenzen in der Datenbank und in den Stylesheets weiterhin auf das alte Verzeichnis verweisen. Um den Ordnernamen zu ändern, führen Sie die Umbenennung nach dem Version 3-Update durch und passen Sie die Daten in der Datenbank mit folgendem Skript an: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Neue Tabellen anlegen';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Neue Spalten anlegen';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Bestehende Spalten ändern';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Bestehende Spalten löschen';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Bestehende Tabellen löschen';
