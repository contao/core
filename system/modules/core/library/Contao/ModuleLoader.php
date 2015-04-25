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
 * @author Leo Feyer <https://github.com/leofeyer>
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
		if (!\Config::get('bypassCache') && file_exists(TL_ROOT . '/' . $strCacheFile))
		{
			include TL_ROOT . '/' . $strCacheFile;
		}
		else
		{
			$load = array();

			static::$active = array();
			static::$disabled = array();

			// Ignore non-core modules if the system runs in safe mode
			if (\Config::get('coreOnlyMode'))
			{
				$modules = array('core', 'calendar', 'comments', 'devtools', 'faq', 'listing', 'news', 'newsletter', 'repository');
			}
			else
			{
				// Sort the modules (see #6391)
				$modules = scan(TL_ROOT . '/system/modules');
				sort($modules);

				// Filter dot resources, files and legacy modules
				foreach ($modules as $k=>$v)
				{
					if (strncmp($v, '.', 1) === 0)
					{
						unset($modules[$k]);
					}
					elseif (!is_dir(TL_ROOT . '/system/modules/' . $v))
					{
						unset($modules[$k]);
					}
					elseif (in_array($v, array('backend', 'frontend', 'rep_base', 'rep_client', 'registration', 'rss_reader', 'tpl_editor')))
					{
						unset($modules[$k]);
					}
				}

				// Load the "core" module first
				array_unshift($modules, 'core');
				$modules = array_unique($modules);
			}

			// Filter disabled modules
			foreach ($modules as $k=>$v)
			{
				if (file_exists(TL_ROOT . '/system/modules/' . $v . '/.skip'))
				{
					unset($modules[$k]);
					static::$disabled[] = $v;
				}
			}

			// Walk through the modules
			foreach ($modules as $module)
			{
				$load[$module] = array();
				$path = TL_ROOT . '/system/modules/' . $module;

				// Read the autoload.ini if any
				if (file_exists($path . '/config/autoload.ini'))
				{
					$config = parse_ini_file($path . '/config/autoload.ini', true);
					$load[$module] = $config['requires'] ?: array();

					foreach ($load[$module] as $k=>$v)
					{
						// Optional requirements (see #6835)
						if (strncmp($v, '*', 1) === 0)
						{
							$key = substr($v, 1);

							if (!in_array($key, $modules))
							{
								unset($load[$module][$k]);
							}
							else
							{
								$load[$module][$k] = $key;
							}
						}
					}
				}
			}

			// Resolve the dependencies
			while (!empty($load))
			{
				$failed = true;

				foreach ($load as $name=>$requires)
				{
					if (empty($requires))
					{
						$resolved = true;
					}
					else
					{
						$resolved = count(array_diff($requires, static::$active)) === 0;
					}

					if ($resolved === true)
					{
						unset($load[$name]);
						static::$active[] = $name;
						$failed = false;
					}
				}

				// The dependencies cannot be resolved
				if ($failed === true)
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
