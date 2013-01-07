<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Model;


/**
 * Handles a set models
 * 
 * The class handles traversing a set of models and lazy loads the database
 * result rows upon their first usage.
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Collection extends \System
{

	/**
	 * Table name
	 * @var string
	 */
	protected $strTable;

	/**
	 * Database result
	 * @var \Database\Result
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
	 * Models
	 * @var array
	 */
	protected $arrModels = array();


	/**
	 * Store the database result and table name
	 * 
	 * @param \Database\Result $objResult The database result object
	 * @param string           $strTable  The table name
	 */
	public function __construct(\Database\Result $objResult, $strTable)
	{
		parent::__construct();
		$this->objResult = $objResult;
		$this->strTable = $strTable;
	}


	/**
	 * Set an object property
	 * 
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
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
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return mixed|null The property value or null
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
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return boolean True if the property is set
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
	 * Return the current row as associative array
	 * 
	 * @return array The current row as array
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
	 * Set the current row from an array
	 * 
	 * @param array $arrData The row data as array
	 * 
	 * @return \Model\Collection The model collection object
	 */
	public function setRow(array $arrData)
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
	 * 
	 * @return \Model\Collection The model collection object
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
	 * 
	 * @return integer The number of affected rows
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
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return \Model|\Model\Collection The model or a model collection if there are multiple rows
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
	 * Return the number of rows in the result set
	 * 
	 * @return integer The number of rows
	 */
	public function count()
	{
		return $this->objResult->numRows;
	}


	/**
	 * Go to the first row
	 * 
	 * @return \Model\Collection The model collection object
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
	 * 
	 * @return \Model\Collection|false The model collection object or false if there is no previous row
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
	 * 
	 * @return \Model The model object
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
	 * 
	 * @return \Model\Collection|boolean The model collection object or false if there is no next row
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
	 * 
	 * @return \Model\Collection The model collection object
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
	 * 
	 * @return \Model\Collection The model collection object
	 */
	public function reset()
	{
		$this->intIndex = -1;
		$this->blnDone = false;
		return $this;
	}


	/**
	 * Fetch a column of each row
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return array An array with all property values
	 */
	public function fetchEach($strKey)
	{
		$this->reset();
		$return = array();

		while ($this->next())
		{
			$strPk = $this->current()->getPk();

			if ($strKey == 'id' && isset($this->$strPk))
			{
				$return[$this->$strPk] = $this->$strKey;
			}
			else
			{
				$return[] = $this->$strKey;
			}
		}

		return $return;
	}


	/**
	 * Fetch the next result row and create the model
	 * 
	 * @return boolean True if there was another row
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
