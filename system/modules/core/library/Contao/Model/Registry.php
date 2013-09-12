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
 * Handles a set models
 *
 * The class handles traversing a set of models and lazy loads the database
 * result rows upon their first usage.
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
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
	 * Registered models, mapped by type and pk.
	 * @var array
	 */
	protected $arrRegistry;

	/**
	 * Collection of managed models.
	 * @var
	 */
	protected $arrIdentities;

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
	 * Fetch a registered model by class name and primary key from this registry.
	 *
	 * @param $strTable  The model table name.
	 * @param $varPrimaryKey The model primary key.
	 *
	 * @return null|\Model An registered model or null if no model is registered for this primary key.
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
	 * Register a model in this registry.
	 *
	 * @param \Model $objModel
	 */
	public function register(\Model $objModel)
	{
		$intObjectId = spl_object_hash($objModel);

		// is the model already registered?
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
			throw new \RuntimeException('This registry already contains an instance for ' . $strTable . '::' . $strPkName . '(' . $varPk . ')');
		}

		$this->arrIdentities[$intObjectId] = $objModel;
		$this->arrRegistry[$strTable][$varPk] = $objModel;
	}

	/**
	 * Unregister a model from this registry.
	 *
	 * @param \Model $objModel
	 */
	public function unregister(\Model $objModel)
	{
		$intObjectId = spl_object_hash($objModel);

		// is the model registered?
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
}
