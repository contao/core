<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao\Filter;


/**
 * Filters a directory for .sql files
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
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
