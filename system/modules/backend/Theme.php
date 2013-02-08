<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Theme
 *
 * Provide methods to handle themes.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class Theme extends Backend
{

	/**
	 * Import a theme
	 */
	public function importTheme()
	{
		if ($this->Input->post('FORM_SUBMIT') == 'tl_theme_import')
		{
			$source = $this->Input->post('source', true);

			// Check the file names
			if (!$source || !is_array($source))
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			$arrFiles = array();

			// Skip invalid entries
			foreach ($source as $strZipFile)
			{
				// Skip folders
				if (is_dir(TL_ROOT . '/' . $strZipFile))
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['importFolder'], basename($strZipFile)));
					continue;
				}

				$objFile = new File($strZipFile);

				// Skip anything but .cto files
				if ($objFile->extension != 'cto')
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension));
					continue;
				}

				$arrFiles[] = $strZipFile;
			}

			// Check wether there are any files left
			if (empty($arrFiles))
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			// Store the field names of the theme tables
			$arrDbFields = array
			(
				'tl_theme'       => $this->Database->getFieldNames('tl_theme'),
				'tl_style_sheet' => $this->Database->getFieldNames('tl_style_sheet'),
				'tl_style'       => $this->Database->getFieldNames('tl_style'),
				'tl_module'      => $this->Database->getFieldNames('tl_module'),
				'tl_layout'      => $this->Database->getFieldNames('tl_layout')
			);

			// Proceed
			if ($this->Input->post('confirm') == 1)
			{
				return $this->extractThemeFiles($arrFiles, $arrDbFields);
			}
			else
			{
				return $this->compareThemeFiles($arrFiles, $arrDbFields);
			}
		}

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_theme']['fields']['source'], 'source', null, 'source', 'tl_theme'));

		// Return the form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=importTheme', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_theme']['importTheme'][1].'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="tl_theme_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_theme_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_tbox">
  <h3><label for="source">'.$GLOBALS['TL_LANG']['tl_theme']['source'][0].'</label> <a href="contao/files.php" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['fileManager']) . '" data-lightbox="files 765 80%">' . $this->generateImage('filemanager.gif', $GLOBALS['TL_LANG']['MSC']['fileManager'], 'style="vertical-align:text-bottom"') . '</a></h3>'.$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_theme']['source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_theme']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_theme']['importTheme'][0]).'">
</div>

</div>
</form>';
	}


	/**
	 * Compare the theme tables with the local database and check
	 * whether there are custom layout sections 
	 * @param array
	 * @param array
	 */
	protected function compareThemeFiles($arrFiles, $arrDbFields)
	{
		$return = '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=importTheme', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_theme']['checking_theme'].'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="tl_theme_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_theme_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="confirm" value="1">';

		// Add the hidden fields
		foreach ($arrFiles as $strFile)
		{
			$return .= "\n" . '<input type="hidden" name="source[]" value="'.$strFile.'">';
		}

		$count = 0;

		// Check the theme data
		foreach ($arrFiles as $strFile)
		{
			$return .= '

<div class="tl_'. (($count++ < 1) ? 't' : '') .'box">
  <h3>'. basename($strFile) .'</h3>
  <h4>'.$GLOBALS['TL_LANG']['tl_theme']['tables_fields'].'</h4>';

			// Find the XML file
			$objArchive = new ZipReader($strFile);

			// Continue if there is no XML file
			if ($objArchive->getFile('theme.xml') === false)
			{
				$blnHasError = true;
				$return .= "\n  " . '<p style="margin:0;color:#c55">'. sprintf($GLOBALS['TL_LANG']['tl_theme']['missing_xml'], basename($strFile)) ."</p>\n</div>";

				continue;
			}

			// Open the XML file
			$xml = new DOMDocument();
			$xml->preserveWhiteSpace = false;
			$xml->loadXML($objArchive->unzip());
			$tables = $xml->getElementsByTagName('table');

			$tl_layout = null;
			$blnHasError = false;

			// Loop through the tables
			for ($i=0; $i<$tables->length; $i++)
			{
				$rows = $tables->item($i)->childNodes;
				$table = $tables->item($i)->getAttribute('name');

				// Skip invalid tables
				if ($table != 'tl_theme' && $table != 'tl_style_sheet' && $table != 'tl_style' && $table != 'tl_module' && $table != 'tl_layout')
				{
					continue;
				}

				$fields = $rows->item(0)->childNodes;

				// Store the tl_layout element
				if ($table == 'tl_layout')
				{
					$tl_layout = $tables->item($i)->childNodes;
				}

				// Loop through the fields
				for ($j=0; $j<$fields->length; $j++)
				{
					$name = $fields->item($j)->getAttribute('name');

					// Print a warning if a field is missing
					if (!in_array($name, $arrDbFields[$table]))
					{
						$blnHasError = true;
						$return .= "\n  " . '<p style="margin:0; color:#c55">'. sprintf($GLOBALS['TL_LANG']['tl_theme']['missing_field'], $table .'.'. $name) .'</p>';
					}
				}
			}

			// Confirmation
			if (!$blnHasError)
			{
				$return .= "\n  " . '<p style="margin:0;color:#8ab858">'. $GLOBALS['TL_LANG']['tl_theme']['tables_ok'] .'</p>';
			}

			$return .= '
  <h4>'.$GLOBALS['TL_LANG']['tl_theme']['custom_sections'].'</h4>';

			$blnHasLayout = false;
			$arrSections = trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']);
			$arrProcessed = array();

			// Loop through tl_layout
			for ($i=0; $i<$tl_layout->length; $i++)
			{
				$fields = $tl_layout->item($i)->childNodes;

				// Loop through the fields
				for ($j=0; $j<$fields->length; $j++)
				{
					if ($fields->item($j)->getAttribute('name') != 'modules')
					{
						continue;
					}

					$modules = deserialize($fields->item($j)->nodeValue);

					// Continue if there are no modules
					if (!is_array($modules) || empty($modules))
					{
						continue;
					}

					// Check all columns
					foreach ($modules as $mod)
					{
						// Default columns
						if ($mod['col'] == 'header' || $mod['col'] == 'left' || $mod['col'] == 'main' || $mod['col'] == 'right' || $mod['col'] == 'footer')
						{
							continue;
						}

						// Do not show multiple warnings
						if (in_array($mod['col'], $arrProcessed) || in_array($mod['col'], $arrSections))
						{
							continue;
						}

						$blnHasLayout = true;
						$arrProcessed[] = $mod['col'];

						$return .= "\n  " . '<p style="margin:0;color:#c55">'. sprintf($GLOBALS['TL_LANG']['tl_theme']['missing_section'], $mod['col']) .'</p>';
					}
				}
			}

			// Confirmation
			if (!$blnHasLayout)
			{
				$return .= "\n  " . '<p style="margin:0;color:#8ab858">'. $GLOBALS['TL_LANG']['tl_theme']['sections_ok'] .'</p>';
			}

			// Check the custom templates
			$return .= '
  <h4>'.$GLOBALS['TL_LANG']['tl_theme']['custom_templates'].'</h4>';

			$objArchive->reset();
			$blnTplExists = false;

			// Loop through the archive
			while ($objArchive->next())
			{
				if (strncmp($objArchive->file_name, 'templates/', 10) !== 0)
				{
					continue;
				}

				if (file_exists(TL_ROOT .'/'. $objArchive->file_name))
				{
					$blnTplExists = true;
					$return .= "\n  " . '<p style="margin:0;color:#c55">'. sprintf($GLOBALS['TL_LANG']['tl_theme']['template_exists'], $objArchive->file_name) .'</p>';
				}
			}

			// Confirmation
			if (!$blnTplExists)
			{
				$return .= "\n  " . '<p style="margin:0;color:#8ab858">'. $GLOBALS['TL_LANG']['tl_theme']['templates_ok'] .'</p>';
			}

			$return .= '
</div>';
		}

		// Return the form
		return $return . '

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['continue']).'">
</div>

</div>
</form>';
	}


	/**
	 * Extract the theme files and write the data to the database
	 * @param array
	 * @param array
	 */
	protected function extractThemeFiles($arrFiles, $arrDbFields)
	{
		foreach ($arrFiles as $strZipFile)
		{
			$xml = null;

			// Open the archive
			$objArchive = new ZipReader($strZipFile);

			// Extract all files
			while ($objArchive->next())
			{
				// Load the XML file
				if ($objArchive->file_name == 'theme.xml')
				{
					$xml = new DOMDocument();
					$xml->preserveWhiteSpace = false;
					$xml->loadXML($objArchive->unzip());
					continue;
				}

				// Limit file operations to tl_files and the templates directory
				if (strncmp($objArchive->file_name, 'tl_files/', 9) !== 0 && strncmp($objArchive->file_name, 'templates/', 10) !== 0)
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['invalidFile'], $objArchive->file_name));
					continue;
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
					$this->addErrorMessage($e->getMessage());
				}
			}

			// Continue if there is no XML file
			if (!$xml instanceof DOMDocument)
			{
				$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['tl_theme']['missing_xml'], basename($strZipFile)));
				continue;
			}

			$arrMapper = array();
			$tables = $xml->getElementsByTagName('table');

			// Lock the tables
			$arrLocks = array
			(
				'tl_theme'       => 'WRITE',
				'tl_style_sheet' => 'WRITE',
				'tl_style'       => 'WRITE',
				'tl_module'      => 'WRITE',
				'tl_layout'      => 'WRITE'
			);

			$this->Database->lockTables($arrLocks);

			// Get the current auto_increment values
			$tl_theme = $this->Database->getNextId('tl_theme');
			$tl_style_sheet = $this->Database->getNextId('tl_style_sheet');
			$tl_style = $this->Database->getNextId('tl_style');
			$tl_module = $this->Database->getNextId('tl_module');
			$tl_layout = $this->Database->getNextId('tl_layout');

			// Loop through the tables
			for ($i=0; $i<$tables->length; $i++)
			{
				$rows = $tables->item($i)->childNodes;
				$table = $tables->item($i)->getAttribute('name');

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
							$id = ${$table}++;
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
						elseif (($table == 'tl_theme' || $table == 'tl_style_sheet') && $name == 'name')
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

						// Adjust the file paths in style sheets and modules
						elseif ($GLOBALS['TL_CONFIG']['uploadPath'] != 'tl_files' && strpos($value, 'tl_files') !== false)
						{
							$tmp = deserialize($value);

							if (is_array($tmp))
							{
								foreach ($tmp as $kk=>$vv)
								{
									$tmp[$kk] = str_replace('tl_files/', $GLOBALS['TL_CONFIG']['uploadPath'] . '/', $vv);
								}

								$value = serialize($tmp);
							}
							else
							{
								$value = str_replace('tl_files/', $GLOBALS['TL_CONFIG']['uploadPath'] . '/', $value);
							}
						}

						$set[$name] = $value;
					}

					// Skip fields that are not in the database (e.g. because of missing extensions)
					foreach ($set as $k=>$v)
					{
						if (!in_array($k, $arrDbFields[$table]))
						{
							unset($set[$k]);
						}
					}

					// Create the templates folder even if it is empty (see #4793)
					if ($table == 'tl_theme' && isset($set['templates']) && strncmp($set['templates'], 'templates/', 10) === 0 && !is_dir(TL_ROOT . '/' . $set['templates']))
					{
						new Folder($set['templates']);
					}

					// Update the datatbase
					$this->Database->prepare("INSERT INTO ". $table ." %s")->set($set)->execute();
				}
			}

			// Unlock the tables
			$this->Database->unlockTables();

			// Update the style sheets
			$this->import('StyleSheets');
			$this->StyleSheets->updateStyleSheets();

			// Notify the user
			$this->addConfirmationMessage(sprintf($GLOBALS['TL_LANG']['tl_theme']['theme_imported'], basename($strZipFile)));
		}

		// Redirect
		$this->setCookie('BE_PAGE_OFFSET', 0, 0);
		$this->redirect(str_replace('&key=importTheme', '', $this->Environment->request));
	}


	/**
	 * Export a theme
	 * @param DataContainer
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
		$strName = preg_replace('/[^A-Za-z0-9\._-]/', '', $strName);
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
		$strTmp = md5(uniqid(mt_rand(), true));
		$objArchive = new ZipWriter('system/tmp/'. $strTmp);

		// Add the XML document
		$objArchive->addString($xml->saveXML(), 'theme.xml');

		// Add the folders
		$arrFolders = deserialize($objTheme->folders);

		if (is_array($arrFolders) && !empty($arrFolders))
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
		$objFile = new File('system/tmp/'. $strTmp);

		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $strName . '.cto"');
		header('Content-Length: ' . $objFile->filesize);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');

		$resFile = fopen(TL_ROOT . '/system/tmp/'. $strTmp, 'rb');
		fpassthru($resFile);
		fclose($resFile);

		exit;
	}


	/**
	 * Add the table tl_theme
	 * @param DOMDocument
	 * @param DOMElement
	 * @param Database_Result
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
	 * @param DOMDocument
	 * @param DOMElement
	 * @param Database_Result
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
		}

		$objStyleSheet->reset();

		// Add the child table
		$table = $xml->createElement('table');
		$table->setAttribute('name', 'tl_style');
		$table = $tables->appendChild($table);

		// Add the child rows
		while ($objStyleSheet->next())
		{
			// Get all format definitions
			$objStyle = $this->Database->prepare("SELECT * FROM tl_style WHERE pid=? ORDER BY sorting")
									   ->execute($objStyleSheet->id);

			// Add the rows
			while ($objStyle->next())
			{
				$this->addDataRow($xml, $table, $objStyle);
			}
		}
	}


	/**
	 * Add the table tl_module
	 * @param DOMDocument
	 * @param DOMElement
	 * @param Database_Result
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
	 * @param DOMDocument
	 * @param DOMElement
	 * @param Database_Result
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
	 * @param DOMDocument
	 * @param DOMElement
	 * @param Database_Result
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

			if ($v === null)
			{
				$v = 'NULL';
			}

			$value = $xml->createTextNode($v);
			$value = $field->appendChild($value);
		}
	}


	/**
	 * Recursively add a folder to the archive
	 * @param ZipWriter
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
	 * @param ZipWriter
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

		$arrAllowed = trimsplit(',', $GLOBALS['TL_CONFIG']['templateFiles']);

		// Add all template files to the archive
		foreach (scan(TL_ROOT .'/'. $strFolder) as $strFile)
		{
			if (preg_match('/\.(' . implode('|', $arrAllowed) . ')$/', $strFile) && strncmp($strFile, 'be_', 3) !== 0 && strncmp($strFile, 'nl_', 3) !== 0)
			{
				$objArchive->addFile($strFolder .'/'. $strFile);
			}
		}
	}
}

?>