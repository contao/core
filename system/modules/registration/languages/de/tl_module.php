<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
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
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['reg_groups']     = array('Mitgliedergruppen', 'Bitte weisen Sie den neuen Benutzer einer oder mehreren Mitgliedergruppen zu.');
$GLOBALS['TL_LANG']['tl_module']['reg_allowLogin'] = array('Login erlauben', 'Wenn Sie diese Option wählen, kann sich der neue Benutzer mit seinen Benutzernamen und Passwort anmelden.');
$GLOBALS['TL_LANG']['tl_module']['reg_assignDir']  = array('Ein Benutzerverzeichnis anlegen', 'Wählen Sie diese Option um automatisch ein Benutzerverzeichnis aus dem Benutzernamen zu erstellen.');
$GLOBALS['TL_LANG']['tl_module']['reg_homeDir']    = array('Benutzerverzeichnis', 'Bitte wählen Sie einen Stammordner in dem das neue Benutzerverzeichnis erstellt wird.');
$GLOBALS['TL_LANG']['tl_module']['reg_activate']   = array('Aktivierungsmail verschicken', 'Wählen Sie diese Option um eine Aktivierungsmail an den registrierten Benutzer zu senden.');
$GLOBALS['TL_LANG']['tl_module']['reg_text']       = array('Aktivierungsmail', 'Bitte geben Sie den Text der Aktivierungsmail ein. Sie können alle Eingabefelder als Platzhalter benutzen (z.B. <em>##lastname##</em>) sowie <em>##domain##</em> (aktuelle Domain) und <em>##link##</em> (Aktivierungslink).');
$GLOBALS['TL_LANG']['tl_module']['reg_password']   = array('Bestätigungsmail', 'Bitte geben Sie den Text der Bestätigungsmail ein. Sie können alle Benutzereigenschaften als Platzhalter benutzen (z.B. <em>##lastname##</em>) sowie <em>##domain##</em> (aktuelle Domain) und <em>##link##</em> (Aktivierungslink).');
$GLOBALS['TL_LANG']['tl_module']['reg_skipName']   = array('Benutzernamen nicht abfragen', 'Den Benutzernamen im "Passwort vergessen" Fomular nicht abfragen.');
$GLOBALS['TL_LANG']['tl_module']['reg_jumpTo']     = array('Weiterleitung nach Aktivierung', 'Bitte legen Sie fest, auf welche Seite ein Benutzer nach der Aktivierung seines Kontos weitergeleitet wird.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['emailText']    = array('Ihre Registrierung auf %s', "Vielen Dank für Ihre Registrierung auf ##domain##.\n\nBitte klicken Sie ##link## um Ihre Registrierung abzuschließen und Ihr Konto zu aktivieren. Wenn Sie keinen Zugang angefordert haben, ignorieren Sie diese E-Mail bitte.\n");
$GLOBALS['TL_LANG']['tl_module']['passwordText'] = array('Ihr Passwort Anfrage auf %s', "Sie haben ein neues Passwort für ##domain## angefordert.\n\nBitte klicken Sie ##link## um das neue Passwort festzulegen. Wenn Sie diese E-Mail nicht angefordert haben, kontaktieren Sie bitte den Administrator der Webseite.\n");

?>