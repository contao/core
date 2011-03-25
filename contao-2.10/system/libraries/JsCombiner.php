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
 * Class JsCombiner
 *
 * This class provides methods to combine JavaScript files.
 * @copyright  Leo Feyer 2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class JsCombiner extends System
{

	/**
	 * Unique key
	 * @var string
	 */
	protected $strKey = '';

	/**
	 * Files
	 * @var array
	 */
	protected $arrFiles = array();


	/**
	 * Add a script
	 * @param string
	 * @param string
	 * @param string
	 */
	public function add($strFile, $strVersion=false)
	{
		if (isset($this->arrFiles[$strFile]))
		{
			return;
		}

		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			throw new Exception("File $strFile does not exist");
		}

		if ($strVersion === false)
		{
			$strVersion = VERSION .'.'. BUILD;
		}

		$arrFile = array
		(
			'name' => $strFile,
			'version' => $strVersion
		);

		$this->arrFiles[$strFile] = $arrFile;
		$this->strKey .= '-f'. $strFile .'-v'. $strVersion;
	}


	/**
	 * Return true if there are style sheets
	 * @return boolean
	 */
	public function hasEntries()
	{
		return !empty($this->arrFiles);
	}


	/**
	 * Generate the combined file and return the path
	 * @return string
	 */
	public function generate()
	{
		$strKey = substr(md5($this->strKey), 0, 12);

		// Load the existing file
		if (file_exists(TL_ROOT .'/system/scripts/'. $strKey .'.js'))
		{
			return TL_SCRIPT_URL .'system/scripts/'. $strKey .'.js';
		}

		// Create the file
		$objFile = new File('system/scripts/'. $strKey .'.js');
		$objFile->truncate();

		foreach ($this->arrFiles as $arrFile)
		{
			$objFile->append(file_get_contents(TL_ROOT .'/'. $arrFile['name']));
		}

		$objFile->close();
		return TL_SCRIPT_URL .'system/scripts/'. $strKey .'.js';
	}
}

?>