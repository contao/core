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
 * Automatically loads class files based on a mapper array
 * 
 * The class stores namespaces and classes and automatically loads the class
 * files upon their first usage. It uses a mapper array to support complex
 * nesting and arbitrary subfolders to store the class files in.
 * 
 * Usage:
 * 
 *     ClassLoader::addNamespace('Custom');
 *     ClassLoader::addClass('Custom\\Calendar', 'calendar/Calendar.php');
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
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
	 * Class mapping
	 * @var array
	 */
	protected static $classMapping = array();

	/**
	 * Known classes
	 * @var array
	 */
	protected static $classes = array
	(
		'Contao\Cache'        => 'system/library/Contao/Cache.php',
		'Contao\Combiner'     => 'system/library/Contao/Combiner.php',
		'Contao\Config'       => 'system/library/Contao/Config.php',
		'Contao\Controller'   => 'system/library/Contao/Controller.php',
		'Contao\Date'         => 'system/library/Contao/Date.php',
		'Contao\DcaExtractor' => 'system/library/Contao/DcaExtractor.php',
		'Contao\Email'        => 'system/library/Contao/Email.php',
		'Contao\Encryption'   => 'system/library/Contao/Encryption.php',
		'Contao\Environment'  => 'system/library/Contao/Environment.php',
		'Contao\Feed'         => 'system/library/Contao/Feed.php',
		'Contao\FeedItem'     => 'system/library/Contao/FeedItem.php',
		'Contao\File'         => 'system/library/Contao/File.php',
		'Contao\Folder'       => 'system/library/Contao/Folder.php',
		'Contao\Idna'         => 'system/library/Contao/Idna.php',
		'Contao\Image'        => 'system/library/Contao/Image.php',
		'Contao\Input'        => 'system/library/Contao/Input.php',
		'Contao\Message'      => 'system/library/Contao/Message.php',
		'Contao\Model'        => 'system/library/Contao/Model.php',
		'Contao\Request'      => 'system/library/Contao/Request.php',
		'Contao\RequestToken' => 'system/library/Contao/RequestToken.php',
		'Contao\Search'       => 'system/library/Contao/Search.php',
		'Contao\Session'      => 'system/library/Contao/Session.php',
		'Contao\String'       => 'system/library/Contao/String.php',
		'Contao\System'       => 'system/library/Contao/System.php',
		'Contao\Template'     => 'system/library/Contao/Template.php',
		'Contao\User'         => 'system/library/Contao/User.php',
		'Contao\Validator'    => 'system/library/Contao/Validator.php',
		'Contao\Widget'       => 'system/library/Contao/Widget.php',
		'Contao\ZipReader'    => 'system/library/Contao/ZipReader.php',
		'Contao\ZipWriter'    => 'system/library/Contao/ZipWriter.php',

		// Database
		'Contao\Database'                      => 'system/library/Contao/Database.php',
		'Contao\Database_Installer'            => 'system/library/Contao/Database/Installer.php',
		'Contao\Database_Result'               => 'system/library/Contao/Database/Result.php',
		'Contao\Database_Statement'            => 'system/library/Contao/Database/Statement.php',
		'Contao\Database_Updater'              => 'system/library/Contao/Database/Updater.php',
		'Contao\Database_Mysql'                => 'system/library/Contao/Database/Mysql.php',
		'Contao\Database_Mysql_Result'         => 'system/library/Contao/Database/Mysql/Result.php',
		'Contao\Database_Mysql_Statement'      => 'system/library/Contao/Database/Mysql/Statement.php',
		'Contao\Database_Mysqli'               => 'system/library/Contao/Database/Mysqli.php',
		'Contao\Database_Mysqli_Result'        => 'system/library/Contao/Database/Mysqli/Result.php',
		'Contao\Database_Mysqli_Statement'     => 'system/library/Contao/Database/Mysqli/Statement.php',

		// Files
		'Contao\Files'     => 'system/library/Contao/Files.php',
		'Contao\Files_Ftp' => 'system/library/Contao/Files/Ftp.php',
		'Contao\Files_Php' => 'system/library/Contao/Files/Php.php',

		// Model
		'Contao\Model_Collection'   => 'system/library/Contao/Model/Collection.php',
		'Contao\Model_QueryBuilder' => 'system/library/Contao/Model/QueryBuilder.php',
	);


	/**
	 * Add a new namespace
	 * 
	 * @param string $name The namespace name
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
	 * Add multiple new namespaces
	 * 
	 * @param array $names An array of namespace names
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
	 * 
	 * @return array An array of all namespaces
	 */
	public static function getNamespaces()
	{
		return self::$namespaces;
	}


	/**
	 * Add a class mapping
	 *
	 * @param string $name The origin class name
	 * @param string $target The target class name
	 */
	public static function addClassMapping($name, $target)
	{
		// prepend mapping to allow overwrite
		if (isset(self::$classMapping[$name]))
		{
			array_unshift(self::$classMapping[$name], $target);
		}

		// or add new mapping
		else
		{
			self::$classMapping[$name] = array($target);
		}
	}


	/**
	 * Add a class mapping
	 *
	 * @param array $mappings An mapping array with origin class as key and target class as value.
	 */
	public static function addClassMappings($mappings)
	{
		foreach ($mappings as $name => $target)
		{
			self::addClassMapping($name, $target);
		}
	}


	/**
	 * Return the class mapping as array
	 *
	 * @return array An array of all namespace mappings
	 */
	public static function getClassMappings()
	{
		return self::$classMapping;
	}


	/**
	 * Add a new class with its file path
	 * 
	 * @param string $class The class name
	 * @param string $file  The path to the class file
	 */
	public static function addClass($class, $file)
	{
		self::$classes[$class] = $file;
	}


	/**
	 * Add multiple new classes with their file paths
	 * 
	 * @param array $classes An array of classes
	 */
	public static function addClasses($classes)
	{
		foreach ($classes as $class=>$file)
		{
			self::addClass($class, $file);
		}
	}


	/**
	 * Return the classes as array.
	 * 
	 * @return array An array of all classes
	 */
	public static function getClasses()
	{
		return self::$classes;
	}


	/**
	 * Autoload a class and create an alias in the global namespace
	 * 
	 * To preserve backwards compatibility with Contao 2 extensions, all core
	 * classes will be aliased into the global namespace.
	 * 
	 * @param string $class The class name
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

			if (strpos($class, '\\') !== false && !preg_match('#^Runtime\\\\#', $class))
			{
				trigger_error('You should the runtime namespace when using your class, please prepend Runtime\\ to your namespaced class ' . $class . '!', E_USER_WARNING);
			}

			include TL_ROOT . '/' . self::$classes[$class];
		}

		// Find class in the class mappings
		elseif (isset(self::$classMapping[$class]))
		{
			$aliased = false;

			foreach (self::$classMapping[$class] as $target)
			{
				// The class file is set in the mapper
				if (isset(self::$classes[$target]))
				{
					if ($GLOBALS['TL_CONFIG']['debugMode'])
					{
						$GLOBALS['TL_DEBUG']['classes_set'][] = $class;
					}
					include_once TL_ROOT . '/' . self::$classes[$target];

					if (!$aliased) {
						if ($GLOBALS['TL_CONFIG']['debugMode'])
						{
							$GLOBALS['TL_DEBUG']['classes_aliased'][] = $class . ' <span style="color:#999">(' . $target . ' &rarr; ' . $class . ')</span>';
						}

						// Create an alias for the mapped class
						class_alias($target, $class);
						$aliased = true;
					}
				}
			}

			// Return if an alias is created
			if ($aliased) {
				return;
			}
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
	 * 
	 * @param string $class The class name
	 * 
	 * @return string The full path including the namespace
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
	 */
	public static function register()
	{
		spl_autoload_register('self::load');
	}


	/**
	 * Scan the module directories for config/autoload.php files and then
	 * register the autoloader on the SPL stack
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
