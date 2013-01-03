<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Hier können Sie die Sicherheitsfrage abschalten (nicht empfohlen).');
$GLOBALS['TL_LANG']['tl_module']['reg_groups']     = array('Mitgliedergruppen', 'Hier können Sie den Benutzer einer oder mehreren Gruppen zuweisen.');
$GLOBALS['TL_LANG']['tl_module']['reg_allowLogin'] = array('Login erlauben', 'Dem neuen Benutzer die Anmeldung im Frontend erlauben.');
$GLOBALS['TL_LANG']['tl_module']['reg_skipName']   = array('Benutzernamen nicht abfragen', 'Den Benutzernamen bei der Passwort-Anforderung nicht abfragen.');
$GLOBALS['TL_LANG']['tl_module']['reg_close']      = array('Modus', 'Hier legen Sie fest, wie die Löschung ausgeführt wird.');
$GLOBALS['TL_LANG']['tl_module']['reg_assignDir']  = array('Ein Benutzerverzeichnis anlegen', 'Ein Benutzerverzeichnis aus dem registrierten Benutzernamen erstellen.');
$GLOBALS['TL_LANG']['tl_module']['reg_homeDir']    = array('Pfad zum Benutzerverzeichnis', 'Bitte wählen Sie das übergeordnete Verzeichnis aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_module']['reg_activate']   = array('Aktivierungsmail verschicken', 'Eine Aktivierungsmail an die registrierte E-Mail-Adresse senden.');
$GLOBALS['TL_LANG']['tl_module']['reg_jumpTo']     = array('Bestätigungsseite', 'Bitte wählen Sie die Seite aus, zu der Benutzer nach Abarbeitung der Anfrage weitergeleitet werden.');
$GLOBALS['TL_LANG']['tl_module']['reg_text']       = array('Aktivierungsmail', 'Sie können die Platzhalter <em>##domain##</em> (Domainname), <em>##link##</em> (Aktivierungslink) sowie alle Eingabefelder (z.B. <em>##lastname##</em>) benutzen.');
$GLOBALS['TL_LANG']['tl_module']['reg_password']   = array('Bestätigungsmail', 'Sie können die Platzhalter <em>##domain##</em> (Domainname), <em>##link##</em> (Aktivierungslink) sowie alle Benutzereigenschaften (z.B. <em>##lastname##</em>) verwenden.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['account_legend'] = 'Konto-Einstellungen';


/**
 * Default messages
 */
$GLOBALS['TL_LANG']['tl_module']['emailText']    = array('Ihre Registrierung auf %s', "Vielen Dank für Ihre Registrierung auf ##domain##.\n\nBitte klicken Sie ##link## um Ihre Registrierung abzuschließen und Ihr Konto zu aktivieren. Wenn Sie keinen Zugang angefordert haben, ignorieren Sie bitte diese E-Mail.\n");
$GLOBALS['TL_LANG']['tl_module']['passwordText'] = array('Ihre Passwort-Anforderung auf %s', "Sie haben ein neues Passwort für ##domain## angefordert.\n\nBitte klicken Sie ##link## um das neue Passwort festzulegen. Wenn Sie diese E-Mail nicht angefordert haben, kontaktieren Sie bitte den Administrator der Webseite.\n");


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['close_deactivate'] = 'Konto deaktivieren';
$GLOBALS['TL_LANG']['tl_module']['close_delete']     = 'Konto unwiderruflich löschen';

?>