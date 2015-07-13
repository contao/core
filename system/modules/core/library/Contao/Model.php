<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Reads objects from and writes them to to the database
 *
 * The class allows you to find and automatically join database records and to
 * convert the result into objects. It also supports creating new objects and
 * persisting them in the database.
 *
 * Usage:
 *
 *     // Write
 *     $user = new UserModel();
 *     $user->name = 'Leo Feyer';
 *     $user->city = 'Wuppertal';
 *     $user->save();
 *
 *     // Read
 *     $user = UserModel::findByCity('Wuppertal');
 *
 *     while ($user->next())
 *     {
 *         echo $user->name;
 *     }
 *
 * @property integer $id The ID
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Model
{

	/**
	 * Insert flag
	 * @var integer
	 */
	const INSERT = 1;

	/**
	 * Update flag
	 * @var integer
	 */
	const UPDATE = 2;

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable;

	/**
	 * Primary key
	 * @var string
	 */
	protected static $strPk = 'id';

	/**
	 * Class name cache
	 * @var array
	 */
	protected static $arrClassNames = array();

	/**
	 * Data
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Modified keys
	 * @var array
	 */
	protected $arrModified = array();

	/**
	 * Relations
	 * @var array
	 */
	protected $arrRelations = array();

	/**
	 * Related
	 * @var array
	 */
	protected $arrRelated = array();

	/**
	 * Prevent saving
	 * @var boolean
	 */
	protected $blnPreventSaving = false;


	/**
	 * Load the relations and optionally process a result set
	 *
	 * @param \Database\Result $objResult An optional database result
	 */
	public function __construct(\Database\Result $objResult=null)
	{
		$this->arrModified = array();

		$objDca = \DcaExtractor::getInstance(static::$strTable);
		$this->arrRelations = $objDca->getRelations();

		if ($objResult !== null)
		{
			$arrRelated = array();
			$arrData = $objResult->row();

			// Look for joined fields
			foreach ($arrData as $k=>$v)
			{
				if (strpos($k, '__') !== false)
				{
					list($key, $field) = explode('__', $k, 2);

					if (!isset($arrRelated[$key]))
					{
						$arrRelated[$key] = array();
					}

					$arrRelated[$key][$field] = $v;
					unset($arrData[$k]);
				}
			}

			// Create the related models
			foreach ($arrRelated as $key=>$row)
			{
				$table = $this->arrRelations[$key]['table'];

				/** @var static $strClass */
				$strClass = static::getClassFromTable($table);
				$intPk = $strClass::getPk();

				// If the primary key is empty, set null (see #5356)
				if (!isset($row[$intPk]))
				{
					$this->arrRelated[$key] = null;
				}
				else
				{
					$objRelated = \Model\Registry::getInstance()->fetch($table, $row[$intPk]);

					if ($objRelated !== null)
					{
						$objRelated->mergeRow($row);
					}
					else
					{
						/** @var static $objRelated */
						$objRelated = new $strClass();
						$objRelated->setRow($row);
					}

					$this->arrRelated[$key] = $objRelated;
				}
			}

			$this->setRow($arrData); // see #5439
			\Model\Registry::getInstance()->register($this);
		}
	}


	/**
	 * Unset the primary key when cloning an object
	 */
	public function __clone()
	{
		$this->arrModified = array();
		$this->blnPreventSaving = false;

		unset($this->arrData[static::$strPk]);
	}


	/**
	 * Clone a model with its original values
	 *
	 * @return static The model
	 */
	public function cloneOriginal()
	{
		$clone = clone $this;
		$clone->setRow($this->originalRow());

		return $clone;
	}


	/**
	 * Set an object property
	 *
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
	 */
	public function __set($strKey, $varValue)
	{
		if ($this->$strKey === $varValue)
		{
			return;
		}

		$this->markModified($strKey);
		$this->arrData[$strKey] = $varValue;

		unset($this->arrRelated[$strKey]);
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property key
	 *
	 * @return mixed|null The property value or null
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return null;
	}


	/**
	 * Check whether a property is set
	 *
	 * @param string $strKey The property key
	 *
	 * @return boolean True if the property is set
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Return the name of the primary key
	 *
	 * @return string The primary key
	 */
	public static function getPk()
	{
		return static::$strPk;
	}


	/**
	 * Return an array of unique field/column names (without the PK)
	 *
	 * @return array
	 */
	public static function getUniqueFields()
	{
		$objDca = \DcaExtractor::getInstance(static::getTable());

		return $objDca->getUniqueFields();
	}


	/**
	 * Return the name of the related table
	 *
	 * @return string The table name
	 */
	public static function getTable()
	{
		return static::$strTable;
	}


	/**
	 * Return the current record as associative array
	 *
	 * @return array The data record
	 */
	public function row()
	{
		return $this->arrData;
	}


	/**
	 * Return the original values as associative array
	 *
	 * @return array The original data
	 */
	public function originalRow()
	{
		$row = $this->row();

		if (!$this->isModified())
		{
			return $row;
		}

		$originalRow = array();

		foreach ($row as $k=>$v)
		{
			$originalRow[$k] = isset($this->arrModified[$k]) ? $this->arrModified[$k] : $v;
		}

		return $originalRow;
	}


	/**
	 * Return true if the model has been modified
	 *
	 * @return boolean True if the model has been modified
	 */
	public function isModified()
	{
		return !empty($this->arrModified);
	}


	/**
	 * Set the current record from an array
	 *
	 * @param array $arrData The data record
	 *
	 * @return static The model object
	 */
	public function setRow(array $arrData)
	{
		foreach ($arrData as $k=>$v)
		{
			if (strpos($k, '__') !== false)
			{
				unset($arrData[$k]);
			}
		}

		$this->arrData = $arrData;

		return $this;
	}


	/**
	 * Set the current record from an array preserving modified but unsaved fields
	 *
	 * @param array $arrData The data record
	 *
	 * @return static The model object
	 */
	public function mergeRow(array $arrData)
	{
		foreach ($arrData as $k=>$v)
		{
			if (strpos($k, '__') !== false)
			{
				continue;
			}

			if (!isset($this->arrModified[$k]))
			{
				$this->arrData[$k] = $v;
			}
		}

		return $this;
	}


	/**
	 * Mark a field as modified
	 *
	 * @param string $strKey The field key
	 */
	public function markModified($strKey)
	{
		if (!isset($this->arrModified[$strKey]))
		{
			$this->arrModified[$strKey] = $this->arrData[$strKey];
		}
	}


	/**
	 * Return the object instance
	 *
	 * @return static The model object
	 */
	public function current()
	{
		return $this;
	}


	/**
	 * Save the current record
	 *
	 * @return static The model object
	 *
	 * @throws \InvalidArgumentException If an argument is passed
	 * @throws \LogicException           If the model cannot be saved
	 */
	public function save()
	{
		// Deprecated call
		if (count(func_get_args()))
		{
			throw new \InvalidArgumentException('The $blnForceInsert argument has been removed (see system/docs/UPGRADE.md)');
		}

		// The instance cannot be saved
		if ($this->blnPreventSaving)
		{
			throw new \LogicException('The model instance has been detached and cannot be saved');
		}

		$objDatabase = \Database::getInstance();
		$arrFields = $objDatabase->getFieldNames(static::$strTable);

		// The model is in the registry
		if (\Model\Registry::getInstance()->isRegistered($this))
		{
			$arrSet = array();
			$arrRow = $this->row();

			// Only update modified fields
			foreach ($this->arrModified as $k=>$v)
			{
				// Only set fields that exist in the DB
				if (in_array($k, $arrFields))
				{
					$arrSet[$k] = $arrRow[$k];
				}
			}

			$arrSet = $this->preSave($arrSet);

			// No modified fiels
			if (empty($arrSet))
			{
				return $this;
			}

			$intPk = $this->{static::$strPk};

			// Track primary key changes
			if (isset($this->arrModified[static::$strPk]))
			{
				$intPk = $this->arrModified[static::$strPk];
			}

			// Update the row
			$objDatabase->prepare("UPDATE " . static::$strTable . " %s WHERE " . static::$strPk . "=?")
						->set($arrSet)
						->execute($intPk);

			$this->postSave(self::UPDATE);
			$this->arrModified = array(); // reset after postSave()
		}

		// The model is not yet in the registry
		else
		{
			$arrSet = $this->row();

			// Remove fields that do not exist in the DB
			foreach ($arrSet as $k=>$v)
			{
				if (!in_array($k, $arrFields))
				{
					unset($arrSet[$k]);
				}
			}

			$arrSet = $this->preSave($arrSet);

			// No modified fiels
			if (empty($arrSet))
			{
				return $this;
			}

			// Insert a new row
			$stmt = $objDatabase->prepare("INSERT INTO " . static::$strTable . " %s")
								->set($arrSet)
								->execute();

			if (static::$strPk == 'id')
			{
				$this->id = $stmt->insertId;
			}

			$this->postSave(self::INSERT);
			$this->arrModified = array(); // reset after postSave()

			\Model\Registry::getInstance()->register($this);
		}

		return $this;
	}


	/**
	 * Modify the current row before it is stored in the database
	 *
	 * @param array $arrSet The data array
	 *
	 * @return array The modified data array
	 */
	protected function preSave(array $arrSet)
	{
		return $arrSet;
	}


	/**
	 * Modify the current row after it has been stored in the database
	 *
	 * @param integer $intType The query type (Model::INSERT or Model::UPDATE)
	 */
	protected function postSave($intType)
	{
		if ($intType == self::INSERT)
		{
			$this->refresh(); // might have been modified by default values or triggers
		}
	}


	/**
	 * Delete the current record and return the number of affected rows
	 *
	 * @return integer The number of affected rows
	 */
	public function delete()
	{
		$intPk = $this->{static::$strPk};

		// Track primary key changes
		if (isset($this->arrModified[static::$strPk]))
		{
			$intPk = $this->arrModified[static::$strPk];
		}

		// Delete the row
		$intAffected = \Database::getInstance()->prepare("DELETE FROM " . static::$strTable . " WHERE " . static::$strPk . "=?")
											   ->execute($intPk)
											   ->affectedRows;

		if ($intAffected)
		{
			// Unregister the model
			\Model\Registry::getInstance()->unregister($this);

			// Remove the primary key (see #6162)
			$this->arrData[static::$strPk] = null;
		}

		return $intAffected;
	}


	/**
	 * Lazy load related records
	 *
	 * @param string $strKey     The property name
	 * @param array  $arrOptions An optional options array
	 *
	 * @return static|\Model\Collection|null The model or a model collection if there are multiple rows
	 *
	 * @throws \Exception If $strKey is not a related field
	 */
	public function getRelated($strKey, array $arrOptions=array())
	{
		// The related model has been loaded before
		if (array_key_exists($strKey, $this->arrRelated))
		{
			return $this->arrRelated[$strKey];
		}

		// The relation does not exist
		if (!isset($this->arrRelations[$strKey]))
		{
			throw new \Exception("Field $strKey does not seem to be related");
		}

		// The relation exists but there is no reference yet (see #6161)
		if (!isset($this->$strKey))
		{
			return null;
		}

		$arrRelation = $this->arrRelations[$strKey];

		/** @var static $strClass */
		$strClass = static::getClassFromTable($arrRelation['table']);

		// Load the related record(s)
		if ($arrRelation['type'] == 'hasOne' || $arrRelation['type'] == 'belongsTo')
		{
			$objModel = $strClass::findOneBy($arrRelation['field'], $this->$strKey, $arrOptions);
			$this->arrRelated[$strKey] = $objModel;
		}
		elseif ($arrRelation['type'] == 'hasMany' || $arrRelation['type'] == 'belongsToMany')
		{
			$arrValues = deserialize($this->$strKey, true);
			$strField = $arrRelation['table'] . '.' . $arrRelation['field'];

			// Handle UUIDs (see #6525)
			if ($strField == 'tl_files.uuid')
			{
				/** @var \FilesModel $strClass */
				$objModel = $strClass::findMultipleByUuids($arrValues, $arrOptions);
			}
			else
			{
				$arrOptions = array_merge
				(
					array
					(
						'order' => \Database::getInstance()->findInSet($strField, $arrValues)
					),

					$arrOptions
				);

				$objModel = $strClass::findBy(array($strField . " IN('" . implode("','", $arrValues) . "')"), null, $arrOptions);
			}

			$this->arrRelated[$strKey] = $objModel;
		}

		return $this->arrRelated[$strKey];
	}


	/**
	 * Reload the data from the database discarding all modifications
	 */
	public function refresh()
	{
		$intPk = $this->{static::$strPk};

		// Track primary key changes
		if (isset($this->arrModified[static::$strPk]))
		{
			$intPk = $this->arrModified[static::$strPk];
		}

		// Reload the database record
		$res = \Database::getInstance()->prepare("SELECT * FROM " . static::$strTable . " WHERE " . static::$strPk . "=?")
									   ->execute($intPk);

		$this->setRow($res->row());
	}


	/**
	 * Detach the model from the registry
	 *
	 * @param boolean $blnKeepClone Keeps a clone of the model in the registry
	 */
	public function detach($blnKeepClone=true)
	{
		\Model\Registry::getInstance()->unregister($this);

		if ($blnKeepClone)
		{
			$this->cloneOriginal()->attach();
		}
	}


	/**
	 * Attach the model to the registry
	 */
	public function attach()
	{
		\Model\Registry::getInstance()->register($this);
	}


	/**
	 * Called when the model is attached to the model registry
	 *
	 * @param \Model\Registry
	 */
	public function onRegister(\Model\Registry $registry)
	{
		// Register aliases to unique fields
		foreach (static::getUniqueFields() as $strColumn)
		{
			$varAliasValue = $this->{$strColumn};

			if (!$registry->isRegisteredAlias($this, $strColumn, $varAliasValue))
			{
				$registry->registerAlias($this, $strColumn, $varAliasValue);
			}
		}
	}


	/**
	 * Called when the model is detached from the model registry
	 *
	 * @param \Model\Registry
	 */
	public function onUnregister(\Model\Registry $registry)
	{
		// Unregister aliases to unique fields
		foreach (static::getUniqueFields() as $strColumn)
		{
			$varAliasValue = $this->{$strColumn};

			if ($registry->isRegisteredAlias($this, $strColumn, $varAliasValue))
			{
				$registry->unregisterAlias($this, $strColumn, $varAliasValue);
			}
		}
	}


	/**
	 * Prevent saving the model
	 *
	 * @param boolean $blnKeepClone Keeps a clone of the model in the registry
	 */
	public function preventSaving($blnKeepClone=true)
	{
		$this->detach($blnKeepClone);
		$this->blnPreventSaving = true;
	}


	/**
	 * Find a single record by its primary key
	 *
	 * @param mixed $varValue   The property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return static The model or null if the result is empty
	 */
	public static function findByPk($varValue, array $arrOptions=array())
	{
		// Try to load from the registry
		if (empty($arrOptions))
		{
			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varValue);

			if ($objModel !== null)
			{
				return $objModel;
			}
		}

		$arrOptions = array_merge
		(
			array
			(
				'limit'  => 1,
				'column' => static::$strPk,
				'value'  => $varValue,
				'return' => 'Model'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}


	/**
	 * Find a single record by its ID or alias
	 *
	 * @param mixed $varId      The ID or alias
	 * @param array $arrOptions An optional options array
	 *
	 * @return static The model or null if the result is empty
	 */
	public static function findByIdOrAlias($varId, array $arrOptions=array())
	{
		// Try to load from the registry
		if (is_numeric($varId) && empty($arrOptions))
		{
			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varId);

			if ($objModel !== null)
			{
				return $objModel;
			}
		}

		$t = static::$strTable;

		$arrOptions = array_merge
		(
			array
			(
				'limit'  => 1,
				'column' => array("($t.id=? OR $t.alias=?)"),
				'value'  => array((is_numeric($varId) ? $varId : 0), $varId),
				'return' => 'Model'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}


	/**
	 * Find multiple records by their IDs
	 *
	 * @param array $arrIds     An array of IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null The model collection or null if there are no records
	 */
	public static function findMultipleByIds($arrIds, array $arrOptions=array())
	{
		if (empty($arrIds) || !is_array($arrIds))
		{
			return null;
		}

		$arrRegistered = array();
		$arrUnregistered = array();

		// Search for registered models
		foreach ($arrIds as $intId)
		{
			$arrRegistered[$intId] = null;

			if (empty($arrOptions))
			{
				$arrRegistered[$intId] = \Model\Registry::getInstance()->fetch(static::$strTable, $intId);
			}

			if ($arrRegistered[$intId] === null)
			{
				$arrUnregistered[] = $intId;
			}
		}

		// Fetch only the missing models from the database
		if (!empty($arrUnregistered))
		{
			$t = static::$strTable;

			$arrOptions = array_merge
			(
				array
				(
					'column' => array("$t.id IN(" . implode(',', array_map('intval', $arrUnregistered)) . ")"),
					'value'  => null,
					'order'  => \Database::getInstance()->findInSet("$t.id", $arrIds),
					'return' => 'Collection'
				),

				$arrOptions
			);

			$objMissing = static::find($arrOptions);

			if ($objMissing !== null)
			{
				while ($objMissing->next())
				{
					$intId = $objMissing->{static::$strPk};
					$arrRegistered[$intId] = $objMissing->current();
				}
			}
		}

		return static::createCollection(array_filter(array_values($arrRegistered)), static::$strTable);
	}


	/**
	 * Find a single record by various criteria
	 *
	 * @param mixed $strColumn  The property name
	 * @param mixed $varValue   The property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return static The model or null if the result is empty
	 */
	public static function findOneBy($strColumn, $varValue, array $arrOptions=array())
	{
		$arrOptions = array_merge
		(
			array
			(
				'limit'  => 1,
				'column' => $strColumn,
				'value'  => $varValue,
				'return' => 'Model'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}


	/**
	 * Find records by various criteria
	 *
	 * @param mixed $strColumn  The property name
	 * @param mixed $varValue   The property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return static|\Model\Collection|null A model, model collection or null if the result is empty
	 */
	public static function findBy($strColumn, $varValue, array $arrOptions=array())
	{
		$blnModel = false;
		$arrColumn = (array) $strColumn;

		if (count($arrColumn) == 1 && ($arrColumn[0] === static::getPk() || in_array($arrColumn[0], static::getUniqueFields())))
		{
			$blnModel = true;
		}

		$arrOptions = array_merge
		(
			array
			(
				'column' => $strColumn,
				'value'  => $varValue,
				'return' => $blnModel ? 'Model' : 'Collection'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}


	/**
	 * Find all records
	 *
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null The model collection or null if the result is empty
	 */
	public static function findAll(array $arrOptions=array())
	{
		$arrOptions = array_merge
		(
			array
			(
				'return' => 'Collection'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}


	/**
	 * Magic method to map Model::findByName() to Model::findBy('name')
	 *
	 * @param string $name The method name
	 * @param array  $args The passed arguments
	 *
	 * @return static|\Model\Collection|null A model or model collection
	 *
	 * @throws \Exception If the method name is invalid
	 */
	public static function __callStatic($name, $args)
	{
		if (strncmp($name, 'findBy', 6) === 0)
		{
			array_unshift($args, lcfirst(substr($name, 6)));

			return call_user_func_array('static::findBy', $args);
		}
		elseif (strncmp($name, 'findOneBy', 9) === 0)
		{
			array_unshift($args, lcfirst(substr($name, 9)));

			return call_user_func_array('static::findOneBy', $args);
		}
		elseif (strncmp($name, 'countBy', 7) === 0)
		{
			array_unshift($args, lcfirst(substr($name, 7)));

			return call_user_func_array('static::countBy', $args);
		}

		throw new \Exception("Unknown method $name");
	}


	/**
	 * Find records and return the model or model collection
	 *
	 * Supported options:
	 *
	 * * column: the field name
	 * * value:  the field value
	 * * limit:  the maximum number of rows
	 * * offset: the number of rows to skip
	 * * order:  the sorting order
	 * * eager:  load all related records eagerly
	 *
	 * @param array $arrOptions The options array
	 *
	 * @return static|\Model\Collection|null A model, model collection or null if the result is empty
	 */
	protected static function find(array $arrOptions)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		// Try to load from the registry
		if ($arrOptions['return'] == 'Model')
		{
			$arrColumn = (array) $arrOptions['column'];

			if (count($arrColumn) == 1 && ($arrColumn[0] == static::$strPk || in_array($arrColumn[0], static::getUniqueFields())))
			{
				$varKey = is_array($arrOptions['value']) ? $arrOptions['value'][0] : $arrOptions['value'];
				$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varKey, $arrColumn[0]);

				if ($objModel !== null)
				{
					return $objModel;
				}
			}
		}

		$arrOptions['table'] = static::$strTable;
		$strQuery = static::buildFindQuery($arrOptions);

		$objStatement = \Database::getInstance()->prepare($strQuery);

		// Defaults for limit and offset
		if (!isset($arrOptions['limit']))
		{
			$arrOptions['limit'] = 0;
		}
		if (!isset($arrOptions['offset']))
		{
			$arrOptions['offset'] = 0;
		}

		// Limit
		if ($arrOptions['limit'] > 0 || $arrOptions['offset'] > 0)
		{
			$objStatement->limit($arrOptions['limit'], $arrOptions['offset']);
		}

		$objStatement = static::preFind($objStatement);
		$objResult = $objStatement->execute($arrOptions['value']);

		if ($objResult->numRows < 1)
		{
			return null;
		}

		$objResult = static::postFind($objResult);

		// Try to load from the registry
		if ($arrOptions['return'] == 'Model')
		{
			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $objResult->{static::$strPk});

			if ($objModel !== null)
			{
				return $objModel->mergeRow($objResult->row());
			}

			return static::createModelFromDbResult($objResult);
		}
		else
		{
			return static::createCollectionFromDbResult($objResult, static::$strTable);
		}
	}


	/**
	 * Modify the database statement before it is executed
	 *
	 * @param \Database\Statement $objStatement The database statement object
	 *
	 * @return \Database\Statement The database statement object
	 */
	protected static function preFind(\Database\Statement $objStatement)
	{
		return $objStatement;
	}


	/**
	 * Modify the database result before the model is created
	 *
	 * @param \Database\Result $objResult The database result object
	 *
	 * @return \Database\Result The database result object
	 */
	protected static function postFind(\Database\Result $objResult)
	{
		return $objResult;
	}


	/**
	 * Return the number of records matching certain criteria
	 *
	 * @param mixed $strColumn  An optional property name
	 * @param mixed $varValue   An optional property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return integer The number of matching rows
	 */
	public static function countBy($strColumn=null, $varValue=null, array $arrOptions=array())
	{
		if (static::$strTable == '')
		{
			return 0;
		}

		$arrOptions = array_merge
		(
			array
			(
				'table'  => static::$strTable,
				'column' => $strColumn,
				'value'  => $varValue
			),

			$arrOptions
		);

		$strQuery = static::buildCountQuery($arrOptions);

		return (int) \Database::getInstance()->prepare($strQuery)->execute($arrOptions['value'])->count;
	}


	/**
	 * Return the total number of rows
	 *
	 * @return integer The total number of rows
	 */
	public static function countAll()
	{
		return static::countBy();
	}


	/**
	 * Compile a Model class name from a table name (e.g. tl_form_field becomes FormFieldModel)
	 *
	 * @param string $strTable The table name
	 *
	 * @return string The model class name
	 */
	public static function getClassFromTable($strTable)
	{
		if (isset(static::$arrClassNames[$strTable]))
		{
			return static::$arrClassNames[$strTable];
		}

		if (isset($GLOBALS['TL_MODELS'][$strTable]))
		{
			static::$arrClassNames[$strTable] = $GLOBALS['TL_MODELS'][$strTable]; // see 4796

			return static::$arrClassNames[$strTable];
		}
		else
		{
			$arrChunks = explode('_', $strTable);

			if ($arrChunks[0] == 'tl')
			{
				array_shift($arrChunks);
			}

			static::$arrClassNames[$strTable] = implode('', array_map('ucfirst', $arrChunks)) . 'Model';

			return static::$arrClassNames[$strTable];
		}
	}


	/**
	 * Build a query based on the given options
	 *
	 * @param array $arrOptions The options array
	 *
	 * @return string The query string
	 */
	protected static function buildFindQuery(array $arrOptions)
	{
		return \Model\QueryBuilder::find($arrOptions);
	}


	/**
	 * Build a query based on the given options to count the number of records
	 *
	 * @param array $arrOptions The options array
	 *
	 * @return string The query string
	 */
	protected static function buildCountQuery(array $arrOptions)
	{
		return \Model\QueryBuilder::count($arrOptions);
	}


	/**
	 * Create a model from a database result
	 *
	 * @param \Database\Result $objResult The database result object
	 *
	 * @return static The model
	 */
	protected static function createModelFromDbResult(\Database\Result $objResult)
	{
		return new static($objResult);
	}


	/**
	 * Create a Model\Collection object
	 *
	 * @param array  $arrModels An array of models
	 * @param string $strTable  The table name
	 *
	 * @return \Model\Collection The Model\Collection object
	 */
	protected static function createCollection(array $arrModels, $strTable)
	{
		return new \Model\Collection($arrModels, $strTable);
	}


	/**
	 * Create a new collection from a database result
	 *
	 * @param \Database\Result $objResult The database result object
	 * @param string           $strTable  The table name
	 *
	 * @return \Model\Collection The model collection
	 */
	protected static function createCollectionFromDbResult(\Database\Result $objResult, $strTable)
	{
		return \Model\Collection::createFromDbResult($objResult, $strTable);
	}
}
