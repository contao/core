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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentCode
 *
 * Front end content element "code".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class ContentCode extends \ContentElement
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
			$GLOBALS['TL_CSS'][] = 'assets/highlighter/'.HIGHLIGHTER.'/shCore.css||static';

			// Add the JavaScripts
			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/highlighter/'.HIGHLIGHTER.'/XRegExp.js|static';
			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/highlighter/'.HIGHLIGHTER.'/shCore.js|static';
			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/highlighter/'.HIGHLIGHTER.'/' . $arrMapper[$this->highlight] . '.js|static';

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
