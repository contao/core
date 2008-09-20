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
 * Class Hybrid
 *
 * Parent class for objects that can be modules or content elements.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
abstract class Hybrid extends Frontend
{

	/**
	 * Key
	 * @var string
	 */
	protected $strKey;

	/**
	 * Table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Initialize the object
	 * @param object
	 * @return string
	 */
	public function __construct(Database_Result $objElement)
	{
		parent::__construct();

		if (!strlen($this->strKey) || !strlen($this->strTable))
		{
			return;
		}

		$strKey = $this->strKey;

		$objHybrid = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
									->limit(1)
									->execute($objElement->$strKey);

		if ($objHybrid->numRows < 1)
		{
			return;
		}

		$this->arrData = $objHybrid->row();

		// Get alignment, space and css ID from the parent element (!)
		$this->align = $objElement->align;
		$this->space = deserialize($objElement->space);
		$this->cssID = deserialize($objElement->cssID, true);

		$this->typePrefix = $objElement->typePrefix;

		$arrHeadline = deserialize($objElement->headline);
		$this->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		$this->hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$style = array();

		// Margin
		if (strlen($this->arrData['space'][0]))
		{
			$style[] = 'margin-top:'.$this->arrData['space'][0].'px;';
		}

		if (strlen($this->arrData['space'][1]))
		{
			$style[] = 'margin-bottom:'.$this->arrData['space'][1].'px;';
		}

		// Align
		if (strlen($this->align))
		{
			$style[] = 'text-align:'.$this->align.';';
		}

		$this->Template = new FrontendTemplate($this->strTemplate);

		$this->compile();

		$this->Template->style = count($style) ? implode(' ', $style) : '';
		$this->Template->cssID = strlen($this->cssID[0]) ? ' id="' . $this->cssID[0] . '"' : '';
		$this->Template->class = trim($this->typePrefix . $this->strKey . ' ' . $this->cssID[1]);

		if (!strlen($this->Template->headline))
		{
			$this->Template->headline = $this->headline;
		}

		if (!strlen($this->Template->hl))
		{
			$this->Template->hl = $this->hl;
		}

		return $this->Template->parse();
	}


	/**
	 * Compile the current element
	 */
	abstract protected function compile();
}

?>