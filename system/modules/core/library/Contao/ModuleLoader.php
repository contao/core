<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Loads modules based on their autoload.ini configuration
 *
 * The class reads the autoload.ini files of the available modules and returns
 * an array of active modules with their dependencies solved.
 *
 * Usage:
 *
 *     $arrModules = ModuleLoader::getActive();
 *     $arrModules = ModuleLoader::getDisabled();
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class ModuleLoader
{

	/**
	 * Active modules
	 * @var array
	 */
	protected static $active;

	/**
	 * Disabled modules
	 * @var array
	 */
	protected static $disabled;


	/**
	 * Return the active modules as array
	 *
	 * @return array An array of active modules
	 */
	public static function getActive()
	{
		if (static::$active === null)
		{
			static::scanAndResolve();
		}

		return static::$active;
	}


	/**
	 * Return the disabled modules as array
	 *
	 * @return array An array of disabled modules
	 */
	public static function getDisabled()
	{
		if (static::$active === null)
		{
			static::scanAndResolve();
		}

		return static::$disabled;
	}


	/**
	 * Scan the modules and resolve their dependencies
	 *
	 * @throws \UnresolvableDependenciesException If the dependencies cannot be resolved
	 */
	protected static function scanAndResolve()
	{
		$strCacheFile = 'system/cache/config/modules.php';

		// Try to load from cache
		if (!$GLOBALS['TL_CONFIG']['bypassCache'] && file_exists(TL_ROOT . '/' . $strCacheFile))
		{
			include TL_ROOT . '/' . $strCacheFile;
		}
		else
		{
			$load = array();

			static::$active = array();
			static::$disabled = array();

			// Ignore non-core modules if the system runs in safe mode
			if ($GLOBALS['TL_CONFIG']['coreOnlyMode'])
			{
				$modules = array('core', 'calendar', 'comments', 'devtools', 'faq', 'listing', 'news', 'newsletter', 'repository');
			}
			else
			{
				// Sort the modules (see #6391)
				$modules = scan(TL_ROOT . '/system/modules');
				sort($modules);

				// Load the "core" module first
				array_unshift($modules, 'core');
				$modules = array_unique($modules);
			}

			// Walk through the modules
			foreach ($modules as $file)
			{
				// Ignore dot resources
				if (strncmp($file, '.', 1) === 0)
				{
					continue;
				}

				// Ignore legacy modules
				if (in_array($file, array('backend', 'frontend', 'rep_base', 'rep_client', 'registration', 'rss_reader', 'tpl_editor')))
				{
					continue;
				}

				$path = TL_ROOT . '/system/modules/' . $file;

				// Ignore files
				if (!is_dir($path))
				{
					continue;
				}

				// Ignore disabled module
				if (file_exists($path . '/.skip'))
				{
					static::$disabled[] = $file;
					continue;
				}

				$load[$file] = array();

				// Read the autoload.ini if any
				if (file_exists($path . '/config/autoload.ini'))
				{
					$config = parse_ini_file($path . '/config/autoload.ini', true);
					$load[$file] = $config['requires'];
				}
			}

			// Resolve the dependencies
			while (!empty($load))
			{
				$matched = false;

				foreach ($load as $name=>$requires)
				{
					if (empty($requires))
					{
						$matched = true;
					}
					else
					{
						$matched = count(array_diff($requires, static::$active)) === 0;
					}

					if ($matched === true)
					{
						unset($load[$name]);
						static::$active[] = $name;
					}
				}

				// The dependencies cannot be resolved
				if ($matched === false)
				{
					ob_start();
					dump($load);
					$buffer = ob_get_contents();
					ob_end_clean();

					throw new \UnresolvableDependenciesException("The module dependencies could not be resolved.\n$buffer");
				}
			}
		}
	}
}
