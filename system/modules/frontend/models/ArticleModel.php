<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ArticleModel
 *
 * Provide methods to find and save articles.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
class ArticleModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_article';


	/**
	 * Find an article by its ID or alias and include the author name
	 * @param mixed
	 * @param integer
	 * @return Model
	 */
	public static function findOneWithAuthor($varId, $intPid)
	{
		$objArticle = \Database::getInstance()->prepare("SELECT *, author AS authorId, (SELECT name FROM tl_user WHERE id=author) AS author FROM tl_article WHERE (id=? OR alias=?)" . ($intPid ? " AND pid=?" : ""))
											  ->limit(1)
											  ->execute((is_numeric($varId) ? $varId : 0), $varId, $intPid);

		if ($objArticle->numRows < 1)
		{
			return null;
		}

		return new static($objArticle);
	}


	/**
	 * Find all published articles by their parent ID and column
	 * @param integer
	 * @param string
	 * @return Model
	 */
	public static function findPublishedByPidAndColumn($intPid, $strColumn)
	{
		$time = time();

		$objArticles = \Database::getInstance()->prepare("SELECT * FROM tl_article WHERE pid=? AND inColumn=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
											   ->execute($intPid, $strColumn);

		if ($objArticles->numRows < 1)
		{
			return null;
		}

		return new static($objArticles);
	}
}

?>