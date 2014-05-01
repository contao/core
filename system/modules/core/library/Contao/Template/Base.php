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
	 * Parse the template file and return it as string
	 *
	 * @return string The template markup
	 */
	public function parse()
	{
		// Start with the template itself
		$this->strParent = $this->strTemplate;

		// Include the parent templates
		while ($this->strParent !== null)
		{
			$strParent = $this->getTemplate($this->strParent, $this->strFormat);
			$this->strParent = null;

			ob_start();
			include $strParent;
			if ($this->strParent === null)
			{
				// Capture the template output for the root template
				$strBuffer = ob_get_contents();
			}
			ob_end_clean();
		}

		// Reset the internal arrays
		$this->arrBlocks = array();

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
	}


	/**
	 * Start a new block
	 *
	 * @param string $name The block name
	 */
	public function block($name)
	{
		$this->arrBlockNames[] = $name;

		// Root template
		if ($this->strParent === null)
		{
			if (!isset($this->arrBlocks[$name]))
			{
				$this->arrBlocks[$name] = '[[TL_PARENT]]';
			}
			elseif (is_array($this->arrBlocks[$name]))
			{
				// Combine the contents of the child blocks
				$this->arrBlocks[$name] = array_reduce(
					$this->arrBlocks[$name] ?: array(),
					function($strCurrent, $strParent) {
						return str_replace('[[TL_PARENT]]', $strParent, $strCurrent);
					},
					'[[TL_PARENT]]'
				);
			}

			if (strpos($this->arrBlocks[$name], '[[TL_PARENT]]') !== false)
			{
				$arrChunks = explode('[[TL_PARENT]]', $this->arrBlocks[$name], 2);
				echo $arrChunks[0];
			}
			else
			{
				echo $this->arrBlocks[$name];
				// Start a output buffer to remove all block contents afterwards
				ob_start();
			}
		}

		// Child template
		else
		{
			// Clean the output buffer
			ob_end_clean();

			if (count($this->arrBlockNames) > 1)
			{
				throw new \Exception('Nested blocks are not allowed for child templates');
			}

			// Start a new output buffer
			ob_start();
		}
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

		// Root template
		if ($this->strParent === null)
		{
			if (strpos($this->arrBlocks[$name], '[[TL_PARENT]]') !== false)
			{
				$arrChunks = explode('[[TL_PARENT]]', $this->arrBlocks[$name], 2);
				echo $arrChunks[1];
			}
			else
			{
				// Remove all block contents because it was overwritten
				ob_end_clean();
			}
		}
		// Child template
		else
		{
			// Clean the output buffer
			$this->arrBlocks[$name][] = ob_get_contents();
			ob_end_clean();

			// Start a new output buffer
			ob_start();
		}
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
