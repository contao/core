<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
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
 * @copyright Leo Feyer 2011-2012
 */
class DcaExtractor extends \Database_Installer
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
			$this->createModelExtract();
		}
		else
		{
			include	$this->strFile;
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
	 * 
	 * @throws \Exception If the table information could not be loaded
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
			throw new \Exception('Could not load the table information of ' . $this->strTable);
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

			// FIXME: ignore non-table drivers (reflection)
			foreach (scan($strDir) as $strFile)
			{
				if (in_array($strFile, $included) || $strFile == '.htaccess')
				{
					continue;
				}

				$included[] = $strFile;
				$strTable = str_replace('.php', '', $strFile);
				new \DcaExtractor($strTable);
			}
		}
	}


	public function createModelExtract()
	{
		$strModelName = System::getModelClassFromTable($this->strTable);
		$arrClasses = \ClassLoader::getClasses();
		$arrDefinedProperties = array();
		$arrDefinedMethods = array();

		// Create the file
		$objFile = new File('system/cache/models/' . $strModelName . '.php');
		$objFile->write("<?php\nnamespace Contao;\nclass $strModelName extends \Model\n{\n\n");

		// scan in reverse order so that method overriding is possible
		// scan everything for the properties first
		foreach (array_reverse(scan(TL_ROOT . '/system/modules')) as $strModule)
		{
			// get the class name from the ClassLoader so namespaces are considered
			$strClassPath = 'system/modules/' . $strModule . '/models/' . $strModelName . '.php';
			$strClassName = array_search($strClassPath, $arrClasses);

			if (!$strClassName)
			{
				continue;
			}

			$objClassFile = new \File($strClassPath);
			$arrFileContent = $objClassFile->getContentAsArray();
			$objReflector = new \ReflectionClass($strClassName);

			// properties
			$arrProperties = $objReflector->getProperties();
			
			// default name=>values array
			$arrDefault = $objReflector->getDefaultProperties();

			foreach ($arrProperties as $objProperty)
			{
				// only take those that are declared in the current class (omit parents)
				if (!preg_match('/' . preg_quote($strModelName) . '$/', $objProperty->getDeclaringClass()->getName()))
				{
					continue;
				}

				if (in_array($objProperty->getName(), $arrDefinedProperties))
				{
					continue;
				}

				$arrDefinedProperties[] = $objProperty->getName();

				// doc comment
				$objFile->append("\t" . $objProperty->getDocComment());
				
				// retrieve default value
				$strValue = $arrDefault[$objProperty->getName()];

				// property (cannot use getStartLine() and getEndLine())
				$objFile->append("\t" . implode(' ', \Reflection::getModifierNames($objProperty->getModifiers())) . ' $' . $objProperty->getName() . ($strValue ? " = '". $strValue . "'" : '') . ';');

				// add an additional line break
				$objFile->append("\n");
			}
		}

		// scan again for the methods
		foreach (array_reverse(scan(TL_ROOT . '/system/modules')) as $strModule)
		{
			// get the class name from the ClassLoader so namespaces are considered
			$strClassPath = 'system/modules/' . $strModule . '/models/' . $strModelName . '.php';
			$strClassName = array_search($strClassPath, $arrClasses);

			if (!$strClassName)
			{
				continue;
			}

			$objClassFile = new \File($strClassPath);
			$arrFileContent = $objClassFile->getContentAsArray();
			$objReflector = new \ReflectionClass($strClassName);

			// methods
			$arrMethods = $objReflector->getMethods(\ReflectionMethod::IS_PUBLIC);

			foreach ($arrMethods as $objMethod)
			{
				// only take those that are declared in the current class (omit parents)
				if (!preg_match('/' . preg_quote($strModelName) . '$/', $objMethod->getDeclaringClass()->getName()))
				{
					continue;
				}

				if (in_array($objMethod->getName(), $arrDefinedMethods))
				{
					continue;
				}

				$arrDefinedMethods[] = $objMethod->getName();

				// doc comment
				$objFile->append("\t" . $objMethod->getDocComment());

				// method
				$objFile->append($arrFileContent[$objMethod->getStartLine() - 1]);

				// method body
				for ($i=$objMethod->getStartLine();$i<=$objMethod->getEndLine() - 1;$i++)
				{
					$objFile->append($arrFileContent[$i]);
				}

				// add an additional line break
				$objFile->append("\n");
			}
		}

		$objFile->append("}");
		$objFile->close();
	}
}
