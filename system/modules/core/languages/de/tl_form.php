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
$GLOBALS['TL_LANG']['tl_form']['title']        = array('Titel', 'Bitte geben Sie den Formular-Titel ein.');
$GLOBALS['TL_LANG']['tl_form']['alias']        = array('Formalias', 'Der Formalias ist eine eindeutige Referenz, die anstelle der numerischen Form-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_form']['jumpTo']       = array('Weiterleitungsseite', 'Bitte wählen Sie die Seite aus, zu der Besucher nach dem Abschicken des Formulars weitergeleitet werden.');
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'] = array('Per E-Mail versenden', 'Die Formulardaten an eine E-Mail-Adresse versenden.');
$GLOBALS['TL_LANG']['tl_form']['recipient']    = array('Empfänger-Adresse', 'Mehrere E-Mail-Adressen können mit Komma getrennt werden.');
$GLOBALS['TL_LANG']['tl_form']['subject']      = array('Betreff', 'Bitte geben Sie die Betreffzeile ein.');
$GLOBALS['TL_LANG']['tl_form']['format']       = array('Datenformat', 'Hier legen Sie fest, in welchem Format die Formulardaten übermittelt werden.');
$GLOBALS['TL_LANG']['tl_form']['raw']          = array('Rohdaten', 'Die Formulardaten werden als einfache Textnachricht mit einer Zeile pro Feld versendet.');
$GLOBALS['TL_LANG']['tl_form']['xml']          = array('XML-Datei', 'Die Formulardaten werden der E-Mail als XML-Datei angefügt.');
$GLOBALS['TL_LANG']['tl_form']['csv']          = array('CSV-Datei', 'Die Formulardaten werden der E-Mail als CSV-Datei angefügt.');
$GLOBALS['TL_LANG']['tl_form']['email']        = array('E-Mail', 'Ignoriert alle Felder außer <em>email</em>, <em>subject</em>, <em>message</em> und <em>cc</em> (Carbon Copy) und verschickt die Formulardaten als wären sie mit einem E-Mail-Programm versendet worden. Datei-Uploads sind erlaubt.');
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy']    = array('Leere Felder auslassen', 'Leere Felder in der E-Mail nicht anzeigen.');
$GLOBALS['TL_LANG']['tl_form']['storeValues']  = array('Eingaben speichern', 'Übermittelte Formulardaten in der Datenbank speichern.');
$GLOBALS['TL_LANG']['tl_form']['targetTable']  = array('Zieltabelle', 'Die Zieltabelle muss für jedes Formularfeld eine Spalte enthalten.');
$GLOBALS['TL_LANG']['tl_form']['method']       = array('Übertragungsmethode', 'Die Standard-Übertragungsmethode ist POST.');
$GLOBALS['TL_LANG']['tl_form']['attributes']   = array('CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_form']['formID']       = array('Formular-ID', 'Die Formular-ID wird zur Ansteuerung eines Contao-Moduls benötigt.');
$GLOBALS['TL_LANG']['tl_form']['tableless']    = array('Tabellenloses Layout', 'Das Formular ohne HTML-Tabellen ausgeben.');
$GLOBALS['TL_LANG']['tl_form']['allowTags']    = array('HTML-Tags erlauben', 'HTML-Eingaben in Formularfeldern erlauben.');
$GLOBALS['TL_LANG']['tl_form']['tstamp']       = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_form']['title_legend']  = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG']['tl_form']['email_legend']  = 'Formulardaten versenden';
$GLOBALS['TL_LANG']['tl_form']['store_legend']  = 'Formulardaten speichern';
$GLOBALS['TL_LANG']['tl_form']['expert_legend'] = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_form']['config_legend'] = 'Formular-Konfiguration';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form']['new']        = array('Neues Formular', 'Ein neues Formular anlegen');
$GLOBALS['TL_LANG']['tl_form']['show']       = array('Formulardetails', 'Details des Formulars ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_form']['edit']       = array('Formular bearbeiten', 'Das Formular ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_form']['editheader'] = array('Formular-Einstellungen bearbeiten', 'Einstellungen des Formulars ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_form']['copy']       = array('Formular duplizieren', 'Das Formular ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_form']['delete']     = array('Formular löschen', 'Das Formular ID %s löschen');
