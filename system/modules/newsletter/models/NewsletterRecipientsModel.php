<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Newsletter
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes newsletter recipients
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class NewsletterRecipientsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_newsletter_recipients';


	/**
	 * Find recipients by their e-mail address and parent ID
	 * 
	 * @param string $strEmail   The e-mail address
	 * @param array  $arrPids    An array of newsletter channel IDs
	 * @param array  $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no recipients
	 */
	public static function findByEmailAndPids($strEmail, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.email=? AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")"), $strEmail, $arrOptions);
	}
}
