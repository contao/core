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
 * Class BackendModule
 *
 * Parent class for back end modules that are not using the default engine.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
abstract class BackendModule extends \Backend
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate;

	/**
	 * Data container object
	 * @var object
	 */
	protected $objDc;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Initialize the object
	 * @param \DataContainer
	 */
	public function __construct(\DataContainer $objDc=null)
	{
		parent::__construct();
		$this->objDc = $objDc;
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

		if ($this->objDc->$strKey !== null)
		{
			return $this->objDc->$strKey;
		}

		return parent::__get($strKey);
	}


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$this->Template = new \BackendTemplate($this->strTemplate);
		$this->compile();

		return $this->Template->parse();
	}


	/**
	 * Compile the current element
	 */
	abstract protected function compile();
}
