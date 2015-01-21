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
 * Sort iterator items ascending
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class SortedIterator extends \SplHeap
{

	/**
	 * Insert the elements
	 *
	 * @param \Iterator $iterator
	 */
	public function __construct(\Iterator $iterator)
	{
		foreach ($iterator as $item)
		{
			$this->insert($item);
		}
	}


	/**
	 * Sort items ascending
	 *
	 * @param mixed $a The first SplFileInfo object
	 * @param mixed $b The second SplFileInfo object
	 *
	 * @return integer Negative value if $b is less than $a, positive value if $b is greater than $a or 0 if they are equal
	 */
	public function compare($a, $b)
	{
		return strcmp($b->getRealpath(), $a->getRealpath());
	}
}
