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
 * Automatically loads template files based on a mapper array
 *
 * The class stores template names and automatically loads the files upon their
 * first usage. It uses a mapper array to support complex nesting and arbitrary
 * subfolders to store the template files in.
 *
 * Usage:
 *
 *     ClassLoader::addFile('moo_mediabox', 'core/templates');
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @param string $name The template name
	 * @param string $file The path to the template folder
	 */
	public static function addFile($name, $file)
	{
		self::$files[$name] = $file;
	}


	/**
	 * Add multiple new templates with their file paths
	 *
	 * @param array $files An array of files
	 */
	public static function addFiles($files)
	{
		foreach ($files as $name=>$file)
		{
			self::addFile($name, $file);
		}
	}


	/**
	 * Return the template files as array
	 *
	 * @return array An array of files
	 */
	public static function getFiles()
	{
		return self::$files;
	}


	/**
	 * Return the files matching a prefix as array
	 *
	 * @param string $prefix The prefix (e.g. "moo_")
	 *
	 * @return array An array of matching files
	 */
	public static function getPrefixedFiles($prefix)
	{
		return array_values(preg_grep('/^' . $prefix . '/', array_keys(self::$files)));
	}


	/**
	 * Return a template path
	 *
	 * @param string $template The template name
	 * @param string $format   The output format (e.g. "html5")
	 * @param string $custom   The custom templates folder (defaults to "templates")
	 *
	 * @return string The path to the template file
	 *
	 * @throws \Exception If $template does not exist
	 */
	public static function getPath($template, $format, $custom='templates')
	{
		$file = $template .  '.' . $format;

		// Check the theme folder first
		if (file_exists(TL_ROOT . '/' . $custom . '/' . $file))
		{
			return TL_ROOT . '/' . $custom . '/' . $file;
		}

		// Then check the global templates directory (see #5547)
		if ($custom != 'templates')
		{
			if (file_exists(TL_ROOT . '/templates/' . $file))
			{
				return TL_ROOT . '/templates/' . $file;
			}
		}

		// Load the default template
		if (isset(self::$files[$template]))
		{
			return TL_ROOT . '/' . self::$files[$template] . '/' . $file;
		}

		throw new \Exception('Could not find template "' . $template . '"');
	}


	/**
	 * Return the path to the default template
	 *
	 * @param string $template The template name
	 * @param string $format   The output format (e.g. "html5")
	 *
	 * @return string The path to the default template file
	 *
	 * @throws \Exception If $template does not exist
	 */
	public static function getDefaultPath($template, $format)
	{
		$file = $template .  '.' . $format;

		if (isset(self::$files[$template]))
		{
			return TL_ROOT . '/' . self::$files[$template] . '/' . $file;
		}

		throw new \Exception('Could not find template "' . $template . '"');
	}
}
