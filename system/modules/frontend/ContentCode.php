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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentCode
 *
 * Front end content element "code".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
			$return = '<pre>'. specialchars($this->code) .'</pre>';

			if ($this->headline != '')
			{
				$return = '<'. $this->hl .'>'. $this->headline .'</'. $this->hl .'>'. $return;
			}

			return $return;
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
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

			// Add the style sheet
			$GLOBALS['TL_CSS'][] = 'plugins/highlighter/shCore.css';

			// Add the core scripts
			$objCombiner = new Combiner();
			$objCombiner->add('plugins/highlighter/XRegExp.js', HIGHLIGHTER);
			$objCombiner->add('plugins/highlighter/shCore.js', HIGHLIGHTER);
			$GLOBALS['TL_JAVASCRIPT'][] = $objCombiner->getCombinedFile(TL_PLUGINS_URL);

			// Add the brushes separately in case there are multiple code elements
			$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/highlighter/' . $arrMapper[$this->highlight] . '.js';

			global $objPage;

			// Initialization
			if ($objPage->outputFormat == 'xhtml')
			{
				$strInit  = '<script type="text/javascript">' . "\n";
				$strInit .= '/* <![CDATA[ */' . "\n";
			}
			else
			{
				$strInit  = '<script>' . "\n";
			}

			$strInit .= 'SyntaxHighlighter.defaults.toolbar = false;' . "\n";
			$strInit .= 'SyntaxHighlighter.all();' . "\n";

			if ($objPage->outputFormat == 'xhtml')
			{
				$strInit .= '/* ]]> */' . "\n";
			}

			$strInit .= '</script>';

			// Add the initialization script to the head section and not (!) to TL_JAVASCRIPT
			$GLOBALS['TL_HEAD'][] = $strInit;
		}
	}
}

?>