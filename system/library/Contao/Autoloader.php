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
 * Namespace
 */
namespace Contao;


/**
 * Class Autoloader
 *
 * This class provides methods to automatically load class files.
 * @copyright  Leo Feyer 2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Autoloader
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
		'Contao\\Cache'              => 'system/library/Contao/Cache.php',
		'Contao\\Combiner'           => 'system/library/Contao/Combiner.php',
		'Contao\\Config'             => 'system/library/Contao/Config.php',
		'Contao\\Controller'         => 'system/library/Contao/Controller.php',
		'Contao\\Database'           => 'system/library/Contao/Database.php',
		'Contao\\Database_Statement' => 'system/library/Contao/Database/Statement.php',
		'Contao\\Database_Result'    => 'system/library/Contao/Database/Result.php',
		'Contao\\Date'               => 'system/library/Contao/Date.php',
		'Contao\\Email'              => 'system/library/Contao/Email.php',
		'Contao\\Encryption'         => 'system/library/Contao/Encryption.php',
		'Contao\\Environment'        => 'system/library/Contao/Environment.php',
		'Contao\\Feed'               => 'system/library/Contao/Feed.php',
		'Contao\\File'               => 'system/library/Contao/File.php',
		'Contao\\FileCache'          => 'system/library/Contao/FileCache.php',
		'Contao\\Files'              => 'system/library/Contao/Files.php',
		'Contao\\Folder'             => 'system/library/Contao/Folder.php',
		'Contao\\FTP'                => 'system/library/Contao/FTP.php',
		'Contao\\Input'              => 'system/library/Contao/Input.php',
		'Contao\\Model'              => 'system/library/Contao/Model.php',
		'Contao\\Request'            => 'system/library/Contao/Request.php',
		'Contao\\RequestToken'       => 'system/library/Contao/RequestToken.php',
		'Contao\\Search'             => 'system/library/Contao/Search.php',
		'Contao\\Session'            => 'system/library/Contao/Session.php',
		'Contao\\String'             => 'system/library/Contao/String.php',
		'Contao\\System'             => 'system/library/Contao/System.php',
		'Contao\\Template'           => 'system/library/Contao/Template.php',
		'Contao\\User'               => 'system/library/Contao/User.php',
		'Contao\\Widget'             => 'system/library/Contao/Widget.php',
		'Contao\\ZipReader'          => 'system/library/Contao/ZipReader.php',
		'Contao\\ZipWriter'          => 'system/library/Contao/ZipWriter.php'
	);


	/**
	 * Add a new namespace (prepend it to override a core class)
	 * @param string
	 * @param boolean
	 */
	public static function addNamespace($name, $prepend=false)
	{
		if ($prepend) {
			array_unshift(self::$namespaces, $name);
		} else {
			array_push(self::$namespaces, $name);
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
	 */
	public static function addClass($class, $file)
	{
		self::$classes[$class] = $file;
	}


	/**
	 * Add multiple new classes with their file paths
	 * @param array
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
	 * Autoload a class and alias it into the global namespace
	 * @param string
	 * @throws \InvalidArgumentException
	 */
	public static function load($class)
	{
		if (class_exists($class, false) || interface_exists($class, false))
		{
			return;
		}

		if (isset(self::$classes[$class]))
		{
			include TL_ROOT . '/' . self::$classes[$class];
		}
		elseif (($namespaced = self::findClass($class)) != false)
		{
			include TL_ROOT . '/' . self::$classes[$namespaced];
			class_alias($namespaced, $class);
		}
		else
		{
			throw new \InvalidArgumentException("Cannot find $class");
		}
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
	}


	/**
	 * Register the autoloader
	 */
	public static function register()
	{
		spl_autoload_register('self::load');
	}


	/**
	 * Scan the module directories for config/autoload.php files
	 * and then register the autoloader on the SPL stack
	 */
	public static function scanAndRegister()
	{
		$dir = new \DirectoryIterator(TL_ROOT . '/system/modules');

		foreach ($dir as $file)
		{
			if ($file->isDot() || !$file->isDir() || strncmp($file->getFilename(), '.', 1) === 0)
			{
				continue;
			}

			if (file_exists($file->getPathname() . '/.skip') || !file_exists($file->getPathname() . '/config/autoload.php'))
			{
				continue;
			}

			include $file->getPathname() . '/config/autoload.php';
		}

		self::register();
	}
}

?>