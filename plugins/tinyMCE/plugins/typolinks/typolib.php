<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Plugins
 * @license    LGPL
 * @filesource
 */


/**
 * Class typolib
 * 
 * Provide methods to render TinyMCE page and file drop down menus.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class typolib extends Controller
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();
		$this->import('Database');
	}


	/**
	 * Get all allowed pages and return them as string
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public function createPageList($intId=0, $level=-1)
	{
		// Show all pages under the root page that the mounted page belongs to
		if ($intId === 0 && !$this->User->isAdmin)
		{
			$return = '';
			$processed = array();

			foreach ($this->eliminateNestedPages($this->User->pagemounts) as $page)
			{
				if (in_array($page, $processed))
				{
					continue;
				}

				$processed[] = $page;
				$objPage = $this->getPageDetails($page);

				$return .= '<optgroup label="' . $objPage->rootTitle . '">' . $this->createPageList($objPage->rootId, $level) . '</optgroup>';
			}

			return $return;
		}

		// Add child pages
		$objPages = $this->Database->prepare("SELECT id, title, type FROM tl_page WHERE pid=? ORDER BY sorting")
								   ->execute($intId);

		if ($objPages->numRows < 1)
		{
			return '';
		}

		++$level;
		$strOptions = '';

		while ($objPages->next())
		{
			if ($objPages->type == 'root')
			{
				$strOptions .= '<optgroup label="' . $objPages->title . '">';
				$strOptions .= $this->createPageList($objPages->id, -1);
				$strOptions .= '</optgroup>';
			}
			else
			{
				$strOptions .= sprintf('<option value="{{link_url::%s}}">%s%s</option>', $objPages->id, str_repeat(" &nbsp; &nbsp; ", $level), specialchars($objPages->title));
				$strOptions .= $this->createPageList($objPages->id, $level);
			}
		}

		return $strOptions;
	}


	/**
	 * Get all allowed files and return them as string
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public function createFileList($strFolder=null, $level=-1)
	{
		if (is_null($strFolder))
		{
			// Limit nodes to the filemounts of the user
			if (!$this->User->isAdmin)
			{
				$return = '';

				foreach ($this->eliminateNestedPaths($this->User->filemounts) as $path)
				{
					$return .= $this->createFileList($path);
				}

				return $return;
			}

			// Allow all nodes for administrators
			$strFolder = $GLOBALS['TL_CONFIG']['uploadPath'];
		}

		$arrPages = scan(TL_ROOT . '/' . $strFolder);

		if (count($arrPages) < 1)
		{
			return '';
		}

		++$level;
		$strFolders = '';
		$strFiles = '';

		// Recursively list all files and folders
		foreach ($arrPages as $strFile)
		{
			if (substr($strFile, 0, 1) == '.')
			{
				continue;
			}

			// Folders
			if (is_dir(TL_ROOT . '/' . $strFolder . '/' . $strFile))
			{
				$strFolders .=  $this->createFileList($strFolder . '/' . $strFile, $level);
			}

			// Files
			elseif ($strFile != 'meta.txt')
			{
				$strFiles .= sprintf('<option value="%s">%s</option>', $strFolder . '/' . $strFile, specialchars($strFile));
			}
		}

		if (strlen($strFiles))
		{
			return '<optgroup label="' . specialchars($strFolder) . '">' . $strFiles . $strFolders . '</optgroup>';
		}

		return $strFiles . $strFolders;
	}


	/**
	 * Get all allowed images and return them as string
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public function createImageList($strFolder=null, $level=-1)
	{
		if (is_null($strFolder))
		{
			// Limit nodes to the filemounts of the user
			if (!$this->User->isAdmin)
			{
				$return = '';

				foreach ($this->eliminateNestedPaths($this->User->filemounts) as $path)
				{
					$return .= $this->createImageList($path);
				}

				return $return;
			}

			// Allow all nodes for administrators
			$strFolder = $GLOBALS['TL_CONFIG']['uploadPath'];
		}

		$arrPages = scan(TL_ROOT . '/' . $strFolder);

		if (count($arrPages) < 1)
		{
			return '';
		}

		++$level;
		$strFolders = '';
		$strFiles = '';

		// Recursively list all images
		foreach ($arrPages as $strFile)
		{
			if (substr($strFile, 0, 1) == '.')
			{
				continue;
			}

			if (is_dir(TL_ROOT . '/' . $strFolder . '/' . $strFile))
			{
				$strFolders .= $this->createImageList($strFolder . '/' . $strFile, $level);
			}

			elseif (preg_match('/\.gif$|\.jpg$|\.jpeg$|\.png$/i', $strFile))
			{
				$strFiles .= sprintf('["%s", "%s"]', specialchars($strFolder . '/' . $strFile), $strFolder . '/' . $strFile) . ",\n";
			}
		}

		return $strFiles . $strFolders;
	}


	/**
	 * Get all tiny_ templates and return them as string
	 * @return string
	 */
	public function createTemplateList()
	{
		$dir = TL_ROOT . '/' . $GLOBALS['TL_CONFIG']['uploadPath'] . '/tiny_templates';

		if (!is_dir($dir))
		{
			return '';
		}

		$strFiles = '';
		$arrTemplates = scan($dir);

		foreach ($arrTemplates as $strFile)
		{
			if (strncmp('.', $strFile, 1) !== 0 && is_file($dir . '/' . $strFile))
			{
				$strFiles .= sprintf('["%s", "' . TL_PATH . '/' . $GLOBALS['TL_CONFIG']['uploadPath'] . '/tiny_templates/%s", "' . $GLOBALS['TL_CONFIG']['uploadPath'] . '/tiny_templates/%s"]', $strFile, $strFile, $strFile) . ",\n";
			}
		}

		return $strFiles;
	}
}

?>