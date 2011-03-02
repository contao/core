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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
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
$GLOBALS['TL_LANG']['tl_module']['nl_includeCss']   = array('Stylesheet parsen', 'Das Stylesheet <em>newsletter.css</em> der Frontendseite hinzufügen.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['text_subscribe']   = array('Ihr Abonnement auf %s', "Sie haben folgende Verteiler auf ##domain## abonniert:\n\n##channels##\n\nBitte klicken Sie ##link## um Ihr Abonnement zu aktivieren. Falls Sie die Bestellung nicht selbst getätigt haben, bitte ignorieren Sie diese E-Mail.\n");
$GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'] = array('Ihr Abonnement auf %s', "Sie haben folgende Abonnements auf ##domain## gekündigt:\n\n##channels##\n");

?>