<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class DBAdjacencyListTree
 *
 * Provides algorithms to perform common selection operations on hierarchical
 * data in relational databases represented as adjacency list.
 * 
 * @copyright  Oliver Hoff 2011
 * @author     Oliver Hoff <http://www.hofff.com/>
 */
final class DBAdjacencyListTree
{

	private function __construct() {}
	
	/**
	 * Returns the given nodes of the given table in preorder,
	 * optionally removing nested IDs.
	 *  
	 * @param array $arrIDs
	 * @param boolean $blnUnnest
	 * @param string $strTable
	 * @return array
	 */
	public static function getPreorder($arrIDs, $blnUnnest = false, $strTable = 'tl_page') {
		if(!$arrIDs)
			return array();
		
		$arrQueriedIDs = $arrIDs = array_flip(array_map('intval', (array) $arrIDs));
		$arrQueryIDs = array_keys($arrQueriedIDs);
		if(count($arrQueryIDs) < 2)
			return $arrQueryIDs;
			
		$objDB = Database::getInstance();
		$arrTree = array();
		$arrQueriedIDs[0] = true;
		
		while($arrQueryIDs) {
			$objNodes = $objDB->query('
				SELECT	id, pid
				FROM	' . $strTable . '
				WHERE	id IN (' . implode(',', $arrQueryIDs) . ')
				ORDER BY sorting
			');
			
			$arrQueryIDs = array();
			while($objNodes->next()) {
				$arrTree[$objNodes->pid][] = $objNodes->id;
				if(!isset($arrQueriedIDs[$objNodes->pid])) {
					$arrQueryIDs[] = $objNodes->pid;
					$arrQueriedIDs[$objNodes->pid] = true;
				}
			}
		}
		
		$arrReturn = array();
		if($blnUnnest) {
			self::getPreorderHelperUnnest($arrIDs, $arrReturn, $arrTree);
		} else {
			self::getPreorderHelper($arrIDs, $arrReturn, $arrTree);
		}
		
		return $arrReturn;
	}
	
	/**
	 * Inserts the nodes of the subtree of $arrTree starting at $intCurrentNode
	 * into $arrReturn, if they are in $arrNodeIDs, too.
	 * 
	 * @param array $arrNodeIDs
	 * @param array $arrReturn 
	 * @param array $arrTree
	 * @param integer $intCurrentNode
	 */
	private static function getPreorderHelper(array $arrNodeIDs, array &$arrReturn, array $arrTree, $intCurrentNode = 0) {
		foreach($arrTree[$intCurrentNode] as $intID) {
			isset($arrNodeIDs[$intID]) && $arrReturn[] = $intID;
			isset($arrTree[$intID]) && self::getPreorderHelperFilter($arrNodeIDs, $arrReturn, $arrTree, $intID);
		}
	}
	
	/**
	 * Inserts the nodes of the subtree of $arrTree starting at $intCurrentNode
	 * into $arrReturn, if they are in $arrNodeIDs, too.
	 * 
	 * @param array $arrNodeIDs
	 * @param array $arrReturn 
	 * @param array $arrTree
	 * @param integer $intCurrentNode
	 */
	private static function getPreorderHelperUnnest(array $arrNodeIDs, array &$arrReturn, array $arrTree, $intCurrentNode = 0) {
		foreach($arrTree[$intCurrentNode] as $intID) {
			if(isset($arrNodeIDs[$intID])) {
				$arrReturn[] = $intID;
			} elseif(isset($arrTree[$intID])) {
				self::getPreorderHelperFilterUnnest($arrNodeIDs, $arrReturn, $arrTree, $intID);
			}
		}
	}

	/**
	 * Get all descendants of the given nodes of the given table, optionally
	 * including the given nodes itself (the ones, which are not already a
	 * descendant of another given node). There are no duplicates in the
	 * returned result.
	 * 
	 * @param array $arrIDs
	 * @param boolean $blnIncludeSelf
	 * @param string $strTable
	 * @return array
	 */
	public static function getDescendants($arrIDs, $blnIncludeSelf = false, $strTable = 'tl_page') {
		if(!$arrIDs)
			return array();
			
		$arrQueriedIDs = array_flip(array_map('intval', (array) $arrIDs));
		$arrQueryIDs = array_keys($arrQueriedIDs);
		$arrDescendants = $blnIncludeSelf ? $arrQueryIDs : array();
		$objDB = Database::getInstance();
		
		while($arrQueryIDs) {
			$objNodes = $objDB->query('
				SELECT	id
				FROM	' . $strTable . '
				WHERE	pid IN (' . implode(',', $arrQueryIDs) . ')
			');
			
			$arrQueryIDs = array();
			while($objNodes->next()) {
				if(!isset($arrQueriedIDs[$objNodes->id])) {
					$arrDescendants[] = $objNodes->id;
					$arrQueryIDs[] = $objNodes->id;
					$arrQueriedIDs[$objNodes->id] = true;
				}
			}
		}
		
		return $arrDescendants;
	}

	/**
	 * Returns the descendants of the each of the given nodes of the given table
	 * in preorder, optionally adding the given node themselves.
	 * Duplicates are not removed and nested nodes are not removed. Use
	 * Controller::getPreorder(..) with $blnUnnest set to true before calling
	 * this function, if this is the desired behavior.
	 * 
	 * @param array $arrIDs
	 * @param boolean $blnIncludeSelf
	 * @param string $strTable
	 * 
	 * @return array
	 */
	public static function getDescendantsPreorder($arrIDs, $blnIncludeSelf = false, $strTable = 'tl_page') {
		if(!$arrIDs)
			return array();
		
		$arrIDs = array_map('intval', (array) $arrIDs);
		$arrQueriedIDs = array_flip($arrIDs);
		$arrQueryIDs = array_keys($arrQueriedIDs);
		$objDB = Database::getInstance();
		$arrTree = array();
		
		while($arrQueryIDs) {
			$objNodes = $objDB->query('
				SELECT	id, pid
				FROM	' . $strTable . '
				WHERE	pid IN (' . implode(',', $arrQueryIDs) . ')
				ORDER BY sorting'
			);
			
			$arrQueryIDs = array();
			while($objNodes->next()) {
				$arrTree[$objNodes->pid][] = $objNodes->id;
				if(!isset($arrQueriedIDs[$objNodes->id])) {
					$arrQueryIDs[] = $objNodes->id;
					$arrQueriedIDs[$objNodes->id] = true;
				}
			}
		}
		
		$arrReturn = array();
		if($blnIncludeSelf) {
			foreach($arrIDs as $intID) {
				$arrReturn[] = $intID;
				self::getDescendantsPreorderHelper($arrReturn, $arrTree, $intID);
			}
		} else {
			foreach($arrIDs as $intID) {
				self::getDescendantsPreorderHelper($arrReturn, $arrTree, $intID);
			}
		}
		
		return $arrReturn;
	}
	
	/**
	 * Inserts the nodes of the subtree of $arrTree starting at $intCurrentNode
	 * into $arrReturn.
	 * 
	 * @param array $arrReturn 
	 * @param array $arrTree
	 * @param integer|mixed $intCurrentNode
	 */
	private static function getDescendantsPreorderHelper(array &$arrReturn, array $arrTree, $intCurrentNode = 0) {
		foreach($arrTree[$intCurrentNode] as $intID) {
			$arrReturn[] = $intID;
			isset($arrTree[$intID]) && self::getPreorderHelper($arrNodeIDs, $arrReturn, $arrTree, $intID);
		}
	}

}
