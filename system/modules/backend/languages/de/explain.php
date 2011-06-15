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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Shortcuts
 */
$GLOBALS['TL_LANG']['XPL']['shortcuts'] = array
(
	array('colspan', 'Backend-Tastaturkürzel'),
	array('[ALT] + <strong>s</strong>', 'Speichern'),
	array('[ALT] + <strong>c</strong>', 'Speichern und schließen'),
	array('[ALT] + <strong>e</strong>', 'Speichern und bearbeiten'),
	array('[ALT] + <strong>n</strong>', 'Neues Element'),
	array('[ALT] + <strong>b</strong>', 'Zurück'),
	array('[ALT] + <strong>t</strong>', 'Nach oben'),
	array('[ALT] + <strong>f</strong>', 'Frontend-Vorschau'),
	array('[ALT] + <strong>q</strong>', 'Abmelden')
);


/**
 * Insert tags
 */
$GLOBALS['TL_LANG']['XPL']['insertTags'] = array
(
	array('Rich Text Editor', 'Weitere Informationen zu TinyMCE finden Sie unter <a href="http://tinymce.moxiecode.com" title="TinyMCE by moxiecode" onclick="window.open(this.href); return false;">http://tinymce.moxiecode.com</a>.'),
	array('Inserttags', 'Weitere Informationen zu Inserttags finden Sie unter <a href="http://www.contao.org/inserttags.html" title="Contao Online-Dokumentation" onclick="window.open(this.href); return false;">http://www.contao.org/inserttags.html</a>.'),
	array('Code Editor', 'Weitere Informationen zu CodeMirror finden Sie unter <a href="http://codemirror.net" title="CodeMirror by Marijn Haverbeke" onclick="window.open(this.href); return false;">http://codemirror.net</a>.')
);


/**
 * Date format
 */
$GLOBALS['TL_LANG']['XPL']['dateFormat'] = array
(
	array('colspan', 'Contao unterstützt alle Datums- und Zeitformate, die mit der PHP-Funktion date() geparst werden können. Um alle Eingaben in einen UNIX-Zeitstempel umwandeln zu können, sind im Backend jedoch ausschließlich numerische Formate (j, d, m, n, y, Y, g, G, h, H, i, s) erlaubt.<br><br><strong>Abweichende Frontend-Formate können in der Seitenstruktur erfasst werden.</strong><br><br><em>Hier sind einige Beispiele gültiger Datums- und Zeitangaben</em>:'),
	array('Y-m-d', 'JJJJ-MM-TT, international ISO-8601, z.B. 2005-01-28'),
	array('m/d/Y', 'MM/TT/JJJJ, Englisches Format, z.B. 01/28/2005'),
	array('d.m.Y', 'TT.MM.JJJJ, Deutsches Format, z.B. 28.01.2005'),
	array('y-n-j', 'JJ-M-T, ohne führende Nullen, z.B. 05-1-28'),
	array('Ymd', 'JJJJMMTT, Zeitstempel, z.B. 20050128'),
	array('H:i:s', '24 Stunden, Minuten und Sekunden, z.B. 20:36:59'),
	array('g:i', '12 Stunden ohne führende Nullen sowie Minuten, z.B. 8:36')
);


/**
 * Syntax highlighter
 */
$GLOBALS['TL_LANG']['XPL']['highlighter'] = array
(
	array('Syntaxhervorhebung', 'Weitere Informationen zur Konfiguration des Syntax-Highlighters finden Sie unter <a href="http://alexgorbatchev.com/wiki/SyntaxHighlighter:Configuration#SyntaxHighlighter.defaults" title="SyntaxHighlighter by Alex Gorbatchev" onclick="window.open(this.href); return false;">http://alexgorbatchev.com</a>.')
);

?>