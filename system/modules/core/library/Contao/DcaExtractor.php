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

namespace Contao;


/**
 * Extracts DCA information and cache it
 * 
 * The class parses the DCA files and stores various extracts like relations in
 * the system/cache directory. This meta data can then be loaded and used in the
 * application (e.g. the Model classes).
 * 
 * Usage:
 * 
 *     $user = new DcaExtractor('tl_user');
 * 
 *     if ($user->hasRelations())
 *     {
 *         print_r($user->getRelations());
 *     }
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class DcaExtractor extends \Database\Installer
{

	/**
	 * Table name
	 * @var string
	 */
	protected $strTable;

	/**
	 * Cache file
	 * @var string
	 */
	protected $strFile;

	/**
	 * Meta data
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
	 * Database table
	 * @var boolean
	 */
	protected $blnIsDbTable = false;


	/**
	 * Load or create the extract
	 * 
	 * @param string $strTable The table name
	 * 
	 * @throws \Exception If $strTable is empty
	 */
	public function __construct($strTable)
	{
		if ($strTable == '')
		{
			throw new \Exception('The table name must not be empty');
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
			$this->blnIsDbTable = true;
		}
	}


	/**
	 * Return the meta data as array
	 * 
	 * @return array The meta data
	 */
	public function getMeta()
	{
		return $this->arrMeta;
	}


	/**
	 * Return true if there is meta data
	 * 
	 * @return boolean True if there is meta data
	 */
	public function hasMeta()
	{
		return !empty($this->arrMeta);
	}


	/**
	 * Return the fields as array
	 * 
	 * @return array The fields array
	 */
	public function getFields()
	{
		return $this->arrFields;
	}


	/**
	 * Return true if there are fields
	 * 
	 * @return boolean True if there are fields
	 */
	public function hasFields()
	{
		return !empty($this->arrFields);
	}


	/**
	 * Return the keys as array
	 * 
	 * @return array The keys array
	 */
	public function getKeys()
	{
		return $this->arrKeys;
	}


	/**
	 * Return true if there are keys
	 * 
	 * @return boolean True if there are keys
	 */
	public function hasKeys()
	{
		return !empty($this->arrKeys);
	}


	/**
	 * Return the relations as array
	 * 
	 * @return array The relations array
	 */
	public function getRelations()
	{
		return $this->arrRelations;
	}


	/**
	 * Return true if there are relations
	 * 
	 * @return boolean True if there are relations
	 */
	public function hasRelations()
	{
		return !empty($this->arrRelations);
	}


	/**
	 * Return true if the extract relates to a database table
	 * 
	 * @return boolean True if the extract relates to a database table
	 */
	public function isDbTable()
	{
		return $this->blnIsDbTable;
	}


	/**
	 * Return an array that can be used by the database installer
	 * 
	 * @return array The data array
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

		// Check whether there are fields (see #4826)
		if (isset($GLOBALS['TL_DCA'][$this->strTable]['fields']))
		{
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
		}

		$sql = $GLOBALS['TL_DCA'][$this->strTable]['config']['sql'] ?: array();
		$fields = $GLOBALS['TL_DCA'][$this->strTable]['fields'] ?: array();

		// Get the SQL information from the database.sql files (backwards compatibility)
		if ($blnFromFile)
		{
			if (!isset(static::$arrSql[$this->strTable]))
			{
				static::$arrSql = $this->getFromFile();
			}

			$arrTable = static::$arrSql[$this->strTable];
			list($engine,, $charset) = explode(' ', trim($arrTable['TABLE_OPTIONS']));

			if ($engine != '')
			{
				$sql['engine'] = str_replace('ENGINE=', '', $engine);
			}
			if ($charset != '')
			{
				$sql['charset'] = str_replace('CHARSET=', '', $charset);
			}

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

		// Not a database table or no field information
		if (empty($sql) || empty($fields))
		{
			return;
		}

		// Add the default engine and charset if none is given
		if (empty($sql['engine']))
		{
			$sql['engine'] = 'MyISAM';
		}
		if (empty($sql['charset']))
		{
			$sql['charset'] = 'utf8';
		}

		// Create the file
		$objFile = new \File('system/cache/sql/' . $this->strTable . '.php');
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
			if (isset($config['sql']))
			{
				$objFile->append("\t'$field' => \"{$config['sql']}\",");
			}
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
		$this->blnIsDbTable = true;

		// Include the file so the class properties are filled
		include TL_ROOT . '/system/cache/sql/' . $this->strTable . '.php';
	}


	/**
	 * Create all extracts
	 * 
	 * @return array An array of DcaExtractors
	 */
	public static function createAllExtracts()
	{
		$included = array();
		$arrExtracts = array();

		// Only check the active modules (see #4541)
		foreach (\Config::getInstance()->getActiveModules() as $strModule)
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
				$objExtract = new \DcaExtractor($strTable);

				if ($objExtract->isDbTable())
				{
					$arrExtracts[$strTable] = $objExtract;
				}
			}
		}

		return $arrExtracts;
	}
}
