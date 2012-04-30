<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \DbInstaller, \File, \Exception;


/**
 * Class DcaExtractor
 *
 * Provide methods to extract information from DCA files.
 * @copyright  Leo Feyer 2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class DcaExtractor extends DbInstaller
{

	/**
	 * Table name
	 * @var string
	 */
	protected $strTable;

	/**
	 * Extract file
	 * @var string
	 */
	protected $strFile;

	/**
	 * Meta information
	 * @var array
	 */
	protected $arrMeta = array();

	/**
	 * Fields
	 * @var array
	 */
	protected $arrFields = array();

	/**
	 * Keys
	 * @var array
	 */
	protected $arrKeys = array();

	/**
	 * Relations
	 * @var array
	 */
	protected $arrRelations = array();

	/**
	 * SQL buffer
	 * @var array
	 */
	protected static $arrSql = array();


	/**
	 * Load or create the extract
	 * @param string
	 * @throws \Exception
	 */
	public function __construct($strTable)
	{
		if ($strTable == '')
		{
			throw new Exception('The table name must not be empty');
		}

		parent::__construct();

		$this->strTable = $strTable;
		$this->strFile = TL_ROOT . '/system/cache/sql/' . $strTable . '.php';

		if ($GLOBALS['TL_CONFIG']['bypassCache'] || !file_exists($this->strFile))
		{
			$this->createExtract();
		}
		else
		{
			include	$this->strFile;
		}
	}


	/**
	 * Return the meta array
	 * @return array
	 */
	public function getMeta()
	{
		return $this->arrMeta;
	}


	/**
	 * Return true if there is meta information
	 * @return boolean
	 */
	public function hasMeta()
	{
		return !empty($this->arrMeta);
	}


	/**
	 * Return the fields array
	 * @return array
	 */
	public function getFields()
	{
		return $this->arrFields;
	}


	/**
	 * Return true if there are fields
	 * @return boolean
	 */
	public function hasFields()
	{
		return !empty($this->arrFields);
	}


	/**
	 * Return the keys array
	 * @return array
	 */
	public function getKeys()
	{
		return $this->arrKeys;
	}


	/**
	 * Return true if there are keys
	 * @return boolean
	 */
	public function hasKeys()
	{
		return !empty($this->arrKeys);
	}


	/**
	 * Return the relations array
	 * @return array
	 */
	public function getRelations()
	{
		return $this->arrRelations;
	}


	/**
	 * Return true if there are relations
	 * @return boolean
	 */
	public function hasRelations()
	{
		return !empty($this->arrRelations);
	}


	/**
	 * Return the DbInstaller array
	 * @return array
	 */
	public function getDbInstallerArray()
	{
		$return = array();

		// Fields
		foreach ($this->arrFields as $k=>$v)
		{
			$return['TABLE_FIELDS'][$k] = '`' . $k . '` ' . $v;
		}

		// Keys
		foreach ($this->arrKeys as $k=>$v)
		{
			if ($v == 'primary')
			{
				$v = 'PRIMARY KEY  (`' . $k . '`)';
				$k = 'PRIMARY';
			}
			elseif ($v == 'index')
			{
				$v = 'KEY `' . $k . '` (`' . $k . '`)';
			}
			else
			{
				$v = strtoupper($v) . ' KEY `' . $k . '` (`' . $k . '`)';
			}

			$return['TABLE_CREATE_DEFINITIONS'][$k] = $v;
		}

		$return['TABLE_OPTIONS'] = '';

		// Options
		foreach ($this->arrMeta as $k=>$v)
		{
			if ($k == 'engine')
			{
				$return['TABLE_OPTIONS'] .= ' ENGINE=' . $v;
			}
			elseif ($k == 'charset')
			{
				$return['TABLE_OPTIONS'] .= ' DEFAULT CHARSET=' . $v;
			}
		}

		return $return;
	}


	/**
	 * Create the extract from the DCA or the database.sql files
	 * @return void
	 * @throws \Exception
	 */
	protected function createExtract()
	{
		// Load the DataContainer
		if (!isset($GLOBALS['loadDataContainer'][$this->strTable]))
		{
			$this->loadDataContainer($this->strTable);
		}

		$blnFromFile = false;
		$arrRelations = array();

		foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'] as $field=>$config)
		{
			// Check whether all fields have an SQL definition
			if (!isset($config['sql']) && isset($config['inputType']))
			{
				$blnFromFile = true;
			}

			// Check whether there is a relation
			if (isset($config['foreignKey']) && isset($config['relation']))
			{
				$table = substr($config['foreignKey'], 0, strrpos($config['foreignKey'], '.'));
				$arrRelations[$field] = array_merge(array('table'=>$table, 'field'=>'id'), $config['relation']);
			}
		}

		$sql = $GLOBALS['TL_DCA'][$this->strTable]['config']['sql'];
		$fields = $GLOBALS['TL_DCA'][$this->strTable]['fields'];

		// Add the default engine and charset if none is given
		if (!isset($sql['engine']))
		{
			$sql['engine'] = 'MyISAM';
		}
		if (!isset($sql['charset']))
		{
			$sql['charset'] = 'utf8';
		}

		// Get the SQL information from the database.sql files (backwards compatibility)
		if ($blnFromFile)
		{
			if (!isset(static::$arrSql[$this->strTable]))
			{
				static::$arrSql = $this->getFromFile();
			}

			$arrTable = static::$arrSql[$this->strTable];

			// Meta
			list($engine,, $charset) = explode(' ', trim($arrTable['TABLE_OPTIONS']));
			$sql['engine'] = str_replace('ENGINE=', '', $engine);
			$sql['charset'] = str_replace('CHARSET=', '', $charset);

			// Fields
			if (isset($arrTable['TABLE_FIELDS']))
			{
				foreach ($arrTable['TABLE_FIELDS'] as $k=>$v)
				{
					$fields[$k]['sql'] = str_replace('`' . $k . '` ', '', $v);
				}
			}

			// Keys
			if (isset($arrTable['TABLE_CREATE_DEFINITIONS']))
			{
				foreach ($arrTable['TABLE_CREATE_DEFINITIONS'] as $strKey)
				{
					$strKey = preg_replace('/^([A-Z]+ )?KEY .+\(`([^`]+)`\)$/', '$2 $1', $strKey);
					list($field, $type) = explode(' ', $strKey);
					$sql['keys'][$field] = ($type != '') ? strtolower($type) : 'index';
				}
			}
		}

		// Not all information could be loaded
		if (!is_array($sql) || !is_array($fields))
		{
			throw new Exception('Could not load the table information of ' . $this->strTable);
		}

		// Create the file
		$objFile = new File('system/cache/sql/' . $this->strTable . '.php');
		$objFile->write("<?php\n\n");

		// Meta
		$objFile->append("\$this->arrMeta = array\n(");
		$objFile->append("\t'engine' => '{$sql['engine']}',");
		$objFile->append("\t'charset' => '{$sql['charset']}',");
		$objFile->append(');', "\n\n");

		// Fields
		$objFile->append("\$this->arrFields = array\n(");

		foreach ($fields as $field=>$config)
		{
			if (!isset($config['sql']) && !isset($config['inputType']))
			{
				continue;
			}

			$objFile->append("\t'$field' => \"{$config['sql']}\",");
		}

		$objFile->append(');', "\n\n");

		// Keys
		if (is_array($sql['keys']) && !empty($sql['keys']))
		{
			$objFile->append("\$this->arrKeys = array\n(");

			foreach ($sql['keys'] as $field=>$type)
			{
				$objFile->append("\t'$field' => '$type',");
			}

			$objFile->append(');', "\n\n");
		}

		// Relations
		if (!empty($arrRelations))
		{
			$objFile->append("\$this->arrRelations = array\n(");

			foreach ($arrRelations as $field=>$config)
			{
				$objFile->append("\t'$field' => array\n\t(");

				foreach ($config as $k=>$v)
				{
					$objFile->append("\t\t'$k'=>'$v',");
				}

				$objFile->append("\t),");
			}

			$objFile->append(');', "\n");
		}

		$objFile->close();

		// Include the file so the class properties are filled
		include TL_ROOT . '/system/cache/sql/' . $this->strTable . '.php';
	}


	/**
	 * Create all extracts
	 * @return void
	 */
	public static function createAllExtracts()
	{
		$included = array();

		foreach (scan(TL_ROOT . '/system/modules') as $strModule)
		{
			$strDir = TL_ROOT . '/system/modules/' . $strModule . '/dca';

			if (!is_dir($strDir))
			{
				continue;
			}

			foreach (scan($strDir) as $strFile)
			{
				if (in_array($strFile, $included) || $strFile == '.htaccess')
				{
					continue;
				}

				$included[] = $strFile;
				$strTable = str_replace('.php', '', $strFile);
				new DcaExtractor($strTable);
			}
		}
	}
}
