<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Faq
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_faq_category']['title']          = array('Titel', 'Bitte geben Sie den Kategorie-Titel ein.');
$GLOBALS['TL_LANG']['tl_faq_category']['headline']       = array('Überschrift', 'Bitte geben Sie die Kategorie-Überschrift ein.');
$GLOBALS['TL_LANG']['tl_faq_category']['jumpTo']         = array('Weiterleitungsseite', 'Bitte wählen Sie die FAQ-Leser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie eine FAQ anklicken.');
$GLOBALS['TL_LANG']['tl_faq_category']['allowComments']  = array('Kommentare aktivieren', 'Besuchern das Kommentieren von FAQs erlauben.');
$GLOBALS['TL_LANG']['tl_faq_category']['notify']         = array('Benachrichtigung an', 'Bitte legen Sie fest, wer beim Hinzufügen neuer Kommentare benachrichtigt wird.');
$GLOBALS['TL_LANG']['tl_faq_category']['sortOrder']      = array('Sortierung', 'Standardmäßig werden Kommentare aufsteigend sortiert, beginnend mit dem ältesten.');
$GLOBALS['TL_LANG']['tl_faq_category']['perPage']        = array('Kommentare pro Seite', 'Anzahl an Kommentaren pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_faq_category']['moderate']       = array('Kommentare moderieren', 'Kommentare erst nach Bestätigung auf der Webseite veröffentlichen.');
$GLOBALS['TL_LANG']['tl_faq_category']['bbcode']         = array('BBCode erlauben', 'Besuchern das Formatieren ihrer Kommentare mittels BBCode erlauben.');
$GLOBALS['TL_LANG']['tl_faq_category']['requireLogin']   = array('Login zum Kommentieren benötigt', 'Nur angemeldeten Benutzern das Erstellen von Kommentaren erlauben.');
$GLOBALS['TL_LANG']['tl_faq_category']['disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option nur, wenn das Erstellen von Kommentaren auf authentifizierte Benutzer beschränkt ist.');
$GLOBALS['TL_LANG']['tl_faq_category']['tstamp']         = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_faq_category']['title_legend'] = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG']['tl_faq_category']['comments_legend']  = 'Kommentare';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_faq_category']['notify_admin']  = 'Systemadministrator';
$GLOBALS['TL_LANG']['tl_faq_category']['notify_author'] = 'Autor der Frage';
$GLOBALS['TL_LANG']['tl_faq_category']['notify_both']   = 'Autor und Systemadministrator';
$GLOBALS['TL_LANG']['tl_faq_category']['deleteConfirm'] = 'Wenn Sie die Kategorie %s löschen, werden auch alle darin enthaltenen FAQs gelöscht! Fortfahren?';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq_category']['new']        = array('Neue Kategorie', 'Eine neue Kategorie anlegen');
$GLOBALS['TL_LANG']['tl_faq_category']['show']       = array('Kategoriedetails', 'Details der Kategorie ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_faq_category']['edit']       = array('Kategorie bearbeiten', 'Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_faq_category']['editheader'] = array('Kategorie-Einstellungen bearbeiten', 'Einstellungen der Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_faq_category']['copy']       = array('Kategorie duplizieren', 'Kategorie ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_faq_category']['delete']     = array('Kategorie löschen', 'Kategorie ID %s löschen');
