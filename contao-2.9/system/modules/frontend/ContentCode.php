<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Highlighter
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentCode
 *
 * Front end content element "code".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
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
	 * Return if the highlighter plugin is not loaded
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			return '<pre>' . $this->code . '</pre>';
		}

		return parent::generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$this->Template->code = htmlspecialchars($this->code);

		// Syntax highlighter
		if ($this->highlight)
		{
			$arrMapper = array
			(
				'AS3'        => 'shBrushAS3',
				'Bash'       => 'shBrushBash',
				'C'          => 'shBrushCpp',
				'CSharp'     => 'shBrushCSharp',
				'CSS'        => 'shBrushCss',
				'Delphi'     => 'shBrushDelphi',
				'Diff'       => 'shBrushDiff',
				'Groovy'     => 'shBrushGroovy',
				'Java'       => 'shBrushJava',
				'JavaFX'     => 'shBrushJavaFX',
				'JavaScript' => 'shBrushJScript',
				'Perl'       => 'shBrushPerl',
				'PHP'        => 'shBrushPhp',
				'PowerShell' => 'shBrushPowerShell',
				'Python'     => 'shBrushPython',
				'Ruby'       => 'shBrushRuby',
				'Scala'      => 'shBrushScala',
				'SQL'        => 'shBrushSql',
				'Text'       => 'shBrushPlain',
				'VB'         => 'shBrushVb',
				'XHTML'      => 'shBrushXml',
				'XML'        => 'shBrushXml'
			);

			$this->Template->shClass = 'brush: ' . strtolower($this->highlight);

			if ($this->shClass)
			{
				$this->Template->shClass .= '; ' . $this->shClass;
			}

			// Add CSS
			$GLOBALS['TL_CSS'][] = 'plugins/highlighter/styles/shCore.css';
			$GLOBALS['TL_CSS'][] = 'plugins/highlighter/styles/shThemeTYPOlight.css';

			// Add scripts
			$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/highlighter/scripts/shCore.js';
			$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/highlighter/scripts/' . $arrMapper[$this->highlight] . '.js';

			// Add head (do not add to scripts!)
			$GLOBALS['TL_HEAD'][] = '<script type="text/javascript" src="plugins/highlighter/scripts/shInit.js"></script>';
		}
	}
}

?>