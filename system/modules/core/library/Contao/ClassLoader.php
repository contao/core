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
 * @author Leo Feyer <https://github.com/leofeyer>
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
	protected static $classes = array();


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
			if (\Config::get('debugMode'))
			{
				$GLOBALS['TL_DEBUG']['classes_set'][] = $class;
			}

			include TL_ROOT . '/' . self::$classes[$class];
		}

 		// Find the class in the registered namespaces
		elseif (($namespaced = self::findClass($class)) !== null)
 		{
			if (!class_exists($namespaced, false) && !interface_exists($namespaced, false))
			{
				if (\Config::get('debugMode'))
				{
					$GLOBALS['TL_DEBUG']['classes_aliased'][] = $class . ' <span style="color:#999">(' . $namespaced . ')</span>';
				}

				include TL_ROOT . '/' . self::$classes[$namespaced];
			}

			class_alias($namespaced, $class);
		}

		// Pass the request to other autoloaders (e.g. Swift)
	}


	/**
	 * Search the namespaces for a matching entry
	 *
	 * @param string $class The class name
	 *
	 * @return string|null The full path including the namespace or null
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

		return null;
	}


	/**
	 * Register the autoloader
	 */
	public static function register()
	{
		spl_autoload_register('ClassLoader::load');
	}


	/**
	 * Scan the module directories for config/autoload.php files and then
	 * register the autoloader on the SPL stack
	 */
	public static function scanAndRegister()
	{
		$strCacheFile = 'system/cache/config/autoload.php';

		// Try to load from cache
		if (!\Config::get('bypassCache') && file_exists(TL_ROOT . '/' . $strCacheFile))
		{
			include TL_ROOT . '/' . $strCacheFile;
		}
		else
		{
			foreach (\ModuleLoader::getActive() as $module)
			{
				$file = 'system/modules/' . $module . '/config/autoload.php';

				if (file_exists(TL_ROOT . '/' . $file))
				{
					include TL_ROOT . '/' . $file;
				}
			}
		}

		self::register();
	}
}
