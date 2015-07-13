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
	protected $arrAliases;

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
	 * @param string  $strTable The table name
	 * @param mixed   $varKey   The key
	 * @param string  $strAlias An optional alias
	 *
	 * @return \Model|null The model or null
	 */
	public function fetch($strTable, $varKey, $strAlias=null)
	{
		/** @var \Model $strClass */
		$strClass = \Model::getClassFromTable($strTable);
		$strPk    = $strClass::getPk();

		// Search by PK (most common case)
		if ($strAlias === null || $strAlias == $strPk)
		{
			if (isset($this->arrRegistry[$strTable][$varKey]))
			{
				return $this->arrRegistry[$strTable][$varKey];
			}

			return null;
		}

		// Try to find the model by one of its aliases
		return $this->fetchByAlias($strTable, $strAlias, $varKey);
	}


	/**
	 * Fetch a model by one of its aliases
	 *
	 * @param string  $strTable The table name
	 * @param string  $strAlias The alias
	 * @param mixed   $varValue The alias value
	 *
	 * @return \Model|null The model or null
	 */
	public function fetchByAlias($strTable, $strAlias, $varValue)
	{
		if (isset($this->arrAliases[$strTable][$strAlias][$varValue]))
		{
			$strPk = $this->arrAliases[$strTable][$strAlias][$varValue];

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

		if (!is_array($this->arrAliases[$strTable]))
		{
			$this->arrAliases[$strTable] = array();
		}

		if (!is_array($this->arrRegistry[$strTable]))
		{
			$this->arrRegistry[$strTable] = array();
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

		// Allow the model to modify the registry
		$objModel->onRegister($this);
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

		// Allow the model to modify the registry
		$objModel->onUnregister($this);
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


	/**
	 * Register an alias for a model
	 *
	 * @param \Model $objModel The model object
	 * @param string $strAlias The alias name
	 * @param mixed  $varValue The value of the alias
	 *
	 * @throws \RuntimeException If the alias is already registered
	 */
	public function registerAlias(\Model $objModel, $strAlias, $varValue)
	{
		$strTable = $objModel->getTable();
		$strPk    = $objModel->getPk();
		$varPk    = $objModel->$strPk;

		if (isset($this->arrAliases[$strTable][$strAlias][$varValue]))
		{
			throw new \RuntimeException("The registry already contains an alias for $strTable::$strPk($varPk) ($strAlias/$varValue)");
		}

		$this->arrAliases[$strTable][$strAlias][$varValue] = $varPk;
	}


	/**
	 * Unregister an alias
	 *
	 * @param \Model $objModel The model object
	 * @param string $strAlias The alias name
	 * @param mixed  $varValue The value of the alias
	 *
	 * @throws \InvalidArgumentException If the alias is not registered
	 */
	public function unregisterAlias(\Model $objModel, $strAlias, $varValue)
	{
		$strTable = $objModel->getTable();

		if (!isset($this->arrAliases[$strTable][$strAlias][$varValue]))
		{
			$strPk = $objModel->getPk();
			$varPk = $objModel->$strPk;

			throw new \RuntimeException("The registry does not contain an alias for $strTable::$strPk($varPk) ($strAlias/$varValue)");
		}

		unset($this->arrAliases[$strTable][$strAlias][$varValue]);
	}


	/**
	 * Check if an alias is registered
	 *
	 * @param \Model $objModel The model object
	 * @param string $strAlias The alias name
	 * @param mixed  $varValue The value of the alias
	 *
	 * @return boolean True if the alias is registered
	 */
	public function isRegisteredAlias(\Model $objModel, $strAlias, $varValue)
	{
		$strTable = $objModel->getTable();

		return isset($this->arrAliases[$strTable][$strAlias][$varValue]);
	}
}
