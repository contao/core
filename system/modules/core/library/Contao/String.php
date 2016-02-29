<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


if (version_compare(PHP_VERSION, '7.0', '>=')) {
	throw new \RuntimeException(
		'The String class cannot be used in PHP ' . PHP_VERSION . '. Use the StringUtil class instead.'
	);
}


/**
 * Provides a String class for backwards compatibility.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 *
 * @deprecated Use the StringUtil class instead
 */
class String extends \StringUtil
{

	/**
	 * Object instance (Singleton)
	 * @var \StringUtil
	 */
	protected static $objInstance;


	/**
	 * Prevent direct instantiation (Singleton)
	 *
	 * @deprecated String is now a static class
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 *
	 * @deprecated String is now a static class
	 */
	final public function __clone() {}


	/**
	 * Return the object instance (Singleton)
	 *
	 * @return \String The object instance
	 *
	 * @deprecated String is now a static class
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
