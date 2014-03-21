<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * Class ContentElement
 *
 * Parent class for content elements.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
abstract class ContentElement extends \Frontend
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate;

	/**
	 * Column
	 * @var string
	 */
	protected $strColumn;

	/**
	 * Model
	 * @var Model
	 */
	protected $objModel;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Processed folders
	 * @var array
	 */
	protected $arrProcessed = array();

	/**
	 * Style array
	 * @var array
	 */
	protected $arrStyle = array();


	/**
	 * Initialize the object
	 * @param object
	 * @param string
	 */
	public function __construct($objElement, $strColumn='main')
	{
		if ($objElement instanceof \Model)
		{
			$this->objModel = $objElement;
		}
		elseif ($objElement instanceof \Model\Collection)
		{
			$this->objModel = $objElement->current();
		}

		parent::__construct();

		$this->arrData = $objElement->row();
		$this->space = deserialize($objElement->space);
		$this->cssID = deserialize($objElement->cssID, true);

		$arrHeadline = deserialize($objElement->headline);
		$this->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		$this->hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
		$this->strColumn = $strColumn;
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
	 * Return the model
	 * @return \Model
	 */
	public function getModel()
	{
		return $this->objModel;
	}


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'FE' && !BE_USER_LOGGED_IN && ($this->invisible || ($this->start != '' && $this->start > time()) || ($this->stop != '' && $this->stop < time())))
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

		// Do not change this order (see #6191)
		$this->Template->style = !empty($this->arrStyle) ? implode(' ', $this->arrStyle) : '';
		$this->Template->class = trim('ce_' . $this->type . ' ' . $this->cssID[1]);
		$this->Template->cssID = ($this->cssID[0] != '') ? ' id="' . $this->cssID[0] . '"' : '';

		$this->Template->inColumn = $this->strColumn;

		if ($this->Template->headline == '')
		{
			$this->Template->headline = $this->headline;
		}

		if ($this->Template->hl == '')
		{
			$this->Template->hl = $this->hl;
		}

		if (!empty($this->objModel->classes) && is_array($this->objModel->classes))
		{
			$this->Template->class .= ' ' . implode(' ', $this->objModel->classes);
		}

		return $this->Template->parse();
	}


	/**
	 * Compile the content element
	 */
	abstract protected function compile();


	/**
	 * Find a content element in the TL_CTE array and return the class name
	 *
	 * @param string $strName The content element name
	 *
	 * @return string The class name
	 */
	public static function findClass($strName)
	{
		foreach ($GLOBALS['TL_CTE'] as $v)
		{
			foreach ($v as $kk=>$vv)
			{
				if ($kk == $strName)
				{
					return $vv;
				}
			}
		}

		return '';
	}
}
