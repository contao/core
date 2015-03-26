<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Model;


/**
 * Handle a set of models
 *
 * The class handles traversing a set of models and lazy loads the database
 * result rows upon their first usage.
 *
 * @author Tristan Lins <https://github.com/tristanlins>
 */
class Registry implements \Countable
{

	/**
	 * Object instance (Singleton)
	 * @var static
	 */
	protected static $objInstance;

	/**
	 * Models by table and PK
	 * @var array
	 */
	protected $arrRegistry;

	/**
	 * Aliases to PK's by table and column
	 * @var array
	 */
	protected $arrRegistryAliases;

	/**
	 * Models by object hash
	 * @var array
	 */
	protected $arrIdentities;


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 *
	 * @return static The object instance
	 */
	public static function getInstance()
	{
		if (static::$objInstance === null)
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}


	/**
	 * Count the elements
	 *
	 * @return integer The number of models
	 */
	public function count()
	{
		return count($this->arrIdentities);
	}


	/**
	 * Fetch a model by table name and primary key
	 *
	 * @param string  $strTable     The table name
	 * @param mixed   $varKey       The key
	 * @param string  $strColumn    The column name (by default: PK)
	 *
	 * @return \Model|null The model or null
	 */
	public function fetch($strTable, $varKey, $strColumn = null)
	{
		// Default is PK and is the most common case
		if ($strColumn === null)
		{
			if (isset($this->arrRegistry[$strTable][$varKey]))
			{
				return $this->arrRegistry[$strTable][$varKey];
			}

			return null;
		}

		/** @var \Model $strClass */
		$strClass = \Model::getClassFromTable($strTable);
		$strPk = $strClass::getPk();

		// Possible that one passed $strColumn === $strPk
		if ($strColumn === $strPk)
		{
			if (isset($this->arrRegistry[$strTable][$varKey]))
			{
				return $this->arrRegistry[$strTable][$varKey];
			}

			return null;
		}

		// Try to find in aliases
		if (isset($this->arrRegistryAliases[$strTable][$strColumn][$varKey]))
		{
			$strPk = $this->arrRegistryAliases[$strTable][$strColumn][$varKey];

			if (isset($this->arrRegistry[$strTable][$strPk]))
			{
				return $this->arrRegistry[$strTable][$strPk];
			}
		}

		return null;
	}


	/**
	 * Register a model in the registry
	 *
	 * @param \Model $objModel The model object
	 *
	 * @throws \RuntimeException If the instance exists already
	 */
	public function register(\Model $objModel)
	{
		$intObjectId = spl_object_hash($objModel);

		// The model has been registered already
		if (isset($this->arrIdentities[$intObjectId]))
		{
			return;
		}

		$strTable = $objModel->getTable();

		if (!is_array($this->arrRegistry[$strTable]))
		{
			$this->arrRegistry[$strTable] = array();
			$this->arrRegistryAliases[$strTable] = array();
		}

		$strPk = $objModel->getPk();
		$varPk = $objModel->$strPk;

		// Another model object is pointing to the DB record already
		if (isset($this->arrRegistry[$strTable][$varPk]))
		{
			throw new \RuntimeException("The registry already contains an instance for $strTable::$strPk($varPk)");
		}

		$this->arrIdentities[$intObjectId] = $objModel;
		$this->arrRegistry[$strTable][$varPk] = $objModel;

		// Also store aliases
		foreach ($objModel->getUniqueFields() as $strColumn)
		{
			$this->arrRegistryAliases[$strTable][$strColumn][$objModel->$strColumn] = $varPk;
		}
	}


	/**
	 * Unregister a model from the registry
	 *
	 * @param \Model $objModel The model object
	 */
	public function unregister(\Model $objModel)
	{
		$intObjectId = spl_object_hash($objModel);

		// The model is not registered
		if (!isset($this->arrIdentities[$intObjectId]))
		{
			return;
		}

		$strTable = $objModel->getTable();
		$strPk    = $objModel->getPk();
		$intPk    = $objModel->$strPk;

		unset($this->arrIdentities[$intObjectId]);
		unset($this->arrRegistry[$strTable][$intPk]);

		// Unset aliases
		foreach ($objModel->getUniqueFields() as $strColumn)
		{
			unset($this->arrRegistryAliases[$strTable][$strColumn][$objModel->$strColumn]);
		}
	}


	/**
	 * Check if a model is registered
	 *
	 * @param \Model $objModel The model object
	 *
	 * @return boolean True if the model is registered
	 */
	public function isRegistered(\Model $objModel)
	{
		$intObjectId = spl_object_hash($objModel);

		return isset($this->arrIdentities[$intObjectId]);
	}
}
