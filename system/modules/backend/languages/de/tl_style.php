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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_style']['comment']        = array('Kommentar', 'Kommentare werden über der jeweiligen Formatdefinition angezeigt.');
$GLOBALS['TL_LANG']['tl_style']['selector']       = array('Selektor', 'Ein Selektor bestimmt, welchem HTML Element oder welcher Elementgruppe eine Formatdefinition zugewiesen wird. Sie können einen oder mehrere durch Komma getrennte Selektoren erfassen.');
$GLOBALS['TL_LANG']['tl_style']['category']       = array('Kategorie', 'Verwenden Sie Kategorien, um Formatdefinitionen zu gruppieren und die Übersicht zu behalten.');
$GLOBALS['TL_LANG']['tl_style']['size']           = array('Größe und Position', 'Größe und Position bearbeiten (width, height, position, overflow, float, clear, display).');
$GLOBALS['TL_LANG']['tl_style']['width']          = array('Breite', 'Bitte geben Sie die Breite des Elements und die Einheit ein.');
$GLOBALS['TL_LANG']['tl_style']['height']         = array('Höhe', 'Bitte geben Sie die Höhe des Elements und die Einheit ein.');
$GLOBALS['TL_LANG']['tl_style']['trbl']           = array('Position', 'Bitte geben Sie die obere, rechte, untere und linke Position sowie die Einheit ein.');
$GLOBALS['TL_LANG']['tl_style']['position']       = array('Positionsart', 'Bestimmt die Positionsart.');
$GLOBALS['TL_LANG']['tl_style']['overflow']       = array('Overflow', 'Bestimmt wie sich das Element verhält, wenn sein Inhalt seine Größe übersteigt.');
$GLOBALS['TL_LANG']['tl_style']['floating']       = array('Float', 'Lässt andere Elemente um ein Float-Element herumfließen.');
$GLOBALS['TL_LANG']['tl_style']['clear']          = array('Clear', 'Beendet das Umfließen anderer Elemente.');
$GLOBALS['TL_LANG']['tl_style']['display']        = array('Display', 'Bestimmt, wie das Element angezeigt wird.');
$GLOBALS['TL_LANG']['tl_style']['alignment']      = array('Margin, Padding und Ausrichtung', 'Margin (Außenabstand), Padding (Innenabstand) und Ausrichtung bearbeiten (margin, padding, align, text-align, vertical-align).');
$GLOBALS['TL_LANG']['tl_style']['margin']         = array('Margin (Außenabstand)', 'Bitte geben Sie den oberen, rechten, unteren und linken Außenabstand sowie die Einheit ein. Der Außenabstand ist der Abstand des Elements zu seinen benachbarten Elementen.');
$GLOBALS['TL_LANG']['tl_style']['padding']        = array('Padding (Innenabstand)', 'Bitte geben Sie den oberen, rechten, unteren und linken Innenabstand sowie die Einheit ein. Der Innenabstand ist der Abstand des Elements zu seinem Inhalt.');
$GLOBALS['TL_LANG']['tl_style']['align']          = array('Elementausrichtung', 'Um das Element auszurichten, wird der linke und rechte Außenabstand überschrieben.');
$GLOBALS['TL_LANG']['tl_style']['textalign']      = array('Textausrichtung', 'Bestimmt die horizontale Ausrichtung.');
$GLOBALS['TL_LANG']['tl_style']['verticalalign']  = array('Vertikale Ausrichtung', 'Bestimmt die vertikale Ausrichtung.');
$GLOBALS['TL_LANG']['tl_style']['background']     = array('Hintergrund', 'Hintergrundeigenschaften bearbeiten (color, image, position, repeat).');
$GLOBALS['TL_LANG']['tl_style']['bgcolor']        = array('Hintergrundfarbe', 'Bitte geben Sie eine hexadezimale Hintergrundfarbe ein (z.B. ff0000 für rot).');
$GLOBALS['TL_LANG']['tl_style']['bgimage']        = array('Hintergrundbild', 'Bitte geben Sie den Pfad zu einem Hintergrundbild ein.');
$GLOBALS['TL_LANG']['tl_style']['bgposition']     = array('Hintergrundposition', 'Wenn Sie ein sich nicht wiederholendes Hintergrundbild verwenden, können Sie dessen Position hier bestimmen.');
$GLOBALS['TL_LANG']['tl_style']['bgrepeat']       = array('Hintergrundwiederholung', 'Wenn Sie ein sich nicht wiederholendes Hintergrundbild verwenden, können Sie dessen Wiederholungsverhalten hier bestimmen.');
$GLOBALS['TL_LANG']['tl_style']['border']         = array('Rahmen', 'Rahmeneigenschaften bearbeiten (width, style, color, collapse).');
$GLOBALS['TL_LANG']['tl_style']['borderwidth']    = array('Rahmenbreite', 'Bitte geben Sie die obere, rechte, untere und linke Rahmenbreite sowie die Einheit ein.');
$GLOBALS['TL_LANG']['tl_style']['borderstyle']    = array('Rahmenstil', 'Bitte wählen Sie den Stil des Rahmens.');
$GLOBALS['TL_LANG']['tl_style']['bordercolor']    = array('Rahmenfarbe', 'Bitte geben Sie eine hexadezimale Rahmenfarbe ein (z.B. ff0000 für rot).');
$GLOBALS['TL_LANG']['tl_style']['bordercollapse'] = array('Rahmenmodell', 'Bitte bestimmen Sie, ob die Rahmen von benachbarten Zellen einer Tabelle als ein Rahmen, oder als zwei separate Rahmen angezeigt werden sollen.');
$GLOBALS['TL_LANG']['tl_style']['font']           = array('Schrift', 'Schrifteigenschaften bearbeiten (types, size, style, color, line-height, white-space).');
$GLOBALS['TL_LANG']['tl_style']['fontfamily']     = array('Schriftarten', 'Bitte geben Sie eine oder mehrere durch Komma getrennte Schriftarten ein. Verwenden Sie Anführungszeichen falls der Name der Schriftart Leerzeichen enthält und verwenden Sie mindestens eine generische Schriftart.');
$GLOBALS['TL_LANG']['tl_style']['fontstyle']      = array('Schriftstil', 'Bitte wählen Sie einen oder mehrere Schriftstile.');
$GLOBALS['TL_LANG']['tl_style']['fontsize']       = array('Schriftgröße', 'Bitte geben Sie die Schriftgröße und die Einheit ein.');
$GLOBALS['TL_LANG']['tl_style']['fontcolor']      = array('Schriftfarbe', 'Bitte geben Sie eine hexadezimale Schriftfarbe ein (z.B. ff0000 für rot).');
$GLOBALS['TL_LANG']['tl_style']['lineheight']     = array('Zeilenhöhe', 'Bestimmt die Zeilenhöhe innerhalb des Elements.');
$GLOBALS['TL_LANG']['tl_style']['whitespace']     = array('Automatischen Zeilenumbruch ausschalten', 'Wenn Sie diese Option wählen, werden die Zeilen innerhalb des Elements nicht automatisch umbrochen.');
$GLOBALS['TL_LANG']['tl_style']['list']           = array('Aufzählung', 'Listeneigenschaften bearbeiten (style, type, image).');
$GLOBALS['TL_LANG']['tl_style']['liststyletype']  = array('Listensymbol', 'Bitte wählen Sie ein Symbol für Listeneinträge.');
$GLOBALS['TL_LANG']['tl_style']['liststyleimage'] = array('Individuelles Symbol', 'Sie können hier den Pfad zu einem Bild eingeben, das als individuelles Symbol verwendet wird.');
$GLOBALS['TL_LANG']['tl_style']['own']            = array('Individueller CSS Code', 'Sie können hier individuellen CSS Code für die Formatdefinition eingeben.');


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
$GLOBALS['TL_LANG']['tl_style']['none']          = 'Kein Symbol';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style']['new']        = array('Neue Formatdefinition', 'Eine neue Formatdefinition erstellen');
$GLOBALS['TL_LANG']['tl_style']['show']       = array('Formatdefinitiondetails', 'Details der Formatdefinition ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_style']['cut']        = array('Formatdefinition verschieben ', 'Formatdefinition ID %s verschieben');
$GLOBALS['TL_LANG']['tl_style']['copy']       = array('Formatdefinition duplizieren', 'Formatdefinition ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_style']['delete']     = array('Formatdefinition löschen', 'Formatdefinition ID %s löschen');
$GLOBALS['TL_LANG']['tl_style']['edit']       = array('Formatdefinition bearbeiten', 'Formatdefinition ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_style']['editheader'] = array('Stylesheetkopf bearbeiten', 'Den Kopf dieses Stylesheets bearbeiten');
$GLOBALS['TL_LANG']['tl_style']['pasteafter'] = array('Am Anfang einfügen', 'Nach der Formatdefinition ID %s einfügen');
$GLOBALS['TL_LANG']['tl_style']['pastenew']   = array('Neue Formatdefinition am Anfang erstellen', 'Neue Formatdefinition nach der Formatdefinition ID %s erstellen');

?>