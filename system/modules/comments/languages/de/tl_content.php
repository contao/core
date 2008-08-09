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
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['com_template']       = array('Kommentarlayout', 'Bitte wählen Sie ein Kommentarlayout. Sie können eigene Kommentarlayouts im Ordner <em>templates</em> speichern. Vorlagen müssen mit <em>com_</em> beginnen und die Dateiendung <em>.tpl</em> haben.');
$GLOBALS['TL_LANG']['tl_content']['com_order']          = array('Sortierung', 'Bitte wählen Sie eine Sortierung.');
$GLOBALS['TL_LANG']['tl_content']['com_perPage']        = array('Elemente pro Seite', 'Bitte geben Sie die Anzahl an Kommentaren pro Seite ein (0 = Seitenumbruch deaktivieren).');
$GLOBALS['TL_LANG']['tl_content']['com_moderate']       = array('Moderieren', 'Kommentare bestätigen bevor sie auf der Webseite angezeigt werden.');
$GLOBALS['TL_LANG']['tl_content']['com_bbcode']         = array('BBCode erlauben', 'Besuchern erlauben, ihre Kommentare mittels BBCode zu formatieren.');
$GLOBALS['TL_LANG']['tl_content']['com_disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option um die Sicherheitsfrage abzuschalten (nicht empfohlen).');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_content']['ascending']  = 'aufsteigend';
$GLOBALS['TL_LANG']['tl_content']['descending'] = 'absteigend';

?>