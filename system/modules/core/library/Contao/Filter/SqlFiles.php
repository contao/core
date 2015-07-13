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
 * Filters a directory for .sql files
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class SqlFiles extends \RecursiveFilterIterator
{

	/**
	 * Check whether the current element of the iterator is acceptable
	 *
	 * @return boolean True if the element is acceptable
	 */
	public function accept()
	{
		if ($this->hasChildren())
		{
			return true;
		}

		$strExtension = pathinfo($this->current()->getFilename(), PATHINFO_EXTENSION);

		if ($strExtension == 'sql')
		{
			return true;
		}

		return false;
	}
}
