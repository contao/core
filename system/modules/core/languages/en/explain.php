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
 * Insert tags
 */
$GLOBALS['TL_LANG']['XPL']['insertTags'] = array
(
	array('Rich Text Editor', 'For more information about TinyMCE please visit <a href="http://www.tinymce.com/" title="TinyMCE by moxiecode" target="_blank">http://www.tinymce.com/</a>.'),
	array('Insert tags', 'For more information on insert tags please visit <a href="http://www.contao.org/en/insert-tags.html" title="Contao online documentation" target="_blank">http://www.contao.org/en/insert-tags.html</a>.'),
	array('Code Editor', 'For more information about CodeMirror please visit <a href="http://codemirror.net" title="EditArea by Marijn Haverbeke" target="_blank">http://codemirror.net</a>.')
);


/**
 * Date format
 */
$GLOBALS['TL_LANG']['XPL']['dateFormat'] = array
(
	array('colspan', 'Contao supports all date and time formats that can be parsed with the PHP date() function. However, to ensure that any input can be transformed into a UNIX timestamp, you can only use numeric date and time formats (j, d, m, n, y, Y, g, G, h, H, i, s) in the back end.<br><br><strong>You can enter variant front end formats in the site structure (website root pages).</strong><br><br><em>Here are some examples of valid date and time formats</em>:'),
	array('Y-m-d', 'YYYY-MM-DD, international ISO-8601, e.g. 2005-01-28'),
	array('m/d/Y', 'MM/DD/YYYY, english format, e.g. 01/28/2005'),
	array('d.m.Y', 'DD.MM.YYYY, german format, e.g. 28.01.2005'),
	array('y-n-j', 'YY-M-D, without leading zeros, e.g. 05-1-28'),
	array('Ymd', 'YYYYMMDD, timestamp, e.g. 20050128'),
	array('H:i:s', '24 hours, minutes and seconds, e.g. 20:36:59'),
	array('g:i', '12 hours without leading zeros and minutes, e.g. 8:36')
);


/**
 * Syntax highlighter
 */
$GLOBALS['TL_LANG']['XPL']['highlighter'] = array
(
	array('Rich Text Editor', 'For more information about how to configure the syntax highlighter please visit <a href="http://alexgorbatchev.com/wiki/SyntaxHighlighter:Configuration#SyntaxHighlighter.defaults" title="SyntaxHighlighter by Alex Gorbatchev" target="_blank">http://alexgorbatchev.com</a>.')
);
