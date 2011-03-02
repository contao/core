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
$GLOBALS['TL_LANG']['tl_theme']['name']       = array('Theme-Titel', 'Bitte geben Sie einen eindeutigen Titel ein.');
$GLOBALS['TL_LANG']['tl_theme']['author']     = array('Autor', 'Bitte geben Sie den Namen des Theme-Designers ein.');
$GLOBALS['TL_LANG']['tl_theme']['folders']    = array('Ordner', 'Bitte wählen Sie die Ordner aus, die zu dem Theme gehören.');
$GLOBALS['TL_LANG']['tl_theme']['templates']  = array('Templates-Ordner', 'Hier können Sie einen Templates-Ordner festlegen, der mit dem Theme zusammen exportiert wird.');
$GLOBALS['TL_LANG']['tl_theme']['screenshot'] = array('Bildschirmfoto', 'Hier können Sie ein Bildschirmfoto des Theme auswählen.');
$GLOBALS['TL_LANG']['tl_theme']['source']     = array('Quelldateien', 'Bitte wählen Sie eine oder mehrere .cto-Dateien aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_theme']['tstamp']     = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_theme']['title_legend']     = 'Titel und Autor';
$GLOBALS['TL_LANG']['tl_theme']['config_legend']    = 'Konfiguration';
$GLOBALS['TL_LANG']['tl_theme']['theme_imported']   = 'Das Theme "%s" wurde importiert.';
$GLOBALS['TL_LANG']['tl_theme']['checking_theme']   = 'Die Theme-Daten werden überprüft';
$GLOBALS['TL_LANG']['tl_theme']['tables_fields']    = 'Tabellen und Felder';
$GLOBALS['TL_LANG']['tl_theme']['missing_field']    = 'Das Feld <strong>%s</strong> fehlt in der Datenbank und wird daher nicht importiert.';
$GLOBALS['TL_LANG']['tl_theme']['tables_ok']        = 'Die Prüfung der Tabellen war erfolgreich.';
$GLOBALS['TL_LANG']['tl_theme']['custom_sections']  = 'Eigene Layoutbereiche';
$GLOBALS['TL_LANG']['tl_theme']['missing_section']  = 'Der Layoutbereich <strong>%s</strong> ist in den Backend-Einstellungen nicht definiert.';
$GLOBALS['TL_LANG']['tl_theme']['sections_ok']      = 'Die Prüfung der Layoutbereiche war erfolgreich.';
$GLOBALS['TL_LANG']['tl_theme']['missing_xml']      = 'Das Theme "%s" ist fehlerhaft und kann nicht importiert werden.';
$GLOBALS['TL_LANG']['tl_theme']['custom_templates'] = 'Eigene Templates';
$GLOBALS['TL_LANG']['tl_theme']['template_exists']  = 'Das Template <strong>"%s"</strong> existiert bereits und wird überschrieben.';
$GLOBALS['TL_LANG']['tl_theme']['templates_ok']     = 'Keine Konflikte festgestellt.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_theme']['new']         = array('Neues Theme', 'Ein neues Theme anlegen');
$GLOBALS['TL_LANG']['tl_theme']['show']        = array('Themedetails', 'Details des Theme ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_theme']['edit']        = array('Theme bearbeiten', 'Theme ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_theme']['delete']      = array('Theme löschen', 'Theme ID %s löschen');
$GLOBALS['TL_LANG']['tl_theme']['css']         = array('Stylesheets', 'Die Stylesheets des Theme ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_theme']['modules']     = array('Module', 'Die Frontend-Module des Theme ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_theme']['layout']      = array('Seitenlayouts', 'Die Seitenlayouts des Theme ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_theme']['importTheme'] = array('Theme importieren', 'Ein neues Theme importieren');
$GLOBALS['TL_LANG']['tl_theme']['exportTheme'] = array('Exportieren', 'Theme ID %s exportieren');

?>