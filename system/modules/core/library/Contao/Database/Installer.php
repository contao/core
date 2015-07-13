<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Database;


/**
 * Compares the existing database structure with the DCA table settings and
 * calculates the queries needed to update the database.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Installer extends \Controller
{

	/**
	 * Make the constructor public
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Generate a HTML form with queries and return it as string
	 *
	 * @return string The form HTML markup
	 */
	public function generateSqlForm()
	{
		$count = 0;
		$return = '';
		$sql_command = $this->compileCommands();

		if (empty($sql_command))
		{
			return '';
		}

		$_SESSION['sql_commands'] = array();

		$arrOperations = array
		(
			'CREATE'        => $GLOBALS['TL_LANG']['tl_install']['CREATE'],
			'ALTER_ADD'     => $GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'],
			'ALTER_CHANGE'  => $GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'],
			'ALTER_DROP'    => $GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'],
			'DROP'          => $GLOBALS['TL_LANG']['tl_install']['DROP']
		);

		foreach ($arrOperations as $command=>$label)
		{
			if (is_array($sql_command[$command]))
			{
				// Headline
				$return .= '
    <tr>
      <td colspan="2" class="tl_col_0">'.$label.'</td>
    </tr>';

				// Check all
				$return .= '
    <tr>
      <td class="tl_col_1"><input type="checkbox" id="check_all_' . $count . '" class="tl_checkbox" onclick="Backend.toggleCheckboxElements(this, \'' . strtolower($command) . '\')"></td>
      <td class="tl_col_2"><label for="check_all_' . $count . '" style="color:#a6a6a6"><em>' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</em></label></td>
    </tr>';

				// Fields
				foreach ($sql_command[$command] as $vv)
				{
					$key = md5($vv);
					$_SESSION['sql_commands'][$key] = $vv;

					$return .= '
    <tr>
      <td class="tl_col_1"><input type="checkbox" name="sql[]" id="sql_'.$count.'" class="tl_checkbox ' . strtolower($command) . '" value="'.$key.'"'.((stristr($command, 'DROP') === false) ? ' checked="checked"' : '').'></td>
      <td class="tl_col_2"><pre><label for="sql_'.$count++.'">'.$vv.'</label></pre></td>
    </tr>';
				}
			}
		}

		return '
<div id="sql_wrapper">
  <table id="sql_table">'.$return.'
  </table>
</div>';
	}


	/**
	 * Compile a command array for each database modification
	 *
	 * @return array An array of commands
	 */
	protected function compileCommands()
	{
		$drop = array();
		$create = array();
		$return = array();

		$sql_current = $this->getFromDb();
		$sql_target = $this->getFromDca();
		$sql_legacy = $this->getFromFile();

		// Manually merge the legacy definitions (see #4766)
		if (!empty($sql_legacy))
		{
			foreach ($sql_legacy as $table=>$categories)
			{
				foreach ($categories as $category=>$fields)
				{
					if (is_array($fields))
					{
						foreach ($fields as $name=>$sql)
						{
							$sql_target[$table][$category][$name] = $sql;
						}
					}
					else
					{
						$sql_target[$table][$category] = $fields;
					}
				}
			}
		}

		// Create tables
		foreach (array_diff(array_keys($sql_target), array_keys($sql_current)) as $table)
		{
			$return['CREATE'][] = "CREATE TABLE `" . $table . "` (\n  " . implode(",\n  ", $sql_target[$table]['TABLE_FIELDS']) . (!empty($sql_target[$table]['TABLE_CREATE_DEFINITIONS']) ? ',' . "\n  " . implode(",\n  ", $sql_target[$table]['TABLE_CREATE_DEFINITIONS']) : '') . "\n)" . $sql_target[$table]['TABLE_OPTIONS'] . ';';
			$create[] = $table;
		}

		// Add or change fields
		foreach ($sql_target as $k=>$v)
		{
			if (in_array($k, $create))
			{
				continue;
			}

			// Fields
			if (is_array($v['TABLE_FIELDS']))
			{
				foreach ($v['TABLE_FIELDS'] as $kk=>$vv)
				{
					if (!isset($sql_current[$k]['TABLE_FIELDS'][$kk]))
					{
						$return['ALTER_ADD'][] = 'ALTER TABLE `'.$k.'` ADD '.$vv.';';
					}
					elseif ($sql_current[$k]['TABLE_FIELDS'][$kk] != $vv)
					{
						$return['ALTER_CHANGE'][] = 'ALTER TABLE `'.$k.'` CHANGE `'.$kk.'` '.$vv.';';
					}
				}
			}

			// Create definitions
			if (is_array($v['TABLE_CREATE_DEFINITIONS']))
			{
				foreach ($v['TABLE_CREATE_DEFINITIONS'] as $kk=>$vv)
				{
					if (!isset($sql_current[$k]['TABLE_CREATE_DEFINITIONS'][$kk]))
					{
						$return['ALTER_ADD'][] = 'ALTER TABLE `'.$k.'` ADD '.$vv.';';
					}
					elseif ($sql_current[$k]['TABLE_CREATE_DEFINITIONS'][$kk] != str_replace('FULLTEXT ', '', $vv))
					{
						$return['ALTER_CHANGE'][] = 'ALTER TABLE `'.$k.'` DROP INDEX `'.$kk.'`, ADD '.$vv.';';
					}
				}
			}

			// Move auto_increment fields to the end of the array
			if (is_array($return['ALTER_ADD']))
			{
				foreach (preg_grep('/auto_increment/i', $return['ALTER_ADD']) as $kk=>$vv)
				{
					unset($return['ALTER_ADD'][$kk]);
					$return['ALTER_ADD'][$kk] = $vv;
				}
			}

			if (is_array($return['ALTER_CHANGE']))
			{
				foreach (preg_grep('/auto_increment/i', $return['ALTER_CHANGE']) as $kk=>$vv)
				{
					unset($return['ALTER_CHANGE'][$kk]);
					$return['ALTER_CHANGE'][$kk] = $vv;
				}
			}
		}

		// Drop tables
		foreach (array_diff(array_keys($sql_current), array_keys($sql_target)) as $table)
		{
			$return['DROP'][] = 'DROP TABLE `'.$table.'`;';
			$drop[] = $table;
		}

		// Drop fields
		foreach ($sql_current as $k=>$v)
		{
			if (!in_array($k, $drop))
			{
				// Create definitions
				if (is_array($v['TABLE_CREATE_DEFINITIONS']))
				{
					foreach ($v['TABLE_CREATE_DEFINITIONS'] as $kk=>$vv)
					{
						if (!isset($sql_target[$k]['TABLE_CREATE_DEFINITIONS'][$kk]))
						{
							$return['ALTER_DROP'][] = 'ALTER TABLE `'.$k.'` DROP INDEX `'.$kk.'`;';
						}
					}
				}

				// Fields
				if (is_array($v['TABLE_FIELDS']))
				{
					foreach ($v['TABLE_FIELDS'] as $kk=>$vv)
					{
						if (!isset($sql_target[$k]['TABLE_FIELDS'][$kk]))
						{
							$return['ALTER_DROP'][] = 'ALTER TABLE `'.$k.'` DROP `'.$kk.'`;';
						}
					}
				}
			}
		}

		// HOOK: allow third-party developers to modify the array (see #3281)
		if (isset($GLOBALS['TL_HOOKS']['sqlCompileCommands']) && is_array($GLOBALS['TL_HOOKS']['sqlCompileCommands']))
		{
			foreach ($GLOBALS['TL_HOOKS']['sqlCompileCommands'] as $callback)
			{
				$this->import($callback[0]);
				$return = $this->$callback[0]->$callback[1]($return);
			}
		}

		// Remove the DROP statements if the safe mode is active (see #7085)
		if (\Config::get('coreOnlyMode'))
		{
			unset($return['DROP']);
			unset($return['ALTER_DROP']);
		}

		return $return;
	}


	/**
	 * Get the DCA table settings from the DCA cache
	 *
	 * @return array An array of DCA table settings
	 */
	public function getFromDca()
	{
		$return = array();
		$included = array();

		// Ignore the internal cache
		$blnBypassCache = \Config::get('bypassCache');
		\Config::set('bypassCache', true);

		// Only check the active modules (see #4541)
		foreach (\ModuleLoader::getActive() as $strModule)
		{
			$strDir = 'system/modules/' . $strModule . '/dca';

			if (!is_dir(TL_ROOT . '/' . $strDir))
			{
				continue;
			}

			foreach (scan(TL_ROOT . '/' . $strDir) as $strFile)
			{
				// Ignore non PHP files and files which have been included before
				if (substr($strFile, -4) != '.php' || in_array($strFile, $included))
				{
					continue;
				}

				$strTable = substr($strFile, 0, -4);
				$objExtract = \DcaExtractor::getInstance($strTable);

				if ($objExtract->isDbTable())
				{
					$return[$strTable] = $objExtract->getDbInstallerArray();
				}

				$included[] = $strFile;
			}
		}

		// Restore the cache settings
		\Config::set('bypassCache', $blnBypassCache);

		// HOOK: allow third-party developers to modify the array (see #6425)
		if (isset($GLOBALS['TL_HOOKS']['sqlGetFromDca']) && is_array($GLOBALS['TL_HOOKS']['sqlGetFromDca']))
		{
			foreach ($GLOBALS['TL_HOOKS']['sqlGetFromDca'] as $callback)
			{
				$this->import($callback[0]);
				$return = $this->$callback[0]->$callback[1]($return);
			}
		}

		return $return;
	}


	/**
	 * Get the DCA table settings from the database.sql files
	 *
	 * @return array An array of DCA table settings
	 */
	public function getFromFile()
	{
		$table = '';
		$return = array();

		// Only check the active modules (see #4541)
		foreach (\ModuleLoader::getActive() as $strModule)
		{
			if (strncmp($strModule, '.', 1) === 0 || strncmp($strModule, '__', 2) === 0)
			{
				continue;
			}

			// Ignore the database.sql of the not renamed core modules
			if (in_array($strModule, array('calendar', 'comments', 'faq', 'listing', 'news', 'newsletter')))
			{
				continue;
			}

			$strFile = TL_ROOT . '/system/modules/' . $strModule . '/config/database.sql';

			if (!file_exists($strFile))
			{
				continue;
			}

			$data = file($strFile);

			foreach ($data as $k=>$v)
			{
				$key_name = array();
				$subpatterns = array();

				// Unset comments and empty lines
				if (preg_match('/^[#-]+/', $v) || !strlen(trim($v)))
				{
					unset($data[$k]);
					continue;
				}

				// Store the table names
				if (preg_match('/^CREATE TABLE `([^`]+)`/i', $v, $subpatterns))
				{
					$table = $subpatterns[1];
				}
				// Get the table options
				elseif ($table != '' && preg_match('/^\)([^;]+);/', $v, $subpatterns))
				{
					$return[$table]['TABLE_OPTIONS'] = $subpatterns[1];
					$table = '';
				}
				// Add the fields
				elseif ($table != '')
				{
					preg_match('/^[^`]*`([^`]+)`/', trim($v), $key_name);
					$first = preg_replace('/\s[^\n\r]+/', '', $key_name[0]);
					$key = $key_name[1];

					// Create definitions
					if (in_array($first, array('KEY', 'PRIMARY', 'PRIMARY KEY', 'FOREIGN', 'FOREIGN KEY', 'INDEX', 'UNIQUE', 'FULLTEXT', 'CHECK')))
					{
						if (strncmp($first, 'PRIMARY', 7) === 0)
						{
							$key = 'PRIMARY';
						}

						$return[$table]['TABLE_CREATE_DEFINITIONS'][$key] = preg_replace('/,$/', '', trim($v));
					}
					else
					{
						$return[$table]['TABLE_FIELDS'][$key] = preg_replace('/,$/', '', trim($v));
					}
				}
			}
		}

		// HOOK: allow third-party developers to modify the array (see #3281)
		if (isset($GLOBALS['TL_HOOKS']['sqlGetFromFile']) && is_array($GLOBALS['TL_HOOKS']['sqlGetFromFile']))
		{
			foreach ($GLOBALS['TL_HOOKS']['sqlGetFromFile'] as $callback)
			{
				$this->import($callback[0]);
				$return = $this->$callback[0]->$callback[1]($return);
			}
		}

		return $return;
	}


	/**
	 * Get the current database structure
	 *
	 * @return array An array of tables and fields
	 */
	public function getFromDb()
	{
		$this->import('Database');
		$tables = preg_grep('/^tl_/', $this->Database->listTables(null, true));

		if (empty($tables))
		{
			return array();
		}

		$return = array();
		$quote = function ($item) { return '`' . $item . '`'; };

		foreach ($tables as $table)
		{
			$fields = $this->Database->listFields($table, true);

			foreach ($fields as $field)
			{
				$name = $field['name'];
				$field['name'] = $quote($field['name']);

				if ($field['type'] != 'index')
				{
					unset($field['index']);
					unset($field['origtype']);

					// Field type
					if ($field['length'] != '')
					{
						$field['type'] .= '(' . $field['length'] . (($field['precision'] != '') ? ',' . $field['precision'] : '') . ')';

						unset($field['length']);
						unset($field['precision']);
					}

					// Variant collation
					if ($field['collation'] != '' && $field['collation'] != \Config::get('dbCollation'))
					{
						$field['collation'] = 'COLLATE ' . $field['collation'];
					}
					else
					{
						unset($field['collation']);
					}

					// Default values
					if (in_array(strtolower($field['type']), array('text', 'tinytext', 'mediumtext', 'longtext', 'blob', 'tinyblob', 'mediumblob', 'longblob')) || stristr($field['extra'], 'auto_increment') || $field['default'] === null || strtolower($field['null']) == 'null')
					{
						unset($field['default']);
					}
					// Date/time constants (see #5089)
					elseif (in_array(strtolower($field['default']), array('current_date', 'current_time', 'current_timestamp')))
					{
						$field['default'] = "default " . $field['default'];
					}
					// Everything else
					else
					{
						$field['default'] = "default '" . $field['default'] . "'";
					}

					$return[$table]['TABLE_FIELDS'][$name] = trim(implode(' ', $field));
				}

				// Indices
				if (isset($field['index']) && $field['index_fields'])
				{
					// Quote the field names
					$index_fields = implode(', ', array_map
					(
						function ($item) use ($quote) {
							if (strpos($item, '(') === false) {
								return $quote($item);
							}

							list($name, $length) = explode('(', rtrim($item, ')'));

							return $quote($name) . '(' . $length . ')';
						},
						$field['index_fields'])
					);

					switch ($field['index'])
					{
						case 'UNIQUE':
							if ($name == 'PRIMARY')
							{
								$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'PRIMARY KEY  ('.$index_fields.')';
							}
							else
							{
								$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'UNIQUE KEY `'.$name.'` ('.$index_fields.')';
							}
							break;

						case 'FULLTEXT':
							$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'FULLTEXT KEY `'.$name.'` ('.$index_fields.')';
							break;

						default:
							$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'KEY `'.$name.'` ('.$index_fields.')';
							break;
					}

					unset($field['index_fields']);
					unset($field['index']);
				}
			}
		}

		// HOOK: allow third-party developers to modify the array (see #3281)
		if (isset($GLOBALS['TL_HOOKS']['sqlGetFromDB']) && is_array($GLOBALS['TL_HOOKS']['sqlGetFromDB']))
		{
			foreach ($GLOBALS['TL_HOOKS']['sqlGetFromDB'] as $callback)
			{
				$this->import($callback[0]);
				$return = $this->$callback[0]->$callback[1]($return);
			}
		}

		return $return;
	}
}
