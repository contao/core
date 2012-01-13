<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 */


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_merge']['emptySelection'] = 'Bitte wählen Sie mindestens ein Modul aus!';
$GLOBALS['TL_LANG']['tl_merge']['autoloadExists'] = 'Das Modul "%s" scheint bereits konvertiert worden zu sein. Die Option "Überschreiben" erzwingt das Update.';
$GLOBALS['TL_LANG']['tl_merge']['headline']       = 'Contao 2-Erweiterungen konvertieren';
$GLOBALS['TL_LANG']['tl_merge']['available']      = 'Verfügbare Module';
$GLOBALS['TL_LANG']['tl_merge']['override']       = 'Bestehende Dateien überschreiben';
$GLOBALS['TL_LANG']['tl_merge']['explain']        = 'Mit diesem Skript können Sie Contao 2-Erweiterungen für Contao 3 aufbereiten. Hierbei wird die Datei <em>config/autoload.php</em> mit den Pfaden zu den vorhandenen Klassen und Templates angelegt. Falls in einer PHP-Klasse der <em>namespace</em>-Befehl gefunden wird, wird dieser ebenfalls übernommen.';

?>