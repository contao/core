<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Glossary
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_glossary_term']['term']         = array('Begriff', 'Bitte geben Sie den Begriff ein.');
$GLOBALS['TL_LANG']['tl_glossary_term']['author']       = array('Autor', 'Hier können Sie den Autor der Definition ändern.');
$GLOBALS['TL_LANG']['tl_glossary_term']['definition']   = array('Definition', 'Bitte geben Sie die Definition ein.');
$GLOBALS['TL_LANG']['tl_glossary_term']['addImage']     = array('Ein Bild hinzufügen', 'Der Definition ein Bild hinzufügen.');
$GLOBALS['TL_LANG']['tl_glossary_term']['addEnclosure'] = array('Enclosures hinzufügen', 'Der Definition eine oder mehrere Dateien als Download hinzufügen.');
$GLOBALS['TL_LANG']['tl_glossary_term']['enclosure']    = array('Enclosures', 'Bitte wählen Sie die Dateien aus, die Sie hinzufügen möchten.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_glossary_term']['title_legend']      = 'Begriff und Autor';
$GLOBALS['TL_LANG']['tl_glossary_term']['definition_legend'] = 'Definition';
$GLOBALS['TL_LANG']['tl_glossary_term']['image_legend']      = 'Artikelbild';
$GLOBALS['TL_LANG']['tl_glossary_term']['enclosure_legend']  = 'Enclosures';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_glossary_term']['new']    = array('Neuer Begriff', 'Einen neuen Begriff hinzufügen');
$GLOBALS['TL_LANG']['tl_glossary_term']['show']   = array('Begriffsdetails', 'Details des Begriffs ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_glossary_term']['edit']   = array('Begriff bearbeiten', 'Begriff ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_glossary_term']['copy']   = array('Begriff duplizieren', 'Begriff ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_glossary_term']['delete'] = array('Begriff löschen', 'Begriff ID %s löschen');

?>