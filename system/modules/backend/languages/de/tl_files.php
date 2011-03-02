<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_files']['name']       = array('Name', 'Contao fügt die Dateiendung bei Dateien automatisch ein.');
$GLOBALS['TL_LANG']['tl_files']['fileupload'] = array('Datei-Upload', 'Durchsuchen Sie Ihren Computer und wählen Sie die Dateien, die Sie auf den Server übertragen möchten.');
$GLOBALS['TL_LANG']['tl_files']['editor']     = array('Quelltexteditor', 'Hier können Sie den Quelltext der Datei bearbeiten.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_files']['upload']      = 'Dateien hochladen';
$GLOBALS['TL_LANG']['tl_files']['uploadNback'] = 'Dateien hochladen und zurück';
$GLOBALS['TL_LANG']['tl_files']['editFF']      = 'Eine Datei oder ein Verzeichnis bearbeiten';
$GLOBALS['TL_LANG']['tl_files']['uploadFF']    = 'Dateien in den Ordner "%s" hochladen';
$GLOBALS['TL_LANG']['tl_files']['editFile']    = 'Datei "%s" bearbeiten';
$GLOBALS['TL_LANG']['tl_files']['browseFiles'] = 'Dateien suchen';
$GLOBALS['TL_LANG']['tl_files']['clearList']   = 'Liste leeren';
$GLOBALS['TL_LANG']['tl_files']['startUpload'] = 'Upload starten';


/**
 * FancyUpload
 */
$GLOBALS['TL_LANG']['tl_files']['fancy_progressOverall'] = 'Gesamtfortschritt ({total})';
$GLOBALS['TL_LANG']['tl_files']['fancy_currentTitle']    = 'Dateifortschritt';
$GLOBALS['TL_LANG']['tl_files']['fancy_currentFile']     = 'Übertrage {name}';
$GLOBALS['TL_LANG']['tl_files']['fancy_currentProgress'] = 'Upload: {bytesLoaded} mit {rate}, {timeRemaining} verbleibend.';
$GLOBALS['TL_LANG']['tl_files']['fancy_remove']          = 'Entfernen';
$GLOBALS['TL_LANG']['tl_files']['fancy_removeTitle']     = 'Klicken Sie hier, um den Eintrag zu entfernen.';
$GLOBALS['TL_LANG']['tl_files']['fancy_fileError']       = 'Upload fehlgeschlagen';
$GLOBALS['TL_LANG']['tl_files']['fancy_duplicate']       = 'Die Datei <em>{name}</em> wurde bereits übertragen. Duplikate sind nicht erlaubt.';
$GLOBALS['TL_LANG']['tl_files']['fancy_sizeLimitMin']    = 'Die Datei <em>{name}</em> (<em>{size}</em>) ist zu klein. Die Mindestgröße beträgt {fileSizeMin}.';
$GLOBALS['TL_LANG']['tl_files']['fancy_sizeLimitMax']    = 'Die Datei <em>{name}</em> (<em>{size}</em>) ist zu groß. Die Maximalgröße beträgt <em>{fileSizeMax}</em>.';
$GLOBALS['TL_LANG']['tl_files']['fancy_fileListMax']     = 'Die Datei <em>{name}</em> konnte nicht übertragen werden. Die maximale Anzahl von <em>{fileListMax} Dateien</em> wurde überschritten.';
$GLOBALS['TL_LANG']['tl_files']['fancy_fileListSizeMax'] = 'Die Datei <em>{name}</em> (<em>{size}</em>) ist to groß. Es können insgesamt nur maximal <em>{fileListSizeMax}</em> übertragen werden.';
$GLOBALS['TL_LANG']['tl_files']['fancy_httpStatus']      = 'Der Server hat den HTTP-Status <code>#{code}</code> zurückgegeben';
$GLOBALS['TL_LANG']['tl_files']['fancy_securityError']   = 'Ein Sicherheitsproblem ist aufgetreten ({text})';
$GLOBALS['TL_LANG']['tl_files']['fancy_ioError']         = 'Ein Lade- bzw. Sendevorgang wurde wegen eines Fehlers abgebrochen ({text})';
$GLOBALS['TL_LANG']['tl_files']['fancy_uploadCompleted'] = 'Upload abgeschlossen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_files']['new']       = array('Neuer Ordner', 'Einen neuen Ordner anlegen');
$GLOBALS['TL_LANG']['tl_files']['cut']       = array('Datei oder Verzeichnis verschieben', 'Datei oder Verzeichnis "%s" verschieben');
$GLOBALS['TL_LANG']['tl_files']['copy']      = array('Datei oder Verzeichnis duplizieren', 'Datei oder Verzeichnis "%s" duplizieren');
$GLOBALS['TL_LANG']['tl_files']['edit']      = array('Datei oder Verzeichnis umbenennen', 'Datei oder Verzeichnis "%s" umbenennen');
$GLOBALS['TL_LANG']['tl_files']['delete']    = array('Datei oder Verzeichnis löschen', 'Datei oder Verzeichnis "%s" löschen');
$GLOBALS['TL_LANG']['tl_files']['source']    = array('Datei bearbeiten', 'Die Datei "%s" bearbeiten');
$GLOBALS['TL_LANG']['tl_files']['protect']   = array('Verzeichnis schützen', 'Verzeichnis "%s" schützen');
$GLOBALS['TL_LANG']['tl_files']['unlock']    = array('Verzeichnisschutz aufheben', 'Schutz des Verzeichnisses "%s" aufheben');
$GLOBALS['TL_LANG']['tl_files']['move']      = array('Datei-Upload', 'Dateien auf den Server übertragen');
$GLOBALS['TL_LANG']['tl_files']['pasteinto'] = array('Einfügen in', 'In dieses Verzeichnis einfügen');

?>