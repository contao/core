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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentCode
 *
 * Front end content element "code".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ContentCode extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_code';

	/**
	 * Code
	 * @var string
	 */
	protected $strCode;


	/**
	 * Return if the highlighter plugin is not loaded
	 * @return string
	 */
	public function generate()
	{
		if (!is_dir(TL_ROOT . '/plugins/dpsyntax'))
		{
			return '';
		}

		$this->import('String');

		$code = $this->String->decodeEntities($this->code);
		$code = str_ireplace(array('<u>', '</u>'), array('<span style="text-decoration:underline;">', '</span>'), $code);
		$code = $this->String->encodeEmail(htmlspecialchars($code));

		$this->strCode = $code;

		if (TL_MODE == 'BE')
		{
			return '<pre>' . $this->strCode . '</pre>';
		}

		return parent::generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$this->Template->preClass = 'simple';
		$this->Template->preId = 'dp_' . $this->id;
		$this->Template->code = $this->strCode;

		// Syntax highlighter
		if ($this->highlight)
		{
			$arrMapper = array
			(
				'C'          => 'shBrushCpp',
				'CSharp'     => 'shBrushCSharp',
				'CSS'        => 'shBrushCss',
				'Delphi'     => 'shBrushDelphi',
				'HTML'       => 'shBrushXml',
				'Java'       => 'shBrushJava',
				'JavaScript' => 'shBrushJScript',
				'PHP'        => 'shBrushPhp',
				'Python'     => 'shBrushPython',
				'Ruby'       => 'shBrushRuby',
				'SQL'        => 'shBrushSql',
				'VB'         => 'shBrushVb',
				'XML'        => 'shBrushXml'
			);

			$this->Template->preClass = strtolower($this->highlight);
			$this->Template->js = $arrMapper[$this->highlight];

			// Add scripts
			$GLOBALS['TL_CSS'][] = 'plugins/dpsyntax/dpsyntax.css';
			$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/dpsyntax/shCore.js';
			$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/dpsyntax/' . $arrMapper[$this->highlight] . '.js';
			$GLOBALS['TL_HEAD'][] = '<!--[if IE]><link rel="stylesheet" href="plugins/dpsyntax/iefixes.css" type="text/css" media="screen" /><![endif]-->';
		}
	}
}

?>