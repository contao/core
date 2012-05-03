<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_theme']['name']       = array('Theme-Titel', 'Bitte geben Sie einen eindeutigen Titel ein.');
$GLOBALS['TL_LANG']['tl_theme']['author']     = array('Autor', 'Bitte geben Sie den Namen des Theme-Designers ein.');
$GLOBALS['TL_LANG']['tl_theme']['folders']    = array('Ordner', 'Bitte wählen Sie die Ordner aus, die zu dem Theme gehören.');
$GLOBALS['TL_LANG']['tl_theme']['templates']  = array('Templates-Ordner', 'Hier können Sie einen Templates-Ordner festlegen, der mit dem Theme zusammen exportiert wird.');
$GLOBALS['TL_LANG']['tl_theme']['screenshot'] = array('Bildschirmfoto', 'Hier können Sie ein Bildschirmfoto des Theme auswählen.');
$GLOBALS['TL_LANG']['tl_theme']['vars']       = array('Globale Variablen', 'Hier können Sie globale Variablen für die Stylesheets des Theme definieren (z.B. <em>$rot</em> -> <em>c00</em> oder <em>$abstand</em> -> <em>12px</em>).');
$GLOBALS['TL_LANG']['tl_theme']['source']     = array('Quelldateien', 'Hier können Sie eine oder mehrere .cto-Dateien für den Import hochladen.');
$GLOBALS['TL_LANG']['tl_theme']['tstamp']     = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_theme']['title_legend']  = 'Titel und Autor';
$GLOBALS['TL_LANG']['tl_theme']['config_legend'] = 'Konfiguration';
$GLOBALS['TL_LANG']['tl_theme']['vars_legend']   = 'Globale Variablen';


/**
 * References
 */
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
