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
	 * @return string
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

		throw new \Exception('Could not find template "' . $template . '"');
	}
}

?>