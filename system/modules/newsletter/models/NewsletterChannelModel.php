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
 * Reads and writes newsletter channels
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class NewsletterChannelModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_newsletter_channel';


	/**
	 * Find multiple newsletter channels by their IDs
	 *
	 * @param array $arrIds     An array of newsletter channel IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no newsletter channels
	 */
	public static function findByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.title";
		}

		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
	}
}
