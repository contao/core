<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_install']['installTool'] = array('TYPOlight-Installtool', 'Installtool-Anmeldung');
$GLOBALS['TL_LANG']['tl_install']['locked']      = array('Das Installtool wurde gesperrt', 'Aus Sicherheitsgründen wurde das Installtool gesperrt, nachdem dreimal hintereinander ein falsches Passwort eingegeben wurde. Um es zu entsperren, öffnen Sie die lokale Konfigurationsdatei und setzten Sie <em>installCount</em> auf <em>0</em>.');
$GLOBALS['TL_LANG']['tl_install']['password']    = array('Passwort', 'Bitte geben Sie das Installtool-Passwort ein. Das Installtool-Passwort ist nicht gleich dem TYPOlight Backend-Passwort.');
$GLOBALS['TL_LANG']['tl_install']['changePass']  = array('Installtool-Passwort', 'Um dieses Skript weiter abzusichern, können Sie entweder den Befehl <strong>exit;</strong> in die Datei <strong>typolight/install.php</strong> einfügen, oder diese komplett von Ihrem Server entfernen. In beiden Fällen müssten Sie Änderungen an den Systemeinstellungen dann direkt in der lokalen Konfigurationsdatei erfassen.');
$GLOBALS['TL_LANG']['tl_install']['encryption']  = array('Einen Verschlüsselungsschlüssel erstellen', 'Der Schlüssel wird zur verschlüsselten Datenspeicherung verwendet. Beachten Sie, dass einmal verschlüsselte Daten nur mit diesem Schlüssel wiederhergestellt werden können! Notieren Sie ihn sich daher und ändern Sie ihn nicht, wenn es bereits verschlüsselte Daten gibt. Lassen Sie das Feld leer, um einen zufälligen Schlüssel zu generieren.');
$GLOBALS['TL_LANG']['tl_install']['database']    = array('Datenbankverbindung prüfen', 'Bitte geben Sie Ihre Datenbank-Zugangsdaten ein.');
$GLOBALS['TL_LANG']['tl_install']['update']      = array('Tabellen aktualisieren', 'Beachten Sie, dass der Update-Assistent bisher nur mit MySQL- und MySQLi-Treibern getestet wurde. Wenn Sie eine andere Datenbank verwenden (z.B. Oracle), müssen Sie die Datenbank ggf. manuell installieren bzw. aktualisieren. Durchsuchen Sie in diesem Fall die Unterordner des Verzeichnisses <strong>system/modules</strong> nach <strong>config/database.sql</strong>-Dateien.');
$GLOBALS['TL_LANG']['tl_install']['template']    = array('Ein Template importieren', 'Bitte wählen Sie eine <em>.sql</em>-Datei aus dem <em>templates</em>-Verzeichnis.');
$GLOBALS['TL_LANG']['tl_install']['admin']       = array('Ein Administratorkonto anlegen', 'Wenn Sie die Beispielwebseite importiert haben, lautet der Benutzername des Administrators <strong>k.jones</strong> und das Passwort <strong>kevinjones</strong>. Rufen Sie die Beispielwebseite (Frontend) auf, um weitere Informationen zu erhalten.');
$GLOBALS['TL_LANG']['tl_install']['completed']   = array('Gratulation!', 'Melden Sie sich nun im TYPOlight-Backend an und prüfen Sie die Systemeinstellungen. Rufen Sie danach Ihre Webseite auf, um sicher zu gehen, dass TYPOlight korrekt funktioniert.');
$GLOBALS['TL_LANG']['tl_install']['ftp']         = array('Dateien via FTP bearbeiten', 'Bitte geben Sie Ihre FTP-Zugangsdaten ein, damit TYPOlight Dateien via FTP bearbeiten kann (Safe Mode Hack).');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_install']['accept']         = 'Lizenz akzeptieren';
$GLOBALS['TL_LANG']['tl_install']['beLogin']        = 'TYPOlight Backend-Login';
$GLOBALS['TL_LANG']['tl_install']['passError']      = 'Bitte ändern Sie das Standardpasswort, um unberechtigte Zugriffe zu vermeiden!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm']    = 'Das Standardpasswort wurde geändert.';
$GLOBALS['TL_LANG']['tl_install']['passSave']       = 'Passwort speichern';
$GLOBALS['TL_LANG']['tl_install']['keyError']       = 'Bitte erstellen Sie einen Schlüssel!';
$GLOBALS['TL_LANG']['tl_install']['keyLength']      = 'Der Schlüssel muss mindestens 12 Zeichen lang sein!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm']     = 'Ein Schlüssel wurde erstellt.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate']      = 'Verschlüsselungsschlüssel';
$GLOBALS['TL_LANG']['tl_install']['keySave']        = 'Schlüssel erstellen bzw. speichern';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm']      = 'Datenbankverbindung ok.';
$GLOBALS['TL_LANG']['tl_install']['dbError']        = 'Keine Verbindung zur Datenbank möglich!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver']       = 'Treiber';
$GLOBALS['TL_LANG']['tl_install']['dbHost']         = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername']     = 'Benutzername';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase']     = 'Datenbank';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent']   = 'Dauerhafte Verbindung';
$GLOBALS['TL_LANG']['tl_install']['dbCharset']      = 'Zeichensatz';
$GLOBALS['TL_LANG']['tl_install']['dbPort']         = 'Portnummer';
$GLOBALS['TL_LANG']['tl_install']['dbSave']         = 'Einstellungen speichern';
$GLOBALS['TL_LANG']['tl_install']['updateError']    = 'Die Datenbank ist nicht aktuell!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm']  = 'Die Datenbank ist aktuell.';
$GLOBALS['TL_LANG']['tl_install']['updateSave']     = 'Datenbank aktualisieren';
$GLOBALS['TL_LANG']['tl_install']['importError']    = 'Bitte wählen Sie eine Template-Datei!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm']  = 'Template importiert am %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn']     = 'Alle bestehenden Daten werden gelöscht!';
$GLOBALS['TL_LANG']['tl_install']['templates']      = 'Templates';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate']  = 'Tabellen nicht leeren';
$GLOBALS['TL_LANG']['tl_install']['importSave']     = 'Ein Template importieren';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Alle bestehenden Daten werden gelöscht. Möchten Sie trotzdem fortfahren?';
$GLOBALS['TL_LANG']['tl_install']['adminError']     = 'Bitte füllen Sie alle Felder aus, um ein Administratorkonto zu erstellen!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm']   = 'Ein Administratorkonto wurde erstellt.';
$GLOBALS['TL_LANG']['tl_install']['adminSave']      = 'Ein Administratorkonto erstellen';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Sie haben TYPOlight erfolgreich installiert.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost']        = 'FTP-Hostname';
$GLOBALS['TL_LANG']['tl_install']['ftpPath']        = 'Relativer Pfad zum TYPOlight-Verzeichnis (z.B. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser']        = 'FTP-Benutzername';
$GLOBALS['TL_LANG']['tl_install']['ftpPass']        = 'FTP-Passwort';
$GLOBALS['TL_LANG']['tl_install']['ftpSave']        = 'FTP-Einstellungen speichern';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError']   = 'Keine Verbindung zum FTP-Server %s möglich';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError']   = 'Anmeldung als "%s" fehlgeschlagen';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError']   = 'Das TYPOlight-Verzeichnis %s wurde nicht gefunden';


/**
 * Updater
 */
$GLOBALS['TL_LANG']['tl_install']['CREATE']       = 'Neue Tabellen anlegen';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD']    = 'Neue Spalten anlegen';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Bestehende Spalten ändern';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP']   = 'Bestehende Spalten löschen';
$GLOBALS['TL_LANG']['tl_install']['DROP']         = 'Bestehende Tabellen löschen';

?>