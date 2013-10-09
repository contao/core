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
	 * The database connection
	 * @var \Database
	 */
	protected $objDatabase;

	/**
	 * Registered models by type and PK
	 * @var array
	 */
	protected $arrRegistry;

	/**
	 * Collection of managed models
	 * @var array
	 */
	protected $arrIdentities;


	/**
	 * Initialize the object
	 *
	 * @param \Database $objDatabase The database object
	 */
	function __construct(\Database $objDatabase)
	{
		$this->objDatabase = $objDatabase;
	}


	/**
	 * {@inheritdoc}
	 */
	public function count()
	{
		return count($this->arrIdentities);
	}


	/**
	 * Fetch a registered model by its class name and primary key
	 *
	 * @param $strTable      The table name
	 * @param $varPrimaryKey The primary key
	 *
	 * @return \Model|null The registered model or null
	 */
	public function fetch($strTable, $varPrimaryKey)
	{
		if (isset($this->arrRegistry[$strTable][$varPrimaryKey]))
		{
			return $this->arrRegistry[$strTable][$varPrimaryKey];
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

		// The model is registered already
		if (isset($this->arrIdentities[$intObjectId]))
		{
			return;
		}

		$strTable = $objModel->getTable();

		if (!is_array($this->arrRegistry[$strTable]))
		{
			$this->arrRegistry[$strTable] = array();
		}

		$strPkName = $objModel->getPk();
		$varPk = $objModel->$strPkName;

		if (isset($this->arrRegistry[$strTable][$varPk]))
		{
			throw new \RuntimeException("The registry already contains an instance for $strTable::$strPkName($varPk)");
		}

		$this->arrIdentities[$intObjectId] = $objModel;
		$this->arrRegistry[$strTable][$varPk] = $objModel;
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
		$strPkName = $objModel->getPk();
		$varPk = $objModel->$strPkName;

		unset($this->arrIdentities[$intObjectId]);
		unset($this->arrRegistry[$strTable][$varPk]);
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
