<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

use \Database_Result, \System, \Exception;


/**
 * Class Model_Collection
 *
 * Provide methods to handle multiple models.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Model_Collection extends System
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Database result
	 * @var \Database_Result
	 */
	protected $objResult;

	/**
	 * Current index
	 * @var integer
	 */
	protected $intIndex = -1;

	/**
	 * End indicator
	 * @var boolean
	 */
	protected $blnDone = false;

	/**
	 * Models array
	 * @var array
	 */
	protected $arrModels = array();


	/**
	 * Store the database result
	 * @param \Database_Result
	 * @param string
	 */
	public function __construct(Database_Result $objResult, $strTable)
	{
		parent::__construct();
		$this->objResult = $objResult;
		$this->strTable = $strTable;
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrModels[$this->intIndex]->$strKey = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed|null
	 */
	public function __get($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		if (isset($this->arrModels[$this->intIndex]->$strKey))
		{
			return $this->arrModels[$this->intIndex]->$strKey;
		}

		return null;
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return isset($this->arrModels[$this->intIndex]->$strKey);
	}


	/**
	 * Return the current row
	 * @return array
	 */
	public function row()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrModels[$this->intIndex]->row();
	}


	/**
	 * Set the current record from an array
	 * @param array
	 * @return \Model_Collection
	 */
	public function setRow(Array $arrData)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrModels[$this->intIndex]->setRow($arrData);
		return $this;
	}


	/**
	 * Save the current model
	 * @return \Model_Collection
	 */
	public function save()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrModels[$this->intIndex]->save();
		return $this;
	}


	/**
	 * Delete the current model and return the number of affected rows
	 * @return integer
	 */
	public function delete()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrModels[$this->intIndex]->delete();
	}


	/**
	 * Lazy load related records
	 * @param string
	 * @return \Model|\Model_Collection
	 */
	public function getRelated($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrModels[$this->intIndex]->getRelated($strKey);
	}


	/**
	 * Return the number of rows
	 * @return integer
	 */
	public function count()
	{
		return $this->objResult->numRows;
	}


	/**
	 * Go to the first row
	 * @return \Model_Collection
	 */
	public function first()
	{
		if (empty($this->arrModels))
		{
			$this->fetchNext();
		}

		$this->intIndex = 0;
		return $this;
	}


	/**
	 * Go to the previous row
	 * @return \Model_Collection
	 */
	public function prev()
	{
		if ($this->intIndex < 1)
		{
			return false;
		}

		--$this->intIndex;
		return $this;
	}


	/**
	 * Return the current model
	 * @return \Model
	 */
	public function current()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrModels[$this->intIndex];
	}


	/**
	 * Go to the next row
	 * @return \Model_Collection|boolean
	 */
	public function next()
	{
		if ($this->blnDone)
		{
			return false;
		}

		if (!isset($this->arrModels[$this->intIndex + 1]))
		{
			if ($this->fetchNext() == false)
			{
				$this->blnDone = true;
				return false;
			}
		}

		++$this->intIndex;
		return $this;
	}


	/**
	 * Go to the last row
	 * @return \Model_Collection
	 */
	public function last()
	{
		if (!$this->blnDone)
		{
			while ($this->next());
		}

		$this->blnDone = true;
		$this->intIndex = count($this->arrModels) - 1;
		return $this;
	}


	/**
	 * Reset the model
	 * @return \Model_Collection
	 */
	public function reset()
	{
		$this->intIndex = -1;
		$this->blnDone = false;
		return $this;
	}


	/**
	 * Fetch a column of each row
	 * @param string
	 * @return array
	 * @throws \Exception
	 */
	public function fetchEach($strKey)
	{
		$this->reset();
		$return = array();

		while ($this->next())
		{
			$return[] = $this->$strKey;
		}

		return $return;
	}


	/**
	 * Fetch the next result row and create the model
	 * @return boolean
	 */
	protected function fetchNext()
	{
		if ($this->objResult->next() == false)
		{
			return false;
		}

		$strClass = $this->getModelClassFromTable($this->strTable);
		$this->arrModels[$this->intIndex + 1] = new $strClass($this->objResult);

		return true;
	}
}
