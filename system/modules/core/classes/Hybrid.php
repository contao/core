<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Hybrid
 *
 * Parent class for objects that can be modules or content elements.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
abstract class Hybrid extends \Frontend
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
	 * Model
	 * @var Model
	 */
	protected $objModel;

	/**
	 * Parent element
	 * @var Model
	 */
	protected $objParent;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Style array
	 * @var array
	 */
	protected $arrStyle = array();


	/**
	 * Initialize the object
	 * @param object
	 */
	public function __construct($objElement)
	{
		parent::__construct();

		// Store the parent element (see #4556)
		if ($objElement instanceof \Model)
		{
			$this->objParent = $objElement;
		}
		elseif ($objElement instanceof \Model\Collection)
		{
			$this->objParent = $objElement->current();
		}

		if ($this->strKey == '' || $this->strTable == '')
		{
			return;
		}

		$strModelClass = $this->getModelClassFromTable($this->strTable);

		// Load the model
		if (class_exists($strModelClass))
		{
			$objHybrid = $strModelClass::findByPk($objElement->{$this->strKey});

			if ($objHybrid === null)
			{
				return;
			}

			$this->objModel = $objHybrid;
		}
		// Directly query the database (backwards compatibility)
		else
		{
			$objHybrid = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
										->limit(1)
										->execute($objElement->{$this->strKey});

			if ($objHybrid->numRows < 1)
			{
				return;
			}
		}

		$this->arrData = $objHybrid->row();

		// Get space and CSS ID from the parent element (!)
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
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Return the parent object
	 * @return object
	 */
	public function getParent()
	{
		return $this->objParent;
	}


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		if ($this->objParent instanceof \ContentModel && !BE_USER_LOGGED_IN && ($this->objParent->invisible || ($this->objParent->start > 0 && $this->objParent->start > time()) || ($this->objParent->stop > 0 && $this->objParent->stop < time())))
		{
			return '';
		}

		if ($this->arrData['space'][0] != '')
		{
			$this->arrStyle[] = 'margin-top:'.$this->arrData['space'][0].'px;';
		}

		if ($this->arrData['space'][1] != '')
		{
			$this->arrStyle[] = 'margin-bottom:'.$this->arrData['space'][1].'px;';
		}

		$this->Template = new \FrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);

		$this->compile();

		$this->Template->style = !empty($this->arrStyle) ? implode(' ', $this->arrStyle) : '';
		$this->Template->cssID = ($this->cssID[0] != '') ? ' id="' . $this->cssID[0] . '"' : '';
		$this->Template->class = trim($this->typePrefix . $this->strKey . ' ' . $this->cssID[1]);

		if ($this->Template->headline == '')
		{
			$this->Template->headline = $this->headline;
		}

		if ($this->Template->hl == '')
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
