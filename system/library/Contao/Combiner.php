<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

use \File, \System, \Exception;


/**
 * Class Combiner
 *
 * This class provides methods to combine CSS and JavaScript files.
 * @copyright  Leo Feyer 2011-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Combiner extends System
{

	/**
	 * Constants
	 */
	const CSS = '.css';
	const JS = '.js';

	/**
	 * Unique key
	 * @var string
	 */
	protected $strKey = '';

	/**
	 * Operation mode
	 * @var string
	 */
	protected $strMode = null;

	/**
	 * Files
	 * @var array
	 */
	protected $arrFiles = array();


	/**
	 * Add a file
	 * @param string
	 * @param string
	 * @param string
	 * @throws \Exception
	 */
	public function add($strFile, $strVersion=null, $strMedia='screen')
	{
		$strType = strrchr($strFile, '.');

		// Check the file type
		if ($strType != self::CSS && $strType != self::JS)
		{
			throw new Exception("Invalid file $strFile");
		}

		// Set the operation mode
		if (!$this->strMode)
		{
			$this->strMode = $strType;
		}
		elseif ($this->strMode != $strType)
		{
			throw new Exception('You cannot mix different file types. Create another Combiner object instead.');
		}

		// Prevent duplicates
		if (isset($this->arrFiles[$strFile]))
		{
			return;
		}

		// Check the source file
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			if ($this->strMode == self::JS)
			{
				throw new Exception("File $strFile does not exist");
			}
			else
			{
				$this->import('StyleSheets');
				$this->StyleSheets->updateStyleSheets();

				// Retry
				if (!file_exists(TL_ROOT . '/' . $strFile))
				{
					throw new Exception("File $strFile does not exist");
				}
			}
		}

		// Default version
		if ($strVersion === null)
		{
			$strVersion = VERSION .'.'. BUILD;
		}

		// Store the file
		$arrFile = array
		(
			'name' => $strFile,
			'version' => $strVersion,
			'media' => $strMedia
		);

		$this->arrFiles[$strFile] = $arrFile;
		$this->strKey .= '-f' . $strFile . '-v' . $strVersion . '-m' . $strMedia;
	}


	/**
	 * Add multiple files from an array
	 * @param array
	 * @param string
	 * @param string
	 */
	public function addMultiple(Array $arrFiles, $strVersion=null, $strMedia='screen')
	{
		foreach ($arrFiles as $strFile)
		{
			$this->add($strFile, $strVersion, $strMedia);
		}
	}


	/**
	 * Return true if there are files
	 * @return boolean
	 */
	public function hasEntries()
	{
		return !empty($this->arrFiles);
	}


	/**
	 * Generate the combined file and return the path
	 * @param string
	 * @return string
	 */
	public function getCombinedFile($strUrl=null)
	{
		if ($strUrl === null)
		{
			$strUrl = TL_SCRIPT_URL;
		}

		$strTarget = substr($this->strMode, 1);
		$strKey = substr(md5($this->strKey), 0, 12);

		// Load the existing file
		if (file_exists(TL_ROOT . '/assets/' . $strTarget . '/' . $strKey . $this->strMode))
		{
			return $strUrl . 'assets/' . $strTarget . '/' . $strKey . $this->strMode;
		}

		// Create the file
		$objFile = new File('assets/' . $strTarget . '/' . $strKey . $this->strMode);
		$objFile->truncate();

		foreach ($this->arrFiles as $arrFile)
		{
			$content = file_get_contents(TL_ROOT . '/' . $arrFile['name']);

			// HOOK: modify the file content
			if (isset($GLOBALS['TL_HOOKS']['getCombinedFile']) && is_array($GLOBALS['TL_HOOKS']['getCombinedFile']))
			{
				foreach ($GLOBALS['TL_HOOKS']['getCombinedFile'] as $callback)
				{
					$this->import($callback[0]);
					$content = $this->$callback[0]->$callback[1]($content, $strKey, $this->strMode, $arrFile);
				}
			}

			// Handle style sheets
			if ($this->strMode == self::CSS)
			{
				// Adjust the file paths
				$strDirname = dirname($arrFile['name']);

				// Remove relative paths
				while (strpos($content, 'url("../') !== false)
				{
					$strDirname = dirname($strDirname);
					$content = str_replace('url("../', 'url("', $content);
				}

				$strGlue = ($strDirname != '.') ? $strDirname . '/' : '';
				$content = preg_replace('/url\("(?!(data:|https?:\/\/|\/))/', 'url("../../' . $strGlue, $content);
				$content = '@media ' . ($arrFile['media'] ?: 'all') . "{\n" . $content . "\n}";
			}

			$objFile->append($content);
		}

		unset($content);
		$objFile->close();

		// Create a gzipped version
		if ($GLOBALS['TL_CONFIG']['gzipScripts'] && function_exists('gzencode'))
		{
			$objFile = new File('assets/' . $strTarget . '/' . $strKey . $this->strMode . '.gz');
			$objFile->write(gzencode(file_get_contents(TL_ROOT . '/assets/' . $strTarget . '/' . $strKey . $this->strMode), 9));
			$objFile->close();
		}

		return $strUrl . 'assets/' . $strTarget . '/' . $strKey . $this->strMode;
	}
}
