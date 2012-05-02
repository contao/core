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

use \Exception;


/**
 * Class TemplateLoader
 *
 * Provide methods to automatically load template files.
 * @copyright  Leo Feyer 2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class TemplateLoader
{

	/**
	 * Known files
	 * @var array
	 */
	protected static $files = array();


	/**
	 * Add a new template with its file path
	 * @param string
	 * @param string
	 */
	public static function addFile($name, $file)
	{
		self::$files[$name] = $file;
	}


	/**
	 * Add multiple new templates with their file paths
	 * @param array
	 */
	public static function addFiles($files)
	{
		foreach ($files as $name=>$file)
		{
			self::addFile($name, $file);
		}
	}


	/**
	 * Return the files as array
	 * @return array
	 */
	public static function getFiles()
	{
		return self::$files;
	}


	/**
	 * Return the files matching the prefix as array
	 * @param string
	 * @return array
	 */
	public static function getPrefixedFiles($prefix)
	{
		return array_values(preg_grep('/^' . $prefix . '/', array_keys(self::$files)));
	}


	/**
	 * Return a template path
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 * @throws \Exception
	 */
	public static function getPath($template, $format, $custom='templates')
	{
		$file = $template .  '.' . $format;

		if (file_exists(TL_ROOT . '/' . $custom . '/' . $file))
		{
			return TL_ROOT . '/' . $custom . '/' . $file;
		}

		if (isset(self::$files[$template]))
		{
			return TL_ROOT . '/' . self::$files[$template] . '/' . $file;
		}

		throw new Exception('Could not find template "' . $template . '"');
	}
}
