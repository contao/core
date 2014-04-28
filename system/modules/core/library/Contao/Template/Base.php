<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Template;


/**
 * Provides shared logic for template classes
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
 */
abstract class Base extends \Controller
{

	/**
	 * Template file
	 * @var string
	 */
	protected $strTemplate;

	/**
	 * Parent template
	 * @var string
	 */
	protected $strParent;

	/**
	 * Output format
	 * @var string
	 */
	protected $strFormat = 'html5';

	/**
	 * Tag ending
	 * @var string
	 */
	protected $strTagEnding = '>';

	/**
	 * Blocks
	 * @var array
	 */
	protected $arrBlocks = array();

	/**
	 * Block names
	 * @var array
	 */
	protected $arrBlockNames = array();

	/**
	 * Fragments
	 * @var array
	 */
	protected $arrFragments = array();

	/**
	 * Insert a parent block
	 * @var boolean
	 */
	protected $blnHasParent = false;


	/**
	 * Parse the template file and return it as string
	 *
	 * @return string The template markup
	 */
	public function parse()
	{
		$strPath = $this->getTemplate($this->strTemplate, $this->strFormat);

		ob_start();
		include $strPath;
		$this->arrFragments[] = ob_get_contents();
		ob_end_clean();

		// Include the parent templates
		while ($this->strParent !== null)
		{
			// Unset everything from the child template that is not a block
			$this->arrFragments = array();

			$strParent = $this->getTemplate($this->strParent, $this->strFormat);
			$this->strParent = null;

			ob_start();
			include $strParent;
			$this->arrFragments[] = ob_get_contents();
			ob_end_clean();
		}

		$strBuffer = '';

		// Implode the fragments
		foreach ($this->arrFragments as $v)
		{
			if (!is_array($v))
			{
				$v = array($v);
			}

			// Insert the content of the parent block
			if ($this->blnHasParent && count($v) > 1)
			{
				for ($i=count($v)-2; $i>=0; $i--)
				{
					$v[$i] = str_replace('[[TL_PARENT]]', $v[$i+1], $v[$i]);
				}
			}

			$strBuffer .= $v[0];
		}

		// Reset the internal arrays
		$this->arrBlocks = array();
		$this->arrFragments = array();
		$this->blnHasParent = false;

		// Add start and end markers in debug mode
		if (\Config::get('debugMode'))
		{
			$strRelPath = str_replace(TL_ROOT . '/', '', $strPath);
			$strBuffer = "\n<!-- TEMPLATE START: $strRelPath -->\n$strBuffer\n<!-- TEMPLATE END: $strRelPath -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Extend another template
	 *
	 * @param string $name The template name
	 */
	public function extend($name)
	{
		$this->strParent = $name;
	}


	/**
	 * Insert the content of the parent block
	 */
	public function parent()
	{
		echo '[[TL_PARENT]]';
		$this->blnHasParent = true;
	}


	/**
	 * Start a new block
	 *
	 * @param string $name The block name
	 */
	public function block($name)
	{
		$this->arrBlockNames[] = $name;

		// Clean the output buffer
		$this->arrFragments[] = ob_get_contents();
		ob_end_clean();

		// Start a new output buffer
		ob_start();
	}


	/**
	 * End a block
	 *
	 * @throws \Exception If there is no open block
	 */
	public function endblock()
	{
		if (empty($this->arrBlockNames))
		{
			throw new \Exception('You must start a block before you can end it');
		}

		// Get the block name
		$name = array_pop($this->arrBlockNames);

		// Clean the output buffer
		$this->arrBlocks[$name][] = ob_get_contents();
		ob_end_clean();

		// Add the fragment
		$this->arrFragments[$name] = $this->arrBlocks[$name];

		// Start a new output buffer
		ob_start();
	}


	/**
	 * Insert a template
	 *
	 * @param string $name The template name
	 * @param array  $data An optional data array
	 */
	public function insert($name, array $data=null)
	{
		$tpl = new static($name);

		if ($data !== null)
		{
			$tpl->setData($data);
		}

		echo $tpl->parse();
	}
}
