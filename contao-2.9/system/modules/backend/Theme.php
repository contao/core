<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Theme
 *
 * Provide methods to handle themes.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Theme extends Backend
{

	/**
	 * Import a theme
	 */
	public function importTheme()
	{
		// Import the themes
		if ($this->Input->post('FORM_SUBMIT') == 'tl_theme_import')
		{
			if (!$this->Input->post('source') || !is_array($this->Input->post('source')))
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

			foreach ($this->Input->post('source') as $strZipFile)
			{
				// Folders cannot be imported
				if (is_dir(TL_ROOT . '/' . $strZipFile))
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['importFolder'], basename($strZipFile));
					continue;
				}

				$objFile = new File($strZipFile);

				// Check the file extension
				if ($objFile->extension != 'zip')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}

				// Open the archive
				$objArchive = new ZipReader($strZipFile);

				// Extract all files
				while ($objArchive->next())
				{
					// Limit file operations to system/tmp, tl_files and the templates directory
					if (strncmp($objArchive->file_name, 'system/tmp/', 11) !== 0 && strncmp($objArchive->file_name, 'tl_files/', 9) !== 0 && strncmp($objArchive->file_name, 'templates/', 10) !== 0)
					{
						$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['invalidFile'], $objArchive->file_name);
					}

					// Extract the files
					try
					{
						$strFileName = $objArchive->file_name;

						// Override the files directory
						if ($GLOBALS['TL_CONFIG']['uploadPath'] != 'tl_files' && strncmp($objArchive->file_name, 'tl_files/', 9) === 0)
						{
							$strFileName = str_replace('tl_files/', $GLOBALS['TL_CONFIG']['uploadPath'] . '/', $objArchive->file_name);
						}

						$objFile = new File($strFileName);
						$objFile->write($objArchive->unzip());
						$objFile->close();
					}
					catch (Exception $e)
					{
						$_SESSION['TL_ERROR'][] = $e->getMessage();
					}
				}

				// Open the XML file
				$xml = new DOMDocument();
				$xml->preserveWhiteSpace = false;
				$xml->load(TL_ROOT .'/system/tmp/'. str_replace('.zip', '', basename($strZipFile)) .'.xml');
				$tables = $xml->getElementsByTagName('table');
				$arrMapper = array();

				// Lock the tables
				$this->Database->query("LOCK TABLES tl_theme WRITE, tl_module WRITE, tl_style_sheet WRITE, tl_style WRITE, tl_layout WRITE");

				// Get the current auto_increment values
				$tl_theme = $this->Database->query("SELECT MAX(id) AS id FROM tl_theme")->id;
				$tl_style_sheet = $this->Database->query("SELECT MAX(id) AS id FROM tl_style_sheet")->id;
				$tl_style = $this->Database->query("SELECT MAX(id) AS id FROM tl_style")->id;
				$tl_module = $this->Database->query("SELECT MAX(id) AS id FROM tl_module")->id;
				$tl_layout = $this->Database->query("SELECT MAX(id) AS id FROM tl_layout")->id;

				// Loop through the tables
				for ($i=0; $i<$tables->length; $i++)
				{
					$rows = $tables->item($i)->childNodes;
					$table = $tables->item($i)->getAttribute('name');

					// Table names do not include spaces and special characters
					$table = preg_replace('/[^A-Za-z0-9_]+/', '', $table);

					// Skip invalid tables
					if ($table != 'tl_theme' && $table != 'tl_style_sheet' && $table != 'tl_style' && $table != 'tl_module' && $table != 'tl_layout')
					{
						continue;
					}

					// Loop through the rows
					for ($j=0; $j<$rows->length; $j++)
					{
						$set = array();
						$fields = $rows->item($j)->childNodes;

						// Loop through the fields
						for ($k=0; $k<$fields->length; $k++)
						{
							$value = $fields->item($k)->nodeValue;
							$name = $fields->item($k)->getAttribute('name');

							// Increment the ID
							if ($name == 'id')
							{
								$id = ++${$table};
								$arrMapper[$table][$value] = $id;
								$value = $id;
							}

							// Increment the parent IDs
							elseif ($name == 'pid')
							{
								if ($table == 'tl_style')
								{
									$value = $arrMapper['tl_style_sheet'][$value];
								}
								else
								{
									$value = $arrMapper['tl_theme'][$value];
								}
							}

							// Adjust the style sheet IDs of the page layout
							elseif ($table == 'tl_layout' && $name == 'stylesheet')
							{
								$stylesheets = deserialize($value);

								if (is_array($stylesheets))
								{
									foreach (array_keys($stylesheets) as $key)
									{
										$stylesheets[$key] = $arrMapper['tl_style_sheet'][$stylesheets[$key]];
									}

									$value = serialize($stylesheets);
								}
							}

							// Adjust the module IDs of the page layout
							elseif ($table == 'tl_layout' && $name == 'modules')
							{
								$modules = deserialize($value);

								if (is_array($modules))
								{
									foreach (array_keys($modules) as $key)
									{
										if ($modules[$key]['mod'] > 0)
										{
											$modules[$key]['mod'] = $arrMapper['tl_module'][$modules[$key]['mod']];
										}
									}

									$value = serialize($modules);
								}
							}

							// Adjust the names
							elseif ($name == 'name' && ($table == 'tl_theme' || $table == 'tl_style_sheet'))
							{
								$objCount = $this->Database->prepare("SELECT COUNT(*) AS total FROM ". $table ." WHERE name=?")
														   ->execute($value);

								if ($objCount->total > 0)
								{
									$value = preg_replace('/( |\-)[0-9]+$/', '', $value);
									$value .= (($table == 'tl_style_sheet') ? '-' : ' ') . ${$table};
								}
							}

							// Handle fallback fields
							elseif ($name == 'fallback')
							{
								$value = '';
							}

							// Skip NULL values
							elseif ($value == 'NULL')
							{
								continue;
							}

							$set[$name] = $value;
						}

						// Update the datatbase
						$this->Database->prepare("INSERT INTO ". $table ." %s")->set($set)->execute();
					}
				}

				// Unlock the tables
				$this->Database->query("UNLOCK TABLES");

				// Update the style sheets
				$this->import('StyleSheets');
				$this->StyleSheets->updateStyleSheets();

				// Notify the user
				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_theme']['theme_imported'], basename($strZipFile));
			}

			// Redirect
			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->redirect(str_replace('&key=importTheme', '', $this->Environment->request));
		}

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_theme']['fields']['source'], 'source', null, 'source', 'tl_theme'));

		// Return the form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=importTheme', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_theme']['importTheme'][1].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request, true).'" id="tl_theme_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_theme_import" />

<div class="tl_tbox block">
  <h3><label for="source">'.$GLOBALS['TL_LANG']['tl_theme']['source'][0].'</label> <a href="contao/files.php" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['fileManager']) . '" onclick="Backend.getScrollOffset(); Backend.openWindow(this, 750, 500); return false;">' . $this->generateImage('filemanager.gif', $GLOBALS['TL_LANG']['MSC']['fileManager'], 'style="vertical-align:text-bottom;"') . '</a></h3>'.$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_theme']['source'][1]) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG']['tl_theme']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_theme']['importTheme'][0]).'" />
</div>

</div>
</form>';
	}


	/**
	 * Export a theme
	 * @param object
	 */
	public function exportTheme(DataContainer $dc)
	{
		// Get the theme meta data
		$objTheme = $this->Database->prepare("SELECT * FROM tl_theme WHERE id=?")
								   ->limit(1)
								   ->execute($dc->id);

		if ($objTheme->numRows < 1)
		{
			return;
		}

		// Romanize the name
		$strName = utf8_romanize($objTheme->name);
		$strName = strtolower(str_replace(' ', '_', $strName));
		$strName = basename($strName);

		// Create a new XML document
		$xml = new DOMDocument('1.0', 'UTF-8');
		$xml->formatOutput = true;

		// Root element
		$tables = $xml->createElement('tables');
		$tables = $xml->appendChild($tables);

		// Add the tables
		$this->addTableTlTheme($xml, $tables, $objTheme);
		$this->addTableTlStyleSheet($xml, $tables, $objTheme);
		$this->addTableTlModule($xml, $tables, $objTheme);
		$this->addTableTlLayout($xml, $tables, $objTheme);

		// Generate the archive
		$objArchive = new ZipWriter('system/tmp/'. $strName .'.zip');

		// Add the XML document
		$objArchive->addString($xml->saveXML(), 'system/tmp/'. $strName .'.xml');

		// Add the folders
		$arrFolders = deserialize($objTheme->folders);

		if (is_array($arrFolders) && count($arrFolders) > 0)
		{
			foreach ($this->eliminateNestedPaths($arrFolders) as $strFolder)
			{
				$this->addFolderToArchive($objArchive, $strFolder);
			}
		}

		// Add the template files
		$this->addTemplatesToArchive($objArchive, $objTheme->templates);

		// Close the archive
		$objArchive->close();

		// Open the "save as …" dialogue
		$objFile = new File('system/tmp/'. $strName .'.zip');

		header('Content-Type: ' . $objFile->mime);
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $objFile->basename . '"');
		header('Content-Length: ' . $objFile->filesize);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');

		$resFile = fopen(TL_ROOT . '/system/tmp/'. $strName .'.zip', 'rb');
		fpassthru($resFile);
		fclose($resFile);

		exit;
	}


	/**
	 * Add the table tl_theme
	 * @param object
	 * @param object
	 * @param object
	 */
	protected function addTableTlTheme(DOMDocument $xml, DOMElement $tables, Database_Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_theme');
		$table = $tables->appendChild($table);

		// Add the row
		$this->addDataRow($xml, $table, $objTheme);
	}


	/**
	 * Add the table tl_style_sheet
	 * @param object
	 * @param object
	 * @param object
	 */
	protected function addTableTlStyleSheet(DOMDocument $xml, DOMElement $tables, Database_Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_style_sheet');
		$table = $tables->appendChild($table);

		// Get all style sheets
		$objStyleSheet = $this->Database->prepare("SELECT * FROM tl_style_sheet WHERE pid=? ORDER BY name")
										->execute($objTheme->id);

		// Add the rows
		while ($objStyleSheet->next())
		{
			$this->addDataRow($xml, $table, $objStyleSheet);
			$this->addTableTlStyle($xml, $tables, $objTheme, $objStyleSheet->id);
		}
	}


	/**
	 * Add the table tl_style
	 * @param object
	 * @param object
	 * @param object
	 * @param integer
	 */
	protected function addTableTlStyle(DOMDocument $xml, DOMElement $tables, Database_Result $objTheme, $pid)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_style');
		$table = $tables->appendChild($table);

		// Get all styles
		$objStyle = $this->Database->prepare("SELECT * FROM tl_style WHERE pid=? ORDER BY sorting")
								   ->execute($pid);

		// Add the rows
		while ($objStyle->next())
		{
			$this->addDataRow($xml, $table, $objStyle);
		}
	}


	/**
	 * Add the table tl_module
	 * @param object
	 * @param object
	 * @param object
	 */
	protected function addTableTlModule(DOMDocument $xml, DOMElement $tables, Database_Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_module');
		$table = $tables->appendChild($table);

		// Get all modules
		$objModule = $this->Database->prepare("SELECT * FROM tl_module WHERE pid=? ORDER BY name")
									->execute($objTheme->id);

		// Add the rows
		while ($objModule->next())
		{
			$this->addDataRow($xml, $table, $objModule);
		}
	}


	/**
	 * Add the table tl_layout
	 * @param object
	 * @param object
	 * @param object
	 */
	protected function addTableTlLayout(DOMDocument $xml, DOMElement $tables, Database_Result $objTheme)
	{
		// Add the table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_layout');
		$table = $tables->appendChild($table);

		// Get all layouts
		$objLayout = $this->Database->prepare("SELECT * FROM tl_layout WHERE pid=? ORDER BY name")
									->execute($objTheme->id);

		// Add the rows
		while ($objLayout->next())
		{
			$this->addDataRow($xml, $table, $objLayout);
		}
	}


	/**
	 * Add a data row to the XML document
	 * @param object
	 * @param object
	 * @param object
	 */
	protected function addDataRow(DOMDocument $xml, DOMElement $table, Database_Result $objData)
	{
		$row = $xml->createElement('row');
		$row = $table->appendChild($row);

		foreach ($objData->row() as $k=>$v)
		{
			$field = $xml->createElement('field');
			$field->setAttribute('name', $k);
			$field = $row->appendChild($field);

			if (is_null($v))
			{
				$v = 'NULL';
			}

			$value = $xml->createTextNode($v);
			$value = $field->appendChild($value);
		}
	}


	/**
	 * Recursively add a folder to the archive
	 * @param object
	 * @param string
	 */
	protected function addFolderToArchive(ZipWriter $objArchive, $strFolder)
	{
		// Sanitize the folder name
		$strFolder = str_replace('../', '', $strFolder);
		$strFolder = preg_replace('@^'.preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '@').'/@', '', $strFolder);

		if ($strFolder == '')
		{
			$strTarget = 'tl_files';
			$strFolder = $GLOBALS['TL_CONFIG']['uploadPath'];
		}
		else
		{
			$strTarget = 'tl_files/' . $strFolder;
			$strFolder = $GLOBALS['TL_CONFIG']['uploadPath'] .'/'. $strFolder;
		}

		// Return if the folder does not exist
		if (!is_dir(TL_ROOT .'/'. $strFolder))
		{
			return;
		}

		// Recursively add the files and subfolders
		foreach (scan(TL_ROOT .'/'. $strFolder) as $strFile)
		{
			// Skip hidden resources
			if ($strFile == '.svn' || $strFile == '.DS_Store')
			{
				continue;
			}

			if (is_dir(TL_ROOT .'/'. $strFolder .'/'. $strFile))
			{
				$this->addFolderToArchive($objArchive, $strFolder .'/'. $strFile);
			}
			else
			{
				// Always store files in tl_files and convert the directory upon import
				$objArchive->addFile($strFolder .'/'. $strFile, $strTarget .'/'. $strFile);
			}
		}
	}


	/**
	 * Add templates to the archive
	 * @param object
	 * @param string
	 */
	protected function addTemplatesToArchive(ZipWriter $objArchive, $strFolder)
	{
		// Sanitize the folder name
		$strFolder = str_replace('../', '', $strFolder);
		$strFolder = preg_replace('@^templates/@', '', $strFolder);

		if ($strFolder == '')
		{
			$strFolder = 'templates';
		}
		else
		{
			$strFolder = 'templates/' . $strFolder;
		}

		// Return if the folder does not exist
		if (!is_dir(TL_ROOT .'/'. $strFolder))
		{
			return;
		}

		// Add all .tpl files to the archive
		foreach (scan(TL_ROOT .'/'. $strFolder) as $strFile)
		{
			if (preg_match('/\.tpl$/', $strFile))
			{
				$objArchive->addFile($strFolder .'/'. $strFile);
			}
		}
	}
}

?>