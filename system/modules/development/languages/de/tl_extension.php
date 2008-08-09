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
 * @package    Development
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_extension']['title']       = array('Titel', 'Bitte geben Sie einen Titel ein.');
$GLOBALS['TL_LANG']['tl_extension']['folder']      = array('Ordnername', 'Bitte geben Sie einen eindeutigen Ordnernamen ein. Dieser Ordner wird dann im Verzeichnis <em>modules</em> angelegt.');
$GLOBALS['TL_LANG']['tl_extension']['author']      = array('Autor', 'Bitte geben Sie einen Namen und eine optionale E-Mail-Adresse ein (z.B. <em>Name [e-mail@adresse.de]</em>).');
$GLOBALS['TL_LANG']['tl_extension']['copyright']   = array('Copyright', 'Bitte geben Sie einen Copyright Vermerk ein (z.B. <em>Name 2007</em>).');
$GLOBALS['TL_LANG']['tl_extension']['package']     = array('Paket', 'Bitte geben Sie den Namen des Paketes ohne Leerzeichen ein (z.B. <em>MyCustomModule</em>).');
$GLOBALS['TL_LANG']['tl_extension']['license']     = array('Lizenz', 'Bitte geben Sie den Lizenztyp an (z.B. <em>GPL</em>).');
$GLOBALS['TL_LANG']['tl_extension']['addBeMod']    = array('Ein Backend-Modul hinzufügen', 'Wählen Sie diese Option, wenn Sie ein Backend-Modul erstellen möchten.');
$GLOBALS['TL_LANG']['tl_extension']['beClasses']   = array('Backend-Klassen', 'Bitte geben Sie eine durch Komma getrennte Liste zu erstellender Backend-Klassendateien ein.');
$GLOBALS['TL_LANG']['tl_extension']['beTables']    = array('Backend-Tabellen', 'Bitte geben Sie eine durch Komma getrennte Liste zu erstellender Backend-Tabellen ein.');
$GLOBALS['TL_LANG']['tl_extension']['beTemplates'] = array('Backend-Templates', 'Bitte geben Sie eine durch Komma getrennte Liste zu erstellender Backend-Template Dateien ein.');
$GLOBALS['TL_LANG']['tl_extension']['addFeMod']    = array('Ein Frontend-Modul hinzufügen', 'Wählen Sie diese Option, wenn Sie ein Frontend-Modul erstellen möchten.');
$GLOBALS['TL_LANG']['tl_extension']['feClasses']   = array('Frontend-Klassen', 'Bitte geben Sie eine durch Komma getrennte Liste zu erstellender Frontend-Klassendateien ein.');
$GLOBALS['TL_LANG']['tl_extension']['feTables']    = array('Frontend-Tabellen', 'Bitte geben Sie eine durch Komma getrennte Liste zu erstellender Frontend-Tabellen ein.');
$GLOBALS['TL_LANG']['tl_extension']['feTemplates'] = array('Frontend-Templates', 'Bitte geben Sie eine durch Komma getrennte Liste zu erstellender Frontend-Template Dateien ein.');
$GLOBALS['TL_LANG']['tl_extension']['addLanguage'] = array('Ein Sprachpaket erstellen', 'Wählen Sie diese Option, wenn Sie ein oder mehrere Sprachpakete erstellen möchten.');
$GLOBALS['TL_LANG']['tl_extension']['languages']   = array('Sprachen', 'Bitte geben Sie eine durch Komma getrennte Liste zu erstellender Sprachpakete ein (z.B. <em>en,de</em>).');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_extension']['headline'] = 'Modul ID %s erstellen';
$GLOBALS['TL_LANG']['tl_extension']['label']    = 'Wie man ein neues Modul erstellt';
$GLOBALS['TL_LANG']['tl_extension']['confirm']  = 'Die Dateien wurden erstellt';
$GLOBALS['TL_LANG']['tl_extension']['unique']   = 'Ein Ordner namens "%s" ist bereits vorhanden!';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_extension']['new']    = array('Neues Modul', 'Ein neues Modul erstellen');
$GLOBALS['TL_LANG']['tl_extension']['edit']   = array('Modul bearbeiten', 'Modul ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_extension']['copy']   = array('Modul duplizieren', 'Modul ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_extension']['delete'] = array('Modul löschen', 'Modul ID %s löschen');
$GLOBALS['TL_LANG']['tl_extension']['show']   = array('Moduldetails', 'Details des Moduls ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_extension']['create'] = array('Dateien erstellen', 'Dateien des Moduls ID %s erstellen');
$GLOBALS['TL_LANG']['tl_extension']['make']   = array('Dateien erstellen', 'Wenn Sie auf die Schaltfläche "Dateien erstellen" klicken, wird ein neuer Ordner im Verzeichnis <em>modules</em> erstellt. Dieser Ordner enthält alle Dateien und Unterordner, die für das Anlegen des Moduls notwendig sind. Sie können diese Dateien in Ihre Entwicklungsumgebung herunterladen. Beachten Sie, dass bestehende Dateien auf dem Server überschrieben werden, wenn Sie die Schaltfläche erneut betätigen!');

?>