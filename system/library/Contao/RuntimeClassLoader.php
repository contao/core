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
class RuntimeClassLoader
{
	/**
	 * Marker when loading the non-runtime class
	 *
	 * @var bool
	 */
	protected static $triggerWarning = false;

	/**
	 * Known class mappings
	 *
	 * @var array
	 */
	protected static $classMapping = array();


	/**
	 * Add a class mapping
	 * 
	 * @param string $name The namespace name
	 */
	public static function addClassMapping($runtimeClass, $targetClass)
	{
		self::$classMapping[$runtimeClass] = $targetClass;
	}


	/**
	 * Add multiple class mappings
	 *
	 * @param string $name The namespace name
	 */
	public static function addClassMappings($classMappings)
	{
		foreach ($classMappings as $runtimeClass => $targetClass)
		{
			self::addClassMapping($runtimeClass, $targetClass);
		}
	}


	/**
	 * Return the class mapping as array
	 * 
	 * @return array An array of all namespaces
	 */
	public static function getClassMapping()
	{
		return self::$classMapping;
	}


	/**
	 * Autoload a runtime class and create an alias
	 * 
	 * @param string $runtimeClass The class name
	 */
	public static function load($runtimeClass)
	{
		if (class_exists($runtimeClass, false) || interface_exists($runtimeClass, false))
		{
			return;
		}

		// The class is a runtime class
		if (strncmp($runtimeClass, 'Runtime\\', 8) === 0)
		{
			// The class file is mapped
			if (isset(self::$classMapping[$runtimeClass]))
			{
				$targetClass = self::$classMapping[$runtimeClass];
			}

			// remove the Runtime\ prefix
			else
			{
				$targetClass = substr($runtimeClass, 8);
			}

			// do not trigger warnings while loading the target class
			self::$triggerWarning = false;

			// create the class alias
			class_alias($targetClass, $runtimeClass);

			// now trigger warnings for future class loads
			self::$triggerWarning = true;
		}

		// Trigger a warning for non global namespace classes
		else if (self::$triggerWarning && strpos($runtimeClass, '\\') !== false)
		{
			trigger_error('You should use Runtime\\' . $runtimeClass . ' when using your class ' . $runtimeClass . '.', E_USER_WARNING);
		}
	}


	/**
	 * Register the autoloader
	 */
	public static function register()
	{
		spl_autoload_register('RuntimeClassLoader::load');
	}
}
