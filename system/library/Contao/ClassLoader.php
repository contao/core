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


/**
 * Class ClassLoader
 *
 * Provide methods to automatically load class files.
 * @copyright  Leo Feyer 2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class ClassLoader
{

	/**
	 * Known namespaces
	 * @var array
	 */
	protected static $namespaces = array
	(
		'Contao'
	);

	/**
	 * Known classes
	 * @var array
	 */
	protected static $classes = array
	(
		'Contao\\Cache'        => 'system/library/Contao/Cache.php',
		'Contao\\Combiner'     => 'system/library/Contao/Combiner.php',
		'Contao\\Config'       => 'system/library/Contao/Config.php',
		'Contao\\Controller'   => 'system/library/Contao/Controller.php',
		'Contao\\Date'         => 'system/library/Contao/Date.php',
		'Contao\\DbInstaller'  => 'system/library/Contao/DbInstaller.php',
		'Contao\\DbUpdater'    => 'system/library/Contao/DbUpdater.php',
		'Contao\\DcaExtractor' => 'system/library/Contao/DcaExtractor.php',
		'Contao\\Email'        => 'system/library/Contao/Email.php',
		'Contao\\Encryption'   => 'system/library/Contao/Encryption.php',
		'Contao\\Environment'  => 'system/library/Contao/Environment.php',
		'Contao\\Feed'         => 'system/library/Contao/Feed.php',
		'Contao\\FeedItem'     => 'system/library/Contao/FeedItem.php',
		'Contao\\File'         => 'system/library/Contao/File.php',
		'Contao\\FileCache'    => 'system/library/Contao/FileCache.php',
		'Contao\\Folder'       => 'system/library/Contao/Folder.php',
		'Contao\\Idna'         => 'system/library/Contao/Idna.php',
		'Contao\\FTP'          => 'system/library/Contao/FTP.php',
		'Contao\\Input'        => 'system/library/Contao/Input.php',
		'Contao\\Model'        => 'system/library/Contao/Model.php',
		'Contao\\Request'      => 'system/library/Contao/Request.php',
		'Contao\\RequestToken' => 'system/library/Contao/RequestToken.php',
		'Contao\\Search'       => 'system/library/Contao/Search.php',
		'Contao\\Session'      => 'system/library/Contao/Session.php',
		'Contao\\String'       => 'system/library/Contao/String.php',
		'Contao\\System'       => 'system/library/Contao/System.php',
		'Contao\\Template'     => 'system/library/Contao/Template.php',
		'Contao\\User'         => 'system/library/Contao/User.php',
		'Contao\\Validator'    => 'system/library/Contao/Validator.php',
		'Contao\\Widget'       => 'system/library/Contao/Widget.php',
		'Contao\\ZipReader'    => 'system/library/Contao/ZipReader.php',
		'Contao\\ZipWriter'    => 'system/library/Contao/ZipWriter.php',

		// Database
		'Contao\\Database'                      => 'system/library/Contao/Database.php',
		'Contao\\Database_Statement'            => 'system/library/Contao/Database/Statement.php',
		'Contao\\Database_Result'               => 'system/library/Contao/Database/Result.php',
		'Contao\\Database_Mssql'                => 'system/library/Contao/Database/Mssql.php',
		'Contao\\Database_Mssql_Statement'      => 'system/library/Contao/Database/Mssql/Statement.php',
		'Contao\\Database_Mssql_Result'         => 'system/library/Contao/Database/Mssql/Result.php',
		'Contao\\Database_Mysql'                => 'system/library/Contao/Database/Mysql.php',
		'Contao\\Database_Mysql_Statement'      => 'system/library/Contao/Database/Mysql/Statement.php',
		'Contao\\Database_Mysql_Result'         => 'system/library/Contao/Database/Mysql/Result.php',
		'Contao\\Database_Mysqli'               => 'system/library/Contao/Database/Mysqli.php',
		'Contao\\Database_Mysqli_Statement'     => 'system/library/Contao/Database/Mysqli/Statement.php',
		'Contao\\Database_Mysqli_Result'        => 'system/library/Contao/Database/Mysqli/Result.php',
		'Contao\\Database_Oracle'               => 'system/library/Contao/Database/Oracle.php',
		'Contao\\Database_Oracle_Statement'     => 'system/library/Contao/Database/Oracle/Statement.php',
		'Contao\\Database_Oracle_Result'        => 'system/library/Contao/Database/Oracle/Result.php',
		'Contao\\Database_Postgresql'           => 'system/library/Contao/Database/Postgresql.php',
		'Contao\\Database_Postgresql_Statement' => 'system/library/Contao/Database/Postgresql/Statement.php',
		'Contao\\Database_Postgresql_Result'    => 'system/library/Contao/Database/Postgresql/Result.php',
		'Contao\\Database_Sybase'               => 'system/library/Contao/Database/Sybase.php',
		'Contao\\Database_Sybase_Statement'     => 'system/library/Contao/Database/Sybase/Statement.php',
		'Contao\\Database_Sybase_Result'        => 'system/library/Contao/Database/Sybase/Result.php',

		// Files
		'Contao\\Files'     => 'system/library/Contao/Files.php',
		'Contao\\Files_Ftp' => 'system/library/Contao/Files/Ftp.php',
		'Contao\\Files_Php' => 'system/library/Contao/Files/Php.php',

		// Model
		'Contao\\Model_Collection'   => 'system/library/Contao/Model/Collection.php',
		'Contao\\Model_QueryBuilder' => 'system/library/Contao/Model/QueryBuilder.php',
	);


	/**
	 * Add a new namespace
	 * @param string
	 * @return void
	 */
	public static function addNamespace($name)
	{
		if (in_array($name, self::$namespaces))
		{
			return;
		}

		array_unshift(self::$namespaces, $name);
	}


	/**
	 * Add a new namespace
	 * @param array
	 * @return void
	 */
	public static function addNamespaces($names)
	{
		foreach ($names as $name)
		{
			self::addNamespace($name);
		}
	}


	/**
	 * Return the namespaces as array
	 * @return array
	 */
	public static function getNamespaces()
	{
		return self::$namespaces;
	}


	/**
	 * Add a new class with its file path
	 * @param string
	 * @param string
	 * @return void
	 */
	public static function addClass($class, $file)
	{
		self::$classes[$class] = $file;
	}


	/**
	 * Add multiple new classes with their file paths
	 * @param array
	 * @return void
	 */
	public static function addClasses($classes)
	{
		foreach ($classes as $class=>$file)
		{
			self::addClass($class, $file);
		}
	}


	/**
	 * Return the classes as array
	 * @return array
	 */
	public static function getClasses()
	{
		return self::$classes;
	}


	/**
	 * Autoload a class and create an alias in the global namespace to
	 * preserve backwards compatibility with Contao 2 extensions
	 * @param string
	 * @return void
	 */
	public static function load($class)
	{
		if (class_exists($class, false) || interface_exists($class, false))
		{
			return;
		}

		// The class file is set in the mapper
		if (isset(self::$classes[$class]))
		{
			if ($GLOBALS['TL_CONFIG']['debugMode'])
			{
				$GLOBALS['TL_DEBUG']['classes_set'][] = $class;
			}

			include TL_ROOT . '/' . self::$classes[$class];
		}
		// Find the class in the registered namespaces
		elseif (($namespaced = self::findClass($class)) != false)
		{
			if ($GLOBALS['TL_CONFIG']['debugMode'])
			{
				$GLOBALS['TL_DEBUG']['classes_aliased'][] = $class . ' <span style="color:#999">(' . $namespaced . ')</span>';
			}

			include TL_ROOT . '/' . self::$classes[$namespaced];
			class_alias($namespaced, $class);
		}

		// Pass the request to other autoloaders (e.g. Swift)
	}


	/**
	 * Search the namespaces for a matching entry
	 * @param string
	 * @return string
	 */
	protected static function findClass($class)
	{
		foreach (self::$namespaces as $namespace)
		{
			if (isset(self::$classes[$namespace . '\\' . $class]))
			{
				return $namespace . '\\' . $class;
			}
		}

		return '';
	}


	/**
	 * Register the autoloader
	 * @return void
	 */
	public static function register()
	{
		spl_autoload_register('self::load');
	}


	/**
	 * Scan the module directories for config/autoload.php files
	 * and then register the autoloader on the SPL stack
	 * @return void
	 */
	public static function scanAndRegister()
	{
		foreach (scan(TL_ROOT . '/system/modules') as $file)
		{
			$path = TL_ROOT . '/system/modules/' . $file;

			if (strncmp($file, '.', 1) === 0 || !is_dir($path))
			{
				continue;
			}

			if (file_exists($path . '/.skip') || !file_exists($path . '/config/autoload.php'))
			{
				continue;
			}

			include $path . '/config/autoload.php';
		}

		self::register();
	}
}
