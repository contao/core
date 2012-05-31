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
$GLOBALS['TL_LANG']['tl_style_sheet']['name']        = array('Name', 'Bitte geben Sie den Namen des Stylesheets ein.');
$GLOBALS['TL_LANG']['tl_style_sheet']['embedImages'] = array('Bilder einbetten bis zu', 'Hier können Sie die Dateigröße in Bytes angeben, bis zu der Bilder im Stylesheet als data:-String eingebettet werden. Tragen Sie 0 ein, um die Funktion zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_style_sheet']['cc']          = array('Conditional Comment', 'Conditional Comments ermöglichen die Erstellung Internet Explorer-spezifischer Stylesheets (z.B. <em>if lt IE 9</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['media']       = array('Medientypen', 'Hier können Sie die Medientypen auswählen, für die das Stylesheet gültig ist.');
$GLOBALS['TL_LANG']['tl_style_sheet']['mediaQuery']  = array('Media-Query', 'Hier können Sie den Medientyp mit Hilfe eines Media-Querys wie z.B. <em>screen and (min-width: 800px)</em> festlegen. Die oben ausgewählten Medientypen werden dadurch überschrieben.');
$GLOBALS['TL_LANG']['tl_style_sheet']['vars']        = array('Globale Variablen', 'Hier können Sie globale Variablen für das Stylesheet definieren (z.B. <em>$rot</em> -> <em>c00</em> oder <em>$abstand</em> -> <em>12px</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['source']      = array('Quelldateien', 'Hier können Sie eine oder mehrere .css-Dateien für den Import hochladen.');
$GLOBALS['TL_LANG']['tl_style_sheet']['tstamp']      = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['title_legend']  = 'Name';
$GLOBALS['TL_LANG']['tl_style_sheet']['config_legend'] = 'Konfiguration';
$GLOBALS['TL_LANG']['tl_style_sheet']['media_legend']  = 'Medieneinstellungen';
$GLOBALS['TL_LANG']['tl_style_sheet']['vars_legend']   = 'Globale Variablen';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['css_imported'] = 'Das Stylesheet "%s" wurde importiert.';
$GLOBALS['TL_LANG']['tl_style_sheet']['css_renamed']  = 'Das Stylesheet "%s" wurde als "%s" importiert.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['new']        = array('Neues Stylesheet', 'Ein neues Stylesheet anlegen');
$GLOBALS['TL_LANG']['tl_style_sheet']['show']       = array('Stylesheetdetails', 'Details des Stylesheets ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_style_sheet']['edit']       = array('Stylesheet bearbeiten', 'Stylesheet ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_style_sheet']['editheader'] = array('Stylesheet-Einstellungen bearbeiten', 'Einstellungen des Stylesheet ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_style_sheet']['cut']        = array('Stylesheet verschieben ', 'Stylesheet ID %s verschieben');
$GLOBALS['TL_LANG']['tl_style_sheet']['copy']       = array('Stylesheet duplizieren', 'Stylesheet ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_style_sheet']['delete']     = array('Stylesheet löschen', 'Stylesheet ID %s löschen');
$GLOBALS['TL_LANG']['tl_style_sheet']['import']     = array('CSS-Import', 'Bestehende CSS-Dateien importieren');
