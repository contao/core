<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Filter;


/**
 * Filters a directory listing
 *
 * The class filters dot files and folders, which are excluded from
 * being synchronized, from a directory listing.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class SyncExclude extends \RecursiveFilterIterator
{

	/**
	 * Exempted folders
	 * @var array
	 */
	protected $arrExempt = array();


	/**
	 * Exempt folders from the synchronisation (see #4522)
	 *
	 * @param \RecursiveIterator $iterator The iterator object
	 */
	public function __construct(\RecursiveIterator $iterator)
	{
		if (\Config::get('fileSyncExclude') != '')
		{
			$this->arrExempt = array_map(function($e) {
				return \Config::get('uploadPath') . '/' . $e;
			}, trimsplit(',', \Config::get('fileSyncExclude')));
		}

		parent::__construct($iterator);
	}


	/**
	 * Check whether the current element of the iterator is acceptable
	 *
	 * @return boolean True if the element is acceptable
	 */
	public function accept()
	{
		// The resource is to be ignored
		if (strncmp($this->current()->getFilename(), '.', 1) === 0)
		{
			return false;
		}

		$strRelpath = str_replace(TL_ROOT . '/', '', $this->current()->getPathname());

		// The resource is an exempt folder
		if (in_array($strRelpath, $this->arrExempt))
		{
			return false;
		}

		// The resource is accepted
		return true;
	}
}
