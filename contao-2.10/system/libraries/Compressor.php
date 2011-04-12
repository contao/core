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
 * Class Compressor
 *
 * This class provides methods to compress HTML files.
 * @copyright  Leo Feyer 2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Compressor extends System
{

	/**
	 * Minify HTML markup preserving pre, script, style and textarea tags
	 * @param string
	 * @return string
	 */
	public function minifyHtml($strHtml)
	{
		// Split the markup based on the tags that shall be preserved
		$arrChunks = preg_split('@(</?pre[^>]*>)|(</?script[^>]*>)|(</?style[^>]*>)|(</?textarea[^>]*>)@i', $strHtml, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		$strHtml = '';
		$blnPreserveNext = false;
		$blnOptimizeNext = false;

		// Recombine the markup
		foreach ($arrChunks as $strChunk)
		{
			if (strncasecmp($strChunk, '<pre', 4) === 0 || strncasecmp($strChunk, '<textarea', 9) === 0)
			{
				$blnPreserveNext = true;
			}
			elseif (strncasecmp($strChunk, '<script', 7) === 0 || strncasecmp($strChunk, '<style', 6) === 0)
			{
				$blnOptimizeNext = true;
			}
			elseif ($blnPreserveNext)
			{
				$blnPreserveNext = false;
			}
			elseif ($blnOptimizeNext)
			{
				$blnOptimizeNext = false;
				$strChunk = $this->minifyInlineScript($strChunk);
				$strChunk = trim($strChunk);
			}
			else
			{
				$arrReplace = array
				(
					'/\n ?\n+/'      => "\n",   // Convert multiple line-breaks
					'/^[\t ]+</m'    => '<',    // Remove tag indentation
					'/>( )?\n</'     => '>$1<', // Remove line-breaks between tags
					'/\n/'           => '',     // Remove all remaining line-breaks
					'/ <\/(div|p)>/' => '</$1>' // Remove spaces before closing DIV and P tags
				);

				$strChunk = str_replace("\r", '', $strChunk);
				$strChunk = preg_replace(array_keys($arrReplace), array_values($arrReplace), $strChunk);
				$strChunk = trim($strChunk);
			}

			$strHtml .= $strChunk;
		}

		return $strHtml;
	}


	/**
	 * Remove not required whitespace characters from inline scripts
	 * @param string
	 * @return string
	 */
	public function minifyInlineScript($strCode)
	{
		return preg_replace('/[ \n\t]*(;|=|\{|\}|&&|,|<|>|\',|",|\':|":|\|\|)[ \n\t]*/', '$1', $strCode);
	}
}

?>