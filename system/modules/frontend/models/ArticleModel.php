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
	public static function findByIdOrAliasAndColumn($varId, $intPid)
	{
		$arrColumns = array('(id=? OR alias=?)');
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if ($intPid)
		{
			$arrColumns[] = 'pid=?';
			$arrValues[] = $intPid;
		}

		return static::findOneBy($arrColumns, $arrValues);
	}


	/**
	 * Find a published article by its ID
	 * @param integer
	 * @return Model
	 */
	public static function findPublishedById($intId)
	{
		$arrColumns = array('id=?');

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "(start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1";
		}

		return static::findOneBy($arrColumns, $intId);
	}


	/**
	 * Find all published articles by their parent ID and column
	 * @param integer
	 * @param string
	 * @return Model
	 */
	public static function findPublishedByPidAndColumn($intPid, $strColumn)
	{
		$arrColumns = array('pid=? AND inColumn=?');
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "(start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1";
		}

		return static::findBy($arrColumns, $arrValues, 'sorting');
	}


	/**
	 * Return the author of an article
	 * @return Model
	 */
	public function getAuthor()
	{
		if ($this->author === null)
		{
			return null;
		}

		$objAuthor = \MemberModel::findByPk($this->author);

		if ($objAuthor === null)
		{
			return null;
		}

		return $objAuthor;
	}


	/**
	 * Return the page of an article
	 * @return Model
	 */
	public function getPage()
	{
		if ($this->pid === null)
		{
			return null;
		}

		$objPage = \PageModel::findPublishedById($this->pid);

		if ($objPage === null)
		{
			return null;
		}

		return $objPage;
	}
}

?>