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
 * A static class to store non-persistent data
 *
 * The class functions as a global cache container where you can store data
 * that is reused by the application. The cache content is not persisted, so
 * once the process is completed, the data is gone.
 *
 * Usage:
 *
 *     public function getResult()
 *     {
 *         if (!Cache::has('result'))
 *         {
 *             Cache::set('result') = $this->complexMethod();
 *         }
 *         return Cache::get('result');
 *     }
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Cache
{

	/**
	 * Object instance (Singleton)
	 * @var \Cache
	 */
	protected static $objInstance;

	/**
	 * The cache data
	 * @var array
	 */
	protected static $arrData = array();


	/**
	 * Check whether a key is set
	 *
	 * @param string $strKey The cache key
	 *
	 * @return boolean True if the key is set
	 */
	public static function has($strKey)
	{
		return isset(static::$arrData[$strKey]);
	}


	/**
	 * Return a cache entry
	 *
	 * @param string $strKey The cache key
	 *
	 * @return mixed The cached data
	 */
	public static function get($strKey)
	{
		return static::$arrData[$strKey];
	}


	/**
	 * Add a cache entry
	 *
	 * @param string $strKey   The cache key
	 * @param mixed  $varValue The data to be cached
	 */
	public static function set($strKey, $varValue)
	{
		static::$arrData[$strKey] = $varValue;
	}


	/**
	 * Remove a cache entry
	 *
	 * @param string $strKey The cache key
	 */
	public static function remove($strKey)
	{
		unset(static::$arrData[$strKey]);
	}


	/**
	 * Prevent direct instantiation (Singleton)
	 *
	 * @deprecated Cache is now a static class
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 *
	 * @deprecated Cache is now a static class
	 */
	final public function __clone() {}


	/**
	 * Check whether a key is set
	 *
	 * @param string $strKey The cache key
	 *
	 * @return boolean True if the key is set
	 *
	 * @deprecated Use Cache::has() instead
	 */
	public function __isset($strKey)
	{
		return static::has($strKey);
	}


	/**
	 * Return a cache entry
	 *
	 * @param string $strKey The cache key
	 *
	 * @return mixed|null The cached data
	 *
	 * @deprecated Use Cache::get() instead
	 */
	public function __get($strKey)
	{
		if (static::has($strKey))
		{
			return static::get($strKey);
		}

		return null;
	}


	/**
	 * Add a cache entry
	 *
	 * @param string $strKey   The cache key
	 * @param mixed  $varValue The data to be stored
	 *
	 * @deprecated Use Cache::set() instead
	 */
	public function __set($strKey, $varValue)
	{
		static::set($strKey, $varValue);
	}


	/**
	 * Remove a cache entry
	 *
	 * @param string $strKey The cache key
	 *
	 * @deprecated Use Cache::remove() instead
	 */
	public function __unset($strKey)
	{
		static::remove($strKey);
	}


	/**
	 * Instantiate the cache object (Factory)
	 *
	 * @return \Cache The object instance
	 *
	 * @deprecated Cache is now a static class
	 */
	public static function getInstance()
	{
		if (static::$objInstance === null)
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}
}
