<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_style']['invisible']      = array('Unsichtbar', 'Die Formatdefinition nicht exportieren.');
$GLOBALS['TL_LANG']['tl_style']['selector']       = array('Selektor', 'Der Selektor legt fest, für welche Elemente eine Formatdefinition gilt.');
$GLOBALS['TL_LANG']['tl_style']['category']       = array('Kategorie', 'Mit Hilfe von Kategorien können Formatdefinitionen im Backend gruppiert werden.');
$GLOBALS['TL_LANG']['tl_style']['comment']        = array('Kommentar', 'Hier können Sie einen Kommentar eingeben.');
$GLOBALS['TL_LANG']['tl_style']['size']           = array('Größe', 'Width, height, min-width, min-height, max-width und max-height.');
$GLOBALS['TL_LANG']['tl_style']['width']          = array('Breite', 'Hier können Sie die Breite des Elements eingeben.');
$GLOBALS['TL_LANG']['tl_style']['height']         = array('Höhe', 'Hier können Sie die Höhe des Elements eingeben.');
$GLOBALS['TL_LANG']['tl_style']['minwidth']       = array('Mindestbreite', 'Hier können Sie die Mindestbreite des Elements eingeben.');
$GLOBALS['TL_LANG']['tl_style']['minheight']      = array('Mindesthöhe', 'Hier können Sie die Mindesthöhe des Elements eingeben.');
$GLOBALS['TL_LANG']['tl_style']['maxwidth']       = array('Maximalbreite', 'Hier können Sie die Maximalbreite des Elements eingeben.');
$GLOBALS['TL_LANG']['tl_style']['maxheight']      = array('Maximalhöhe', 'Hier können Sie die Maximalhöhe des Elements eingeben.');
$GLOBALS['TL_LANG']['tl_style']['positioning']    = array('Position', 'Position, float, clear, overflow und display.');
$GLOBALS['TL_LANG']['tl_style']['trbl']           = array('Position', 'Hier können Sie die obere, rechte, untere und linke Position eingeben.');
$GLOBALS['TL_LANG']['tl_style']['position']       = array('Positionsart', 'Hier können Sie die Positionsart auswählen.');
$GLOBALS['TL_LANG']['tl_style']['floating']       = array('Float', 'Hier können Sie den Float-Typ auswählen.');
$GLOBALS['TL_LANG']['tl_style']['clear']          = array('Clear', 'Hier können Sie den Clear-Typ auswählen.');
$GLOBALS['TL_LANG']['tl_style']['overflow']       = array('Overflow', 'Hier können Sie das Overflow-Verhalten festlegen.');
$GLOBALS['TL_LANG']['tl_style']['display']        = array('Display', 'Hier können Sie den Anzeige-Typ auswählen.');
$GLOBALS['TL_LANG']['tl_style']['alignment']      = array('Margin, Padding und Ausrichtung', 'Margin, padding, align, vertical-align und text-align.');
$GLOBALS['TL_LANG']['tl_style']['margin']         = array('Margin (Außenabstand)', 'Hier können Sie den oberen, rechten, unteren und linken Außenabstand eingeben.');
$GLOBALS['TL_LANG']['tl_style']['padding']        = array('Padding (Innenabstand)', 'Hier können Sie den oberen, rechten, unteren und linken Innenabstand eingeben.');
$GLOBALS['TL_LANG']['tl_style']['align']          = array('Elementausrichtung', 'Um das Element auszurichten, wird der linke und rechte Außenabstand überschrieben.');
$GLOBALS['TL_LANG']['tl_style']['verticalalign']  = array('Vertikale Ausrichtung', 'Hier können Sie die vertikale Ausrichtung auswählen.');
$GLOBALS['TL_LANG']['tl_style']['textalign']      = array('Textausrichtung', 'Hier können Sie die horizontale Ausrichtung auswählen.');
$GLOBALS['TL_LANG']['tl_style']['background']     = array('Hintergrund', 'Background-color, background-image, background-position, background-repeat, linear-gradient und box-shadow.');
$GLOBALS['TL_LANG']['tl_style']['bgcolor']        = array('Hintergrund und Deckkraft', 'Hier können Sie eine hexadezimale Hintergrundfarbe (z.B. ff0000 für rot) sowie optional die Deckkraft in Prozent (z.B. 75) eingeben.');
$GLOBALS['TL_LANG']['tl_style']['bgimage']        = array('Hintergrundbild', 'Hier können Sie den Pfad zu einem Hintergrundbild eingeben.');
$GLOBALS['TL_LANG']['tl_style']['bgposition']     = array('Hintergrundposition', 'Hier können Sie die Position des Hintergrundbildes auswählen.');
$GLOBALS['TL_LANG']['tl_style']['bgrepeat']       = array('Hintergrundwiederholung', 'Hier können Sie das Wiederholungsverhalten auswählen.');
$GLOBALS['TL_LANG']['tl_style']['shadowsize']     = array('Schattengröße', 'Hier können Sie den X- und Y-Versatz sowie optional die Unschärfe und den Spread-Radius eingeben.');
$GLOBALS['TL_LANG']['tl_style']['shadowcolor']    = array('Schattenfarbe und Deckkraft', 'Hier können Sie eine hexadezimale Schattenfarbe (z.B. ff0000 für rot) sowie optional die Deckkraft in Prozent (z.B. 75) eingeben.');
$GLOBALS['TL_LANG']['tl_style']['gradientAngle']  = array('Verlaufswinkel', 'Hier können Sie den Verlaufswinkel (z.B. <em>-45deg</em>) oder den Startpunkt (z.B. <em>top</em> oder <em>left bottom</em>) eingeben.');
$GLOBALS['TL_LANG']['tl_style']['gradientColors'] = array('Verlaufsfarben', 'Hier können sie bis zu vier Farben mit einer optionalen Prozentangabe eingeben (z.B. <em>ffc 10% | f90 | f00</em>).');
$GLOBALS['TL_LANG']['tl_style']['border']         = array('Rahmen', 'Border-width, border-style, border-color, border-radius, border-collapse und border-spacing.');
$GLOBALS['TL_LANG']['tl_style']['borderwidth']    = array('Rahmenbreite', 'Hier können Sie die obere, rechte, untere und linke Rahmenbreite eingeben.');
$GLOBALS['TL_LANG']['tl_style']['borderstyle']    = array('Rahmenstil', 'Hier können Sie den Stil des Rahmens auswählen.');
$GLOBALS['TL_LANG']['tl_style']['bordercolor']    = array('Rahmenfarbe und Deckkraft', 'Hier können Sie eine hexadezimale Rahmenfarbe (z.B. ff0000 für rot) sowie optional die Deckkraft in Prozent (z.B. 75) eingeben.');
$GLOBALS['TL_LANG']['tl_style']['borderradius']   = array('Eckradius', 'Hier können Sie den oberen linken, den oberen rechten, den unteren rechten und den unteren linken Eckradius eingeben.');
$GLOBALS['TL_LANG']['tl_style']['bordercollapse'] = array('Rahmenmodell', 'Hier können Sie das Rahmenmodell auswählen.');
$GLOBALS['TL_LANG']['tl_style']['borderspacing']  = array('Rahmenabstand', 'Hier können Sie den Rahmenabstand eingeben.');
$GLOBALS['TL_LANG']['tl_style']['font']           = array('Schrift', 'Font-family, font-size, font-color, line-height, font-style und white-space.');
$GLOBALS['TL_LANG']['tl_style']['fontfamily']     = array('Schriftarten', 'Hier können Sie eine kommagetrennte Liste von Schriftarten eingeben.');
$GLOBALS['TL_LANG']['tl_style']['fontsize']       = array('Schriftgröße', 'Hier können Sie die Schriftgröße eingeben.');
$GLOBALS['TL_LANG']['tl_style']['fontcolor']      = array('Schriftfarbe und Deckkraft', 'Hier können Sie eine hexadezimale Schriftfarbe (z.B. ff0000 für rot) sowie optional die Deckkraft in Prozent (z.B. 75) eingeben.');
$GLOBALS['TL_LANG']['tl_style']['lineheight']     = array('Zeilenhöhe', 'Hier können Sie die Zeilenhöhe festlegen.');
$GLOBALS['TL_LANG']['tl_style']['fontstyle']      = array('Schriftstil', 'Hier können Sie einen oder mehrere Schriftstile auswählen.');
$GLOBALS['TL_LANG']['tl_style']['whitespace']     = array('Automatischen Zeilenumbruch ausschalten', 'Zeilen innerhalb des Elements nicht automatisch umbrechen.');
$GLOBALS['TL_LANG']['tl_style']['texttransform']  = array('Text-Transformation', 'Hier können Sie einen Text-Transformationsmodus auswählen.');
$GLOBALS['TL_LANG']['tl_style']['textindent']     = array('Text-Einrückung', 'Hier können Sie die Text-Einrückung festlegen.');
$GLOBALS['TL_LANG']['tl_style']['letterspacing']  = array('Buchstaben-Abstand', 'Hier können Sie den Buchstaben-Abstand verändern (Standard: 0px).');
$GLOBALS['TL_LANG']['tl_style']['wordspacing']    = array('Wort-Abstand', 'Hier können Sie den Wort-Abstand verändern (Standard: 0px).');
$GLOBALS['TL_LANG']['tl_style']['list']           = array('Aufzählung', 'List-style-type und list-style-image.');
$GLOBALS['TL_LANG']['tl_style']['liststyletype']  = array('Listensymbol', 'Hier können Sie das Symbol für Listeneinträge auswählen.');
$GLOBALS['TL_LANG']['tl_style']['liststyleimage'] = array('Eigenes Symbol', 'Hier können Sie den Pfad zu einem eigenen Symbol eingeben.');
$GLOBALS['TL_LANG']['tl_style']['own']            = array('Eigener Code', 'Hier können Sie eigenen CSS-Code eingeben.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_style']['selector_legend']   = 'Selektor und Kategorie';
$GLOBALS['TL_LANG']['tl_style']['size_legend']       = 'Abmessungen';
$GLOBALS['TL_LANG']['tl_style']['position_legend']   = 'Position';
$GLOBALS['TL_LANG']['tl_style']['align_legend']      = 'Abstand und Ausrichtung';
$GLOBALS['TL_LANG']['tl_style']['background_legend'] = 'Hintergrund';
$GLOBALS['TL_LANG']['tl_style']['border_legend']     = 'Rahmen';
$GLOBALS['TL_LANG']['tl_style']['font_legend']       = 'Schrift';
$GLOBALS['TL_LANG']['tl_style']['list_legend']       = 'Aufzählung';
$GLOBALS['TL_LANG']['tl_style']['custom_legend']     = 'Eigener Code';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_style']['normal']        = 'normal';
$GLOBALS['TL_LANG']['tl_style']['bold']          = 'fett';
$GLOBALS['TL_LANG']['tl_style']['italic']        = 'kursiv';
$GLOBALS['TL_LANG']['tl_style']['underline']     = 'unterstrichen';
$GLOBALS['TL_LANG']['tl_style']['notUnderlined'] = 'nicht unterstrichen';
$GLOBALS['TL_LANG']['tl_style']['line-through']  = 'durchgestrichen';
$GLOBALS['TL_LANG']['tl_style']['overline']      = 'überstrichen';
$GLOBALS['TL_LANG']['tl_style']['small-caps']    = 'Kapitälchen';
$GLOBALS['TL_LANG']['tl_style']['disc']          = 'Punkt';
$GLOBALS['TL_LANG']['tl_style']['circle']        = 'Kreis';
$GLOBALS['TL_LANG']['tl_style']['square']        = 'Quadrat';
$GLOBALS['TL_LANG']['tl_style']['decimal']       = 'Ziffern';
$GLOBALS['TL_LANG']['tl_style']['upper-roman']   = 'Lateinische Zahlen in Großbuchstaben';
$GLOBALS['TL_LANG']['tl_style']['lower-roman']   = 'Lateinische Zahlen in Kleinbuchstaben';
$GLOBALS['TL_LANG']['tl_style']['upper-alpha']   = 'Großbuchstaben';
$GLOBALS['TL_LANG']['tl_style']['lower-alpha']   = 'Kleinbuchstaben';
$GLOBALS['TL_LANG']['tl_style']['uppercase']     = 'Großbuchstaben';
$GLOBALS['TL_LANG']['tl_style']['lowercase']     = 'Kleinbuchstaben';
$GLOBALS['TL_LANG']['tl_style']['capitalize']    = 'Anfangsbuchstaben groß';
$GLOBALS['TL_LANG']['tl_style']['none']          = 'deaktivieren';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style']['new']        = array('Neue Formatdefinition', 'Eine neue Formatdefinition erstellen');
$GLOBALS['TL_LANG']['tl_style']['show']       = array('Formatdefinitionsdetails', 'Details der Formatdefinition ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_style']['edit']       = array('Formatdefinition bearbeiten', 'Formatdefinition ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_style']['cut']        = array('Formatdefinition verschieben ', 'Formatdefinition ID %s verschieben');
$GLOBALS['TL_LANG']['tl_style']['copy']       = array('Formatdefinition duplizieren', 'Formatdefinition ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_style']['delete']     = array('Formatdefinition löschen', 'Formatdefinition ID %s löschen');
$GLOBALS['TL_LANG']['tl_style']['editheader'] = array('Stylesheet bearbeiten', 'Die Stylesheet-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_style']['pasteafter'] = array('Oben einfügen', 'Nach der Formatdefinition ID %s einfügen');
$GLOBALS['TL_LANG']['tl_style']['pastenew']   = array('Neue Formatdefinition oben erstellen', 'Neues Element nach der Formatdefinition ID %s erstellen');
$GLOBALS['TL_LANG']['tl_style']['toggle']     = array('Sichtbarkeit ändern', 'Die Sichtbarkeit der Formatdefinition ID %s ändern');

?>