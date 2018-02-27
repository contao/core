<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
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

		if ($this->current()->getExtension() == 'sql')
		{
			return true;
		}

		return false;
	}
}
