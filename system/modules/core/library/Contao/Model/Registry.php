<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Model;


/**
 * Handle a set of models
 *
 * The class handles traversing a set of models and lazy loads the database
 * result rows upon their first usage.
 *
 * @package   Library
 * @author    Tristan Lins <tristan.lins@bit3.de>
 * @copyright Leo Feyer 2005-2013
 */
class Registry implements \Countable
{

	/**
	 * Object instance (Singleton)
	 * @var \Registry
	 */
	protected static $objInstance;

	/**
	 * Models by table and PK
	 * @var array
	 */
	protected $arrRegistry;

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
	 * @return \Registry The object instance
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
	 * {@inheritdoc}
	 */
	public function count()
	{
		return count($this->arrIdentities);
	}


	/**
	 * Fetch a model by table name and primary key
	 *
	 * @param string  $strTable The table name
	 * @param integer $intPk    The primary key
	 *
	 * @return \Model|null The model or null
	 */
	public function fetch($strTable, $intPk)
	{
		if (isset($this->arrRegistry[$strTable][$intPk]))
		{
			return $this->arrRegistry[$strTable][$intPk];
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
		}

		$strPk = $objModel->getPk();
		$intPk = $objModel->$strPk;

		// Another model object is pointing to the DB record already
		if (isset($this->arrRegistry[$strTable][$intPk]))
		{
			throw new \RuntimeException("The registry already contains an instance for $strTable::$strPk($intPk)");
		}

		$this->arrIdentities[$intObjectId] = $objModel;
		$this->arrRegistry[$strTable][$intPk] = $objModel;
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
