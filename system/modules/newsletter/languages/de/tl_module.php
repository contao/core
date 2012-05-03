<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Newsletter
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['newsletters']     = array('Abonnierbare Newsletter', 'Diese Newsletter im Frontend-Formular anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['nl_channels']     = array('Verteiler', 'Bitte wählen Sie einen oder mehrere Verteiler.');
$GLOBALS['TL_LANG']['tl_module']['nl_hideChannels'] = array('Verteilermenü ausblenden', 'Das Menü zum Auswählen von Verteilern nicht anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['nl_subscribe']    = array('Abonnementbestätigung', 'Sie können die Platzhalter <em>##channels##</em> (Name der Verteiler), <em>##domain##</em> (Domainname) und <em>##link##</em> (Aktivierungslink) verwenden.');
$GLOBALS['TL_LANG']['tl_module']['nl_unsubscribe']  = array('Kündigungsbestätigung', 'Sie können die Platzhalter <em>##channels##</em> (Name der Verteiler) und <em>##domain##</em> (Domainname) verwenden.');
$GLOBALS['TL_LANG']['tl_module']['nl_template']     = array('Newslettertemplate', 'Hier können Sie das Newslettertemplate auswählen.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['text_subscribe']   = array('Ihr Abonnement auf %s', "Sie haben folgende Verteiler auf ##domain## abonniert:\n\n##channels##\n\nBitte klicken Sie ##link## um Ihr Abonnement zu aktivieren. Falls Sie die Bestellung nicht selbst getätigt haben, bitte ignorieren Sie diese E-Mail.\n");
$GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'] = array('Ihr Abonnement auf %s', "Sie haben folgende Abonnements auf ##domain## gekündigt:\n\n##channels##\n");
