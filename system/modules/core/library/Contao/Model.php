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
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
abstract class Model
{

	const INSERT = 1;
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
	 * Load the relations and optionally process a result set
	 *
	 * @param \Database\Result $objResult An optional database result
	 */
	public function __construct(\Database\Result $objResult=null)
	{
		$this->arrModified = array();

		$objRelations = new \DcaExtractor(static::$strTable);
		$this->arrRelations = $objRelations->getRelations();

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
				$strClass = static::getClassFromTable($table);

				// If the primary key is empty, set null (see #5356)
				if (!isset($row[$strClass::getPk()]))
				{
					$this->arrRelated[$key] = null;
				}
				else
				{
					/** @var \Model $objRelated */
					$objRelated = \Model\Registry::getInstance()->fetch($table, $row[$strClass::getPk()]);

					if (!$objRelated)
					{
						$objRelated = new $strClass();
						$objRelated->setRow($row);
					}
					else
					{
						$objRelated->safeMerge($row);
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
		unset($this->arrData[static::$strPk]);
		$this->arrModified = array();
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

		// Store the original value
		if (!isset($this->arrModified[$strKey]))
		{
			$this->arrModified[$strKey] = $this->arrData[$strKey];
		}

		$this->arrData[$strKey] = $varValue;
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
	 * Set the current record from an array
	 *
	 * @param array $arrData The data record
	 *
	 * @return \Model The model object
	 */
	public function setRow(array $arrData)
	{
		foreach ($arrData as $field => $value)
		{
			$this->$field = $value;
		}

		return $this;
	}


	/**
	 * Set the current record from an array preserving modified but unsaved fields
	 *
	 * @param array $arrData The data record
	 *
	 * @return \Model The model object
	 */
	public function safeMerge(array $arrData)
	{
		foreach ($arrData as $field => $value)
		{
			if (!isset($this->arrModified[$field]) && $this->arrData[$field] != $value)
			{
				$this->$field = $value;
			}
		}
		return $this;
	}


	/**
	 * Return the object instance
	 *
	 * @return \Model The model object
	 */
	public function current()
	{
		return $this;
	}


	/**
	 * Save the current record
	 *
	 * @return \Model The model object
	 *
	 * @throws \InvalidArgumentException If an argument is passed
	 */
	public function save()
	{
		if (count(func_get_args()))
		{
			trigger_error('Model::save($blnForceInsert) is impossible since Contao 3.2, see the upgrade notice in system/docs/UPGRADE.md for details.', E_USER_DEPRECATED);
			throw new \InvalidArgumentException('Model::save() does not accept an argument anymore.');
		}

		if (\Model\Registry::getInstance()->isRegistered($this))
		{
			$arrRow = $this->row();
			$arrSet = array();
			foreach ($this->arrModified as $strField => $varValue)
			{
				$arrSet[$strField] = $arrRow[$strField];
			}
			$arrSet = $this->preSave($arrSet);

			// track primary key changes
			if (isset($this->arrModified[static::$strPk]))
			{
				$strPk = $this->arrModified[static::$strPk];
			}
			else {
				$strPk = $this->{static::$strPk};
			}

			\Database::getInstance()->prepare("UPDATE " . static::$strTable . " %s WHERE " . static::$strPk . "=?")
									->set($arrSet)
									->execute($strPk);

			$this->arrModified = array();

			$this->postSave(self::UPDATE);
		}
		else
		{
			$arrSet = $this->preSave($this->row());

			$stmt = \Database::getInstance()->prepare("INSERT INTO " . static::$strTable . " %s")
											->set($arrSet)
											->execute();

			if (static::$strPk == 'id')
			{
				$this->id = $stmt->insertId;
			}

			$this->arrModified = array();

			\Model\Registry::getInstance()->register($this);

			$this->postSave(self::INSERT);
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
			// Reload the model data (might have been modified by default values or triggers)
			$this->refresh();
		}
	}


	/**
	 * Delete the current record and return the number of affected rows
	 *
	 * @return integer The number of affected rows
	 */
	public function delete()
	{
		if (isset($this->arrModified[static::$strPk]))
		{
			$strPk = $this->arrModified[static::$strPk];
		}
		else {
			$strPk = $this->{static::$strPk};
		}

		$intAffected = \Database::getInstance()->prepare("DELETE FROM " . static::$strTable . " WHERE " . static::$strPk . "=?")
											   ->execute($strPk)
											   ->affectedRows;

		if ($intAffected)
		{
			// unregister this model from the registry
			\Model\Registry::getInstance()->unregister($this);

			// remove the primary key, it is invalid now
			$this->arrData[static::$strPk] = null; // see #6162
		}

		return $intAffected;
	}


	/**
	 * Lazy load related records
	 *
	 * @param string $strKey     The property name
	 * @param array  $arrOptions An optional options array
	 *
	 * @return \Model|\Model\Collection The model or a model collection if there are multiple rows
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

			$arrOptions = array_merge
			(
				array
				(
					'order' => \Database::getInstance()->findInSet($strField, $arrValues)
				),

				$arrOptions
			);

			$objModel = $strClass::findBy(array($strField . " IN('" . implode("','", $arrValues) . "')"), null, $arrOptions);
			$this->arrRelated[$strKey] = $objModel;
		}

		return $this->arrRelated[$strKey];
	}


	/**
	 * Reload all data from the database, this will discard all modifications.
	 */
	public function refresh()
	{
		// Note: do not check $this->arrModified here to make possible to refresh after low level updated!

		if (isset($this->arrModified[static::$strPk]))
		{
			$strPk = $this->arrModified[static::$strPk];
		}
		else {
			$strPk = $this->{static::$strPk};
		}

		$res = \Database::getInstance()->prepare("SELECT * FROM " . static::$strTable . " WHERE " . static::$strPk . "=?")
									   ->execute($strPk);

		$this->setRow($res->row());
	}


	/**
	 * Free the model from the registry and release references.
	 * Freeing the model may useful as alternative to clone the model in mass imports.
	 */
	public function free()
	{
		\Model\Registry::getInstance()->unregister($this);
	}


	/**
	 * Find a single record by its primary key
	 *
	 * @param mixed $varValue   The property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if the result is empty
	 */
	public static function findByPk($varValue, array $arrOptions=array())
	{
		$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varValue);

		if ($objModel)
		{
			return $objModel;
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
	 * @return \Model|null The model or null if the result is empty
	 */
	public static function findByIdOrAlias($varId, array $arrOptions=array())
	{
		if (is_numeric($varId)) {
			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varId);

			if ($objModel)
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
	 * @return \Model\Collection|null A collection of models or null if there are no records
	 */
	public static function findMultipleByIds($arrIds, array $arrOptions=array())
	{
		if (empty($arrIds) || !is_array($arrIds))
		{
			return null;
		}

		$arrRegisteredModels = array();
		$arrMissingModelIds  = array();

		// search for already registered models
		foreach ($arrIds as $varId)
		{
			$arrRegisteredModels[$varId] = \Model\Registry::getInstance()->fetch(static::$strTable, $varId);

			if (!$arrRegisteredModels[$varId])
			{
				$arrMissingModelIds[] = $varId;
			}
		}

		// fetch only missing models from database
		if (count($arrMissingModelIds))
		{
			$t = static::$strTable;

			$arrOptions = array_merge
			(
				array
				(
					'column' => array("$t.id IN(" . implode(',', array_map('intval', $arrMissingModelIds)) . ")"),
					'value'  => null,
					'order'  => \Database::getInstance()->findInSet("$t.id", $arrIds),
					'return' => 'Collection'
				),

				$arrOptions
			);

			$arrMissingModels = static::find($arrOptions);

			foreach ($arrMissingModels as $objMissingModel)
			{
				$varId = $objMissingModel->{static::$strPk};
				$arrRegisteredModels[$varId] = $objMissingModel;
			}
		}

		return new \Model\Collection(array_filter(array_values($arrRegisteredModels)), static::$strTable);
	}


	/**
	 * Find a single record by various criteria
	 *
	 * @param mixed $strColumn  The property name
	 * @param mixed $varValue   The property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if the result is empty
	 */
	public static function findOneBy($strColumn, $varValue, array $arrOptions=array())
	{
		if ($strColumn == static::$strPk || is_array($strColumn) && count($strColumn) == 1 && $strColumn[0] == static::$strPk)
		{
			$varId = is_array($varValue) ? $varValue[0] : $varValue;

			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varId);

			if ($objModel)
			{
				return $objModel;
			}
		}

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
	 * @return \Model\Collection|null The model collection or null if the result is empty
	 */
	public static function findBy($strColumn, $varValue, array $arrOptions=array())
	{
		$arrOptions = array_merge
		(
			array
			(
				'column' => $strColumn,
				'value'  => $varValue,
				'return' => 'Collection'
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
	 * @return \Model|\Model\Collection A model or model collection
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
	 * @return \Model|\Model\Collection|null A model, model collection or null if the result is empty
	 */
	protected static function find(array $arrOptions)
	{
		if (static::$strTable == '')
		{
			return null;
		}

		$arrOptions['table'] = static::$strTable;
		$strQuery = \Model\QueryBuilder::find($arrOptions);

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

		if ($arrOptions['return'] == 'Model')
		{
			$strPkName = static::getPk();
			$varPk = $objResult->$strPkName;

			$objModel = \Model\Registry::getInstance()->fetch(static::$strTable, $varPk);

			if ($objModel)
			{
				$objModel->safeMerge($objResult->row());
				return $objModel;
			}

			return new static($objResult);
		}
		else
		{
			return \Model\Collection::createFromDbResult($objResult, static::$strTable);
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
	 * @param mixed $strColumn An optional property name
	 * @param mixed $varValue  An optional property value
	 *
	 * @return integer The number of matching rows
	 */
	public static function countBy($strColumn=null, $varValue=null)
	{
		if (static::$strTable == '')
		{
			return 0;
		}

		$strQuery = \Model\QueryBuilder::count(array
		(
			'table'  => static::$strTable,
			'column' => $strColumn,
			'value'  => $varValue
		));

		return (int) \Database::getInstance()->prepare($strQuery)->execute($varValue)->count;
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
		if (isset($GLOBALS['TL_MODELS'][$strTable]))
		{
			return $GLOBALS['TL_MODELS'][$strTable]; // see 4796
		}
		else
		{
			$arrChunks = explode('_', $strTable);

			if ($arrChunks[0] == 'tl')
			{
				array_shift($arrChunks);
			}

			return implode('', array_map('ucfirst', $arrChunks)) . 'Model';
		}
	}
}
